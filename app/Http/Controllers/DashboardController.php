<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ChildPart;
use App\Models\ScanLog;
use App\Models\NgSealingLog;
use App\Models\SealingSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
        $minLimit = SealingSetting::getSettings()->min_limit;
        $cacheKey = 'dashboard_stats:' . $start . ':' . $end . ':' . $currentDate . ':' . $minLimit;

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($start, $end, $currentDate, $minLimit) {

            return [
                'total_part'    => ChildPart::count(),
                'safe'          => ChildPart::where('child_part_qty', '>', $minLimit)->count(),
                'under_minimum' => ChildPart::where('child_part_qty', '<', $minLimit)->count(),
                'total_scan'    => ScanLog::whereBetween('t_log_scan_timestamp', [$start, $end])->count(),
                'total_ng'      => NgSealingLog::whereBetween('t_log_ng_sealing_timestamp', [$start, $end])->sum('t_log_ng_sealing_qty'),
                'date'          => $currentDate
            ];
        });
    }

    /**
     * Helper: Mengambil daftar stok yang berada di bawah status aman (< 500).
     */
    private function getNonSafeStocks()
    {
        $minLimit = SealingSetting::getSettings()->min_limit;
        $cacheKey = 'dashboard_non_safe_stocks:' . $minLimit;

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($minLimit) {
            return ChildPart::where('child_part_qty', '<', $minLimit)
                ->orderBy('child_part_qty', 'asc')
                ->get()
                ->map(fn($item) => [
                    'part_number' => $item->part_number_child_part,
                    'part_name'   => $item->material_name,
                    'balance'     => $item->child_part_qty,
                    'status'      => 'Under Minimum'
                ])
                ->toArray();
        });
    }

    /**
     * Helper: Mengambil riwayat transaksi scan terakhir.
     */
    private function getRecentTransactions()
    {
        $cacheKey = 'dashboard_recent_transactions';

        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
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
                ->get()
                ->toArray();
        });
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

        $cacheKey = 'dashboard_fiscal_charts:' . $fiscalYearStart;

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($dateStart, $dateEnd) {
            $chartCategoryNg = NgSealingLog::select('t_log_ng_sealing_ket as category', DB::raw('COUNT(t_log_ng_sealing_qty) as total'))
                ->whereBetween('t_log_ng_sealing_timestamp', [$dateStart, $dateEnd])
                ->groupBy('t_log_ng_sealing_ket')
                ->pluck('total', 'category')
                ->toArray();

            $chartNgPart = NgSealingLog::select('t_log_ng_sealing_part_name as part_name', DB::raw('SUM(t_log_ng_sealing_qty) as total'))
                ->whereBetween('t_log_ng_sealing_timestamp', [$dateStart, $dateEnd])
                ->groupBy('t_log_ng_sealing_part_name')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->pluck('total', 'part_name')
                ->toArray();

            return compact('chartCategoryNg', 'chartNgPart');
        });
    }

    public function sealingData()
    {
        // Ambil data periode shift produksi yang sama
        $shiftData = getActiveShiftData();
        $start     = $shiftData['start_time'];
        $end       = $shiftData['end_time'];
        $currentDate = strtoupper(Carbon::now('Asia/Jakarta')->format('d F Y'));

        $response = response()->json(array_merge([
            'stats'         => $this->calculateStatistics($start, $end, $currentDate),
            'nonSafeStocks' => $this->getNonSafeStocks(),
            'history'       => $this->getRecentTransactions(),
        ], $this->getFiscalYearCharts()));

        // Disable semua cache browser agar selalu mengambil data terbaru
        return $response->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', '0');
    }

    /**
     * Clear semua cache dashboard agar data otomatis update setelah transaksi
     * (ubah qty child_part, scan, NG, stock in, adjustment, dll).
     */
    public function clearDashboardCache()
    {
        $now = Carbon::now('Asia/Jakarta');
        $fiscalYearStart = $now->month >= 4 ? $now->year : $now->year - 1;

        // Clear chart cache
        Cache::forget('dashboard_fiscal_charts:' . $fiscalYearStart);

        // Clear non-safe stocks dan recent transactions
        Cache::forget('dashboard_non_safe_stocks');
        Cache::forget('dashboard_recent_transactions');

        // Clear stats cache (format kunci sama dengan calculateStatistics())
        $shiftData = getActiveShiftData();
        $start = $shiftData['start_time'];
        $end   = $shiftData['end_time'];
        $currentDate = strtoupper($now->format('d F Y'));
        Cache::forget('dashboard_stats:' . $start . ':' . $end . ':' . $currentDate);
    }
}