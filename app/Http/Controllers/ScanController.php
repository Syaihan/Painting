<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;
use App\Models\Bom;
use App\Models\ChildPart;
use App\Models\NgSealingLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ScanController extends Controller
{
    /**
     * Helper: Mendapatkan data operator berdasarkan role session.
     */
    private function getOperatorsByRole()
    {
        $roleId = session('operator_id'); 

        if ($roleId == 3) {
            return Operator::where('grup', 'A')->get();
        } elseif ($roleId == 4) {
            return Operator::where('grup', 'B')->get();
        }
        
        return Operator::all();
    }

    /**
     * Helper: Mendapatkan informasi PIC dan Grup dari session.
     */
    private function getPicAndGroup()
    {
        return [
            'picName' => session('operator_name', 'System User'),
            'grup'    => session('operator_role', '-')
        ];
    }

    public function console()
    {
        $activeMenu = 'Scan Console';
        $operators  = $this->getOperatorsByRole();

        $history = DB::table('t_log_scan')
            ->select(
                't_log_scan_timestamp as jam',
                't_log_scan_fg as fq',
                't_log_scan_fg_part_number as part_number',
                't_log_scan_qty as qty',
                't_log_scan_operator as operator',
                't_log_scan_pic as pic',
                't_log_scan_grup as grup'
            )
            ->orderBy('t_log_scan_timestamp', 'desc')
            ->limit(50)
            ->get();

        return view('scan.console', compact('activeMenu', 'operators', 'history'));
    }

    public function consolestore(Request $request)
    {
        $lockKey = 'scan_store_' . session('operator_id') . '_' . $request->barcode;
        if (Cache::has($lockKey)) {
            return redirect()->back()->with('error', 'Proses sedang berjalan, mohon jangan menekan tombol berkali-kali.');
        }
        Cache::put($lockKey, true, now()->addSeconds(3));

        $request->validate([
            'operator' => 'required',
            'barcode'  => 'required',
            'qty'      => 'required|integer|min:1'
        ]);

        $authInfo = $this->getPicAndGroup();
        $operatorData = Operator::where('nama', $request->operator)->first();
        $grup = $operatorData ? $operatorData->grup : 'A';

        DB::beginTransaction();

        try {
            $boms = Bom::where('barcode_fg', $request->barcode)->get();

            if ($boms->isEmpty()) {
                throw new \Exception("Barcode Finish Good [{$request->barcode}] tidak ditemukan dalam data BOM.");
            }

            foreach ($boms as $bom) {
                $totalReduction = $bom->bom_qty * $request->qty;

                $childPart = ChildPart::where('part_number_child_part', $bom->part_number_child_part)
                    ->lockForUpdate()
                    ->first();

                if (!$childPart) {
                    throw new \Exception("Material child part [{$bom->part_number_child_part}] tidak ditemukan.");
                }

                if ($childPart->child_part_qty < $totalReduction) {
                    throw new \Exception("Stok tidak mencukupi untuk material: {$childPart->material_name} (Sisa: {$childPart->child_part_qty}, Dibutuhkan: {$totalReduction})");
                }

                $childPart->child_part_qty -= $totalReduction;
                $childPart->save();
            }

            DB::table('t_log_scan')->insert([
                't_log_scan_fg'             => $request->barcode,
                't_log_scan_fg_part_number' => $boms->first()->part_number_fg,
                't_log_scan_qty'            => $request->qty,
                't_log_scan_operator'       => $request->operator,
                't_log_scan_pic'            => $authInfo['picName'],
                't_log_scan_grup'           => $grup,
                't_log_scan_timestamp'      => now('Asia/Jakarta'),
            ]);

            DB::commit();

            return redirect()->route('scan.console')->with('success', 'Scan berhasil dieksekusi dan stok berhasil dipotong!');

        } catch (\Exception $e) {
            DB::rollBack();
            Cache::forget($lockKey);

            return redirect()->back()
                ->with('error', 'Gagal memproses scan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function stockCard(Request $request)
    {
        $activeMenu = 'Stock Card';
        $search = $request->input('search');

        $query = ChildPart::query();

        if ($search) {
            $query->where('part_number_child_part', 'like', "%{$search}%")
                ->orWhere('material_name', 'like', "%{$search}%");
        }

        $childParts = $query->orderBy('part_number_child_part', 'asc')->get();
        $adjustmentLogs = DB::table('t_log_stock_adjustment')->orderBy('t_log_stock_adjustment_timestamp', 'desc')->paginate(10);

        return view('scan.stock_card', compact('activeMenu', 'childParts', 'search', 'adjustmentLogs'));
    }

    public function stockInForm(Request $request)
    {
        $activeMenu = 'Stock In';
        $search = $request->input('search');

        $childParts = ChildPart::orderBy('part_number_child_part', 'asc')->get();

        $query = DB::table('t_log_stock_in')->orderBy('t_log_stock_in_timestamp', 'desc');

        if ($search) {
            $query->where('t_log_stock_in_child_part', 'like', "%{$search}%")
                ->orWhere('t_log_stock_in_part_name', 'like', "%{$search}%")
                ->orWhere('t_log_stock_in_delivery', 'like', "%{$search}%")
                ->orWhere('t_log_stock_in_pic', 'like', "%{$search}%");
        }

        $stockInLogs = $query->paginate(10);
        $authInfo = $this->getPicAndGroup();

        return view('scan.stock_in', array_merge(compact('activeMenu', 'childParts', 'stockInLogs', 'search'), $authInfo));
    }

    public function stockInStore(Request $request)
    {
        $lockKey = 'stock_in_' . session('operator_id') . '_' . $request->t_log_stock_in_child_part;
        if (Cache::has($lockKey)) {
            return redirect()->back()->with('error', 'Proses stock in sedang berjalan, mohon jangan menekan tombol berkali-kali.');
        }
        Cache::put($lockKey, true, now()->addSeconds(3));

        $request->validate([
            't_log_stock_in_child_part' => 'required|string',
            't_log_stock_in_qty'        => 'required|integer|min:1',
            't_log_stock_in_delivery'   => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            $childPart = ChildPart::where('part_number_child_part', $request->t_log_stock_in_child_part)
                ->lockForUpdate()
                ->first();

            if (!$childPart) {
                throw new \Exception("Part Number Child Part tidak ditemukan di database!");
            }

            $authInfo = $this->getPicAndGroup();

            DB::table('t_log_stock_in')->insert([
                't_log_stock_in_child_part' => $childPart->part_number_child_part,
                't_log_stock_in_part_name'  => $childPart->material_name,
                't_log_stock_in_qty'        => $request->t_log_stock_in_qty,
                't_log_stock_in_pic'        => $authInfo['picName'],
                't_log_stock_in_grup'       => $authInfo['grup'],
                't_log_stock_in_delivery'   => $request->t_log_stock_in_delivery,
                't_log_stock_in_timestamp'  => now('Asia/Jakarta'),
            ]);

            $childPart->increment('child_part_qty', $request->t_log_stock_in_qty);

            DB::commit();

            return redirect()->route('scan.stock_in.form')->with('success', 'Stok masuk berhasil disimpan dan stok material bertambah!');

        } catch (\Exception $e) {
            DB::rollBack();
            Cache::forget($lockKey);

            return back()->with('error', 'Gagal memproses stock in: ' . $e->getMessage())->withInput();
        }
    }

    public function adjustmentStore(Request $request)
    {
        $lockKey = 'stock_adj_' . session('operator_id') . '_' . $request->t_log_stock_adjustment_child_part;
        if (Cache::has($lockKey)) {
            return redirect()->back()->with('error', 'Proses adjustment sedang berjalan, mohon jangan menekan tombol berkali-kali.');
        }
        Cache::put($lockKey, true, now()->addSeconds(3));

        $request->validate([
            't_log_stock_adjustment_child_part' => 'required|string',
            't_log_stock_adjustment_qty'        => 'required|integer|min:0',
            't_log_stock_adjustment_reason'     => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $childPart = ChildPart::where('part_number_child_part', $request->t_log_stock_adjustment_child_part)
                ->lockForUpdate()
                ->first();

            if (!$childPart) {
                throw new \Exception("Part Number Child Part tidak ditemukan di database!");
            }

            $stockBefore   = $childPart->child_part_qty;
            $stockPhysical = $request->t_log_stock_adjustment_qty;

            if ($stockBefore == $stockPhysical) {
                Cache::forget($lockKey);
                return redirect()->back()->with('error', 'Stok fisik di lapangan sudah sama persis dengan sistem. Tidak ada perubahan yang disimpan.')->withInput();
            }

            if ($stockPhysical > $stockBefore) {
                $type           = 'IN';
                $qtyAdjustment  = $stockPhysical - $stockBefore;
            } else {
                $type           = 'OUT';
                $qtyAdjustment  = $stockBefore - $stockPhysical;
            }

            $stockAfter = $stockPhysical;

            $childPart->child_part_qty = $stockAfter;
            $childPart->save();

            $authInfo = $this->getPicAndGroup();

            DB::table('t_log_stock_adjustment')->insert([
                't_log_stock_adjustment_child_part'     => $childPart->part_number_child_part,
                't_log_stock_adjustment_part_name'      => $childPart->material_name,
                't_log_stock_adjustment_type'           => $type,
                't_log_stock_adjustment_qty'            => $qtyAdjustment,
                't_log_stock_adjustment_stock_before'   => $stockBefore,
                't_log_stock_adjustment_stock_after'    => $stockAfter,
                't_log_stock_adjustment_reason'         => $request->t_log_stock_adjustment_reason,
                't_log_stock_adjustment_pic'            => $authInfo['picName'],
                't_log_stock_adjustment_grup'           => $authInfo['grup'],
                't_log_stock_adjustment_timestamp'      => now('Asia/Jakarta'),
            ]);

            DB::commit();

            return redirect()->route('scan.stock_card')->with('success', "Stock adjustment berhasil! Stok disesuaikan menjadi {$stockAfter} pcs (Selisih {$type} {$qtyAdjustment} pcs).");

        } catch (\Exception $e) {
            DB::rollBack();
            Cache::forget($lockKey);

            return back()->with('error', 'Gagal memproses adjustment: ' . $e->getMessage())->withInput();
        }
    }

    public function nglog()
    {
        $activeMenu = 'NG Log';
        $ngHistory  = NgSealingLog::orderBy('t_log_ng_sealing_id', 'desc')->get();
        $childParts = ChildPart::orderBy('part_number_child_part', 'asc')->get();
        $operators  = $this->getOperatorsByRole();
        $grup       = session('operator_role', '-');

        return view('scan.nglog', compact('ngHistory', 'childParts', 'activeMenu', 'operators', 'grup'));
    }

    public function nglogstore(Request $request)
    {
        $request->validate([
            't_log_ng_sealing_child_part' => 'required',
            't_log_ng_sealing_qty'        => 'required|integer|min:1',
            't_log_ng_sealing_ket'        => 'required',
            't_log_ng_sealing_operator'   => 'required',
        ]);

        DB::beginTransaction();
        try {
            $childPart = ChildPart::where('part_number_child_part', $request->t_log_ng_sealing_child_part)
                ->lockForUpdate()
                ->first();

            if (!$childPart) {
                throw new \Exception("Material child part [{$request->t_log_ng_sealing_child_part}] tidak ditemukan.");
            }

            if ($childPart->child_part_qty < $request->t_log_ng_sealing_qty) {
                throw new \Exception("Stok tidak mencukupi untuk material: {$childPart->material_name} (Sisa: {$childPart->child_part_qty}, Dibutuhkan: {$request->t_log_ng_sealing_qty})");
            }

            $childPart->child_part_qty -= $request->t_log_ng_sealing_qty;
            $childPart->save();

            NgSealingLog::create([
                't_log_ng_sealing_child_part' => $request->t_log_ng_sealing_child_part,
                't_log_ng_sealing_part_name'  => $request->t_log_ng_sealing_part_name,
                't_log_ng_sealing_qty'        => $request->t_log_ng_sealing_qty,
                't_log_ng_sealing_ket'        => $request->t_log_ng_sealing_ket,
                't_log_ng_sealing_operator'   => $request->t_log_ng_sealing_operator,
                't_log_ng_sealing_grup'       => $request->t_log_ng_sealing_grup,
                't_log_ng_sealing_timestamp'  => Carbon::now('Asia/Jakarta'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data NG berhasil disimpan dan stok child part otomatis terpotong.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses NG: ' . $e->getMessage());
        }
    }
}