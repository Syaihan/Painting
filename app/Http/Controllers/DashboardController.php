<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ChildPart;
use App\Models\ScanLog;
use App\Models\NgSealingLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function sealing()
    {
        $activeMenu = 'Dashboard Sealing';
        $currentDate = strtoupper(Carbon::now('Asia/Jakarta')->format('d F Y'));

        // Panggil fungsi global shift produksi
        $shiftData = getActiveShiftData();
        $start     = $shiftData['start_time'];
        $end       = $shiftData['end_time'];

        // Ambil data melalui method pendukung agar kode modular & bersih
        $stats         = $this->calculateStatistics($start, $end, $currentDate);
        $nonSafeStocks = $this->getNonSafeStocks();
        $history       = $this->getRecentTransactions();
        $charts        = $this->getFiscalYearCharts();
        
        return view('/dashboard/sealing', array_merge(
            compact('activeMenu', 'currentDate', 'stats', 'nonSafeStocks', 'history'),
            $charts
        ));
    }

    /**
     * Helper: Menghitung statistik utama dashboard sealing.
     */
    private function calculateStatistics($start, $end, $currentDate)
    {
        return [
            'total_part'    => ChildPart::count(),
            'safe'          => ChildPart::where('child_part_qty', '>', 499)->count(),
            'under_minimum' => ChildPart::whereBetween('child_part_qty', [300, 499])->count(),
            'investigate'   => ChildPart::where('child_part_qty', '<', 300)->count(),
            'total_scan'    => ScanLog::whereBetween('t_log_scan_timestamp', [$start, $end])->count(),
            'total_ng'      => NgSealingLog::whereBetween('t_log_ng_sealing_timestamp', [$start, $end])->sum('t_log_ng_sealing_qty'),
            'date'          => $currentDate
        ];
    }

    /**
     * Helper: Mengambil daftar stok yang berada di bawah status aman (< 500).
     */
    private function getNonSafeStocks()
    {
        return ChildPart::where('child_part_qty', '<', 500)
            ->orderBy('child_part_qty', 'asc')
            ->get()
            ->map(fn($item) => [
                'part_number' => $item->part_number_child_part,
                'part_name'   => $item->material_name,
                'balance'     => $item->child_part_qty,
                'status'      => $item->child_part_qty < 300 ? 'Investigate' : 'Under Minimum'
            ]);
    }

    /**
     * Helper: Mengambil riwayat transaksi scan terakhir.
     */
    private function getRecentTransactions()
    {
        return ScanLog::select(
                't_log_scan_timestamp as jam',
                't_log_scan_fg as fq',
                't_log_scan_fg_part_number as part_number',
                't_log_scan_qty as qty',
                't_log_scan_operator as operator',
                't_log_scan_pic as pic',
                't_log_scan_grup as grup'
            )
            ->orderBy('t_log_scan_timestamp', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Helper: Mengambil data chart berdasarkan tahun fiskal (April s.d. Maret).
     */
    private function getFiscalYearCharts()
    {
        $now = Carbon::now('Asia/Jakarta');
        $fiscalYearStart = $now->month >= 4 ? $now->year : $now->year - 1;
        
        $dateStart = Carbon::create($fiscalYearStart, 4, 1, 0, 0, 0, 'Asia/Jakarta'); 
        $dateEnd   = Carbon::create($fiscalYearStart + 1, 3, 31, 23, 59, 59, 'Asia/Jakarta'); 

        $chartCategoryNg = NgSealingLog::select('t_log_ng_sealing_ket as category', DB::raw('COUNT(t_log_ng_sealing_qty) as total'))
            ->whereBetween('t_log_ng_sealing_timestamp', [$dateStart, $dateEnd])
            ->groupBy('t_log_ng_sealing_ket')
            ->pluck('total', 'category');

        $chartNgPart = NgSealingLog::select('t_log_ng_sealing_part_name as part_name', DB::raw('SUM(t_log_ng_sealing_qty) as total'))
            ->whereBetween('t_log_ng_sealing_timestamp', [$dateStart, $dateEnd])
            ->groupBy('t_log_ng_sealing_part_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->pluck('total', 'part_name');

        return compact('chartCategoryNg', 'chartNgPart');
    }
}