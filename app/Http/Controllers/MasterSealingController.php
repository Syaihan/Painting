<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\ChildPart;
use App\Models\FinishGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MasterSealingController extends Controller
{
    public function index(Request $request)
    {
        $activeMenu = 'Master Sealing';
        $activeTab = $request->input('tab', 'fg'); // Default tab: Finish Good

        // Pencarian untuk masing-masing tabel
        $searchFg = $request->input('search_fg');
        $searchCp = $request->input('search_cp');
        $searchBom = $request->input('search_bom');

        // Cache key disesuaikan dengan parameter pencarian agar hasil berbeda per query
        $finishGoodsCacheKey = 'master_sealing_fg:' . md5($searchFg ?? '');
        $childPartsCacheKey  = 'master_sealing_cp:' . md5($searchCp ?? '');
        $bomsCacheKey        = 'master_sealing_bom:' . md5($searchBom ?? '');

        $finishGoods = Cache::remember($finishGoodsCacheKey, now()->addMinutes(60), function () use ($searchFg) {
            return FinishGood::when($searchFg, function ($q, $search) {
                $q->where('barcode_fg', 'like', "%{$search}%")
                    ->orWhere('part_number_fg', 'like', "%{$search}%")
                    ->orWhere('fg_name', 'like', "%{$search}%");
            })->orderby('barcode_fg', 'asc')->get()->toArray();
        });

        $childParts = Cache::remember($childPartsCacheKey, now()->addMinutes(60), function () use ($searchCp) {
            return ChildPart::when($searchCp, function ($q, $search) {
                $q->where('part_number_child_part', 'like', "%{$search}%")
                    ->orWhere('material_name', 'like', "%{$search}%");
            })->orderBy('material_name', 'asc')->get()->toArray();
        });

        $boms = Cache::remember($bomsCacheKey, now()->addMinutes(60), function () use ($searchBom) {
            return Bom::with(['finishGood', 'childPart'])
                ->when($searchBom, function ($q, $search) {
                    $q->where('barcode_fg', 'like', "%{$search}%")
                        ->orWhere('part_number_fg', 'like', "%{$search}%")
                        ->orWhere('part_number_child_part', 'like', "%{$search}%");
                })->orderby('barcode_fg', 'asc')->get()->toArray();
        });

        return view('master.master_sealing', compact(
            'activeMenu', 'activeTab',
            'finishGoods', 'childParts', 'boms',
            'searchFg', 'searchCp', 'searchBom'
        ));
    }

    // --- CRUD FINISH GOOD ---
    public function storeFg(Request $request)
    {
        $request->validate([
            'barcode_fg' => 'required|string|unique:m_finish_good,barcode_fg',
            'part_number_fg' => 'required|string',
            'fg_name' => 'required|string|max:255',
        ]);

        FinishGood::create($request->only(['barcode_fg', 'part_number_fg', 'fg_name']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'fg'])->with('success', 'Data Finish Good berhasil ditambahkan!');
    }

    public function updateFg(Request $request, $id)
    {
        $request->validate([
            'part_number_fg' => 'required|string',
            'fg_name' => 'required|string|max:255',
        ]);

        FinishGood::where('barcode_fg', $id)->firstOrFail()->update($request->only(['part_number_fg', 'fg_name']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'fg'])->with('success', 'Data Finish Good berhasil diperbarui!');
    }

    public function destroyFg($id)
    {
        FinishGood::where('barcode_fg', $id)->firstOrFail()->delete();

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'fg'])->with('success', 'Data Finish Good berhasil dihapus!');
    }

    // --- CRUD CHILD PART ---
    public function storeCp(Request $request)
    {
        $request->validate([
            'part_number_child_part' => 'required|string|unique:m_child_part,part_number_child_part',
            'material_name' => 'required|string|max:255',
            'child_part_qty' => 'required|integer|min:0',
        ]);

        ChildPart::create($request->only(['part_number_child_part', 'material_name', 'child_part_qty']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'cp'])->with('success', 'Data Child Part berhasil ditambahkan!');
    }

    public function updateCp(Request $request, $id)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
            'child_part_qty' => 'required|integer|min:0',
        ]);

        ChildPart::where('part_number_child_part', $id)->firstOrFail()->update($request->only(['material_name', 'child_part_qty']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'cp'])->with('success', 'Data Child Part berhasil diperbarui!');
    }

    public function destroyCp($id)
    {
        ChildPart::where('part_number_child_part', $id)->firstOrFail()->delete();

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'cp'])->with('success', 'Data Child Part berhasil dihapus!');
    }

    // --- CRUD BOM ---
    public function storeBom(Request $request)
    {
        $request->validate([
            'barcode_fg' => 'required|string',
            'part_number_fg' => 'required|string',
            'part_number_child_part' => 'required|string',
            'bom_qty' => 'required|integer|min:1',
        ]);

        Bom::create($request->only(['barcode_fg', 'part_number_fg', 'part_number_child_part', 'bom_qty']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'bom'])->with('success', 'Data BOM berhasil ditambahkan!');
    }

    public function updateBom(Request $request, $barcode, $childPart)
    {
        $request->validate([
            'barcode_fg' => 'required|string',
            'part_number_fg' => 'required|string',
            'part_number_child_part' => 'required|string',
            'bom_qty' => 'required|integer|min:1',
        ]);

        Bom::where('barcode_fg', $barcode)
            ->where('part_number_child_part', $childPart)
            ->update($request->only(['barcode_fg', 'part_number_fg', 'part_number_child_part', 'bom_qty']));

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'bom'])->with('success', 'Data BOM berhasil diperbarui!');
    }

    public function destroyBom($barcode, $childPart)
    {
        Bom::where('barcode_fg', $barcode)
            ->where('part_number_child_part', $childPart)
            ->delete();

        $this->clearMasterCache();

        return redirect()->route('master.sealing.index', ['tab' => 'bom'])->with('success', 'Data BOM berhasil dihapus!');
    }

    /**
     * Hapus seluruh cache master sealing sehingga data akan di-refresh pada request berikutnya.
     */
    private function clearMasterCache()
    {
        Cache::flush();
    }
}
