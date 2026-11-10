<?php

namespace App\Http\Controllers;

use App\Models\FinishGood;
use App\Models\SealingPlan;
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

        // Ambil plan yang sudah ada untuk shift ini hari ini
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

            SealingPlan::updateOrCreate(
                [
                    't_log_sealing_plan_date' => $today,
                    't_log_sealing_plan_shift_name' => $shiftName,
                    't_log_sealing_plan_barcode_fg' => $barcodeFg,
                ],
                [
                    't_log_sealing_plan_planned_qty' => $qty,
                    't_log_sealing_plan_actual_qty' => 0,
                    't_log_sealing_plan_status' => 'planned',
                    't_log_sealing_plan_created_by' => $createdBy,
                ]
            );
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
        $plan->update([
            't_log_sealing_plan_planned_qty' => $request->t_log_sealing_plan_planned_qty,
            't_log_sealing_plan_status' => 'planned',
        ]);

        return redirect()->route('sealing.plan.index')
            ->with('success', 'Qty rencana berhasil diperbarui!');
    }
}