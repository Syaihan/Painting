<?php

namespace App\Http\Controllers;

use App\Models\FinishGood;
use App\Models\SealingPlan;
use App\Models\SealingPlanUpdateLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SealingPlanController extends Controller
{
    public function index(Request $request)
    {
        $shiftData = getActiveShiftData();
        $shiftName = $shiftData['shift_name'];
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Ambil semua FG
        $finishGoods = FinishGood::orderBy('barcode_fg', 'asc')->get();

        // AUTO-GENERATE: Buat plan otomatis untuk semua FG jika belum ada untuk shift ini hari ini
        $createdBy = session('operator_name', 'System');
        foreach ($finishGoods as $fg) {
            $exists = SealingPlan::where('t_log_sealing_plan_date', $today)
                ->where('t_log_sealing_plan_shift_name', $shiftName)
                ->where('t_log_sealing_plan_barcode_fg', $fg->barcode_fg)
                ->exists();

            if (!$exists) {
                SealingPlan::create([
                    't_log_sealing_plan_date'             => $today,
                    't_log_sealing_plan_shift_name'       => $shiftName,
                    't_log_sealing_plan_barcode_fg'       => $fg->barcode_fg,
                    't_log_sealing_plan_planned_qty'      => 0,
                    't_log_sealing_plan_actual_qty'       => 0,
                    't_log_sealing_plan_status'           => 'planned',
                    't_log_sealing_plan_created_by'       => $createdBy,
                ]);
            }
        }

        // Ambil plan yang sudah ada untuk shift ini hari ini (termasuk yang baru dibuat)
        $existingPlans = SealingPlan::where('t_log_sealing_plan_date', $today)
            ->where('t_log_sealing_plan_shift_name', $shiftName)
            ->get()
            ->keyBy('t_log_sealing_plan_barcode_fg');

        $activeMenu = 'Rencana Sealing';

        return view('sealing.plan', compact(
            'activeMenu',
            'finishGoods',
            'existingPlans',
            'shiftData',
            'today'
        ));
    }

    public function store(Request $request)
    {
        $shiftData = getActiveShiftData();
        $shiftName = $shiftData['shift_name'];
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $createdBy = session('operator_name', 'Operator');

        $request->validate([
            'planned_qty' => 'nullable|array',
        ]);

        foreach ($request->input('planned_qty', []) as $barcodeFg => $qty) {
            $qty = (int) $qty;
            if ($qty < 0) {
                $qty = 0;
            }

            // Cek apakah sudah ada plan untuk tanggal+shift+FG ini
            $existingPlan = SealingPlan::where('t_log_sealing_plan_date', $today)
                ->where('t_log_sealing_plan_shift_name', $shiftName)
                ->where('t_log_sealing_plan_barcode_fg', $barcodeFg)
                ->first();

            $oldQty = $existingPlan ? $existingPlan->t_log_sealing_plan_planned_qty : null;

            if ($existingPlan) {
                // UPDATE: hanya ubah planned_qty, JANGAN sentuh actual_qty
                $existingPlan->update([
                    't_log_sealing_plan_planned_qty' => $qty,
                    't_log_sealing_plan_status' => 'planned',
                ]);
            } else {
                // CREATE: buat baru dengan actual_qty = 0
                SealingPlan::create([
                    't_log_sealing_plan_date'             => $today,
                    't_log_sealing_plan_shift_name'       => $shiftName,
                    't_log_sealing_plan_barcode_fg'       => $barcodeFg,
                    't_log_sealing_plan_planned_qty'      => $qty,
                    't_log_sealing_plan_actual_qty'       => 0,
                    't_log_sealing_plan_status'           => 'planned',
                    't_log_sealing_plan_created_by'       => $createdBy,
                ]);
            }

            // BUAT LOG HANYA JIKA ADA UPDATE (data lama ada dan qty berubah)
            if ($existingPlan && $oldQty !== $qty) {
                $fg = FinishGood::where('barcode_fg', $barcodeFg)->first();

                SealingPlanUpdateLog::create([
                    't_log_sealing_plan_update_log_date' => $today,
                    't_log_sealing_plan_update_log_shift_name' => $shiftName,
                    't_log_sealing_plan_update_log_barcode_fg' => $barcodeFg,
                    't_log_sealing_plan_update_log_part_number_fg' => $fg ? $fg->part_number_fg : '',
                    't_log_sealing_plan_update_log_fg_name' => $fg ? $fg->fg_name : '',
                    't_log_sealing_plan_update_log_old_qty' => $oldQty,
                    't_log_sealing_plan_update_log_new_qty' => $qty,
                    't_log_sealing_plan_update_log_created_by' => $createdBy,
                ]);
            }
        }

        return redirect()->route('sealing.plan.index')
            ->with('success', 'Rencana sealing untuk ' . $shiftName . ' berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            't_log_sealing_plan_planned_qty' => 'required|integer|min:0',
        ]);

        $plan = SealingPlan::where('t_log_sealing_plan_id', $id)->firstOrFail();
        $oldQty = $plan->t_log_sealing_plan_planned_qty;
        $newQty = $request->t_log_sealing_plan_planned_qty;

        $plan->update([
            't_log_sealing_plan_planned_qty' => $newQty,
            't_log_sealing_plan_status' => 'planned',
        ]);

        // BUAT LOG JIKA QTY BERUBAH
        if ($oldQty !== $newQty) {
            $fg = FinishGood::where('barcode_fg', $plan->t_log_sealing_plan_barcode_fg)->first();

            SealingPlanUpdateLog::create([
                't_log_sealing_plan_update_log_date' => $plan->t_log_sealing_plan_date,
                't_log_sealing_plan_update_log_shift_name' => $plan->t_log_sealing_plan_shift_name,
                't_log_sealing_plan_update_log_barcode_fg' => $plan->t_log_sealing_plan_barcode_fg,
                't_log_sealing_plan_update_log_part_number_fg' => $fg ? $fg->part_number_fg : '',
                't_log_sealing_plan_update_log_fg_name' => $fg ? $fg->fg_name : '',
                't_log_sealing_plan_update_log_old_qty' => $oldQty,
                't_log_sealing_plan_update_log_new_qty' => $newQty,
                't_log_sealing_plan_update_log_created_by' => session('operator_name', 'Operator'),
            ]);
        }

        return redirect()->route('sealing.plan.index')
            ->with('success', 'Qty rencana berhasil diperbarui!');
    }
}