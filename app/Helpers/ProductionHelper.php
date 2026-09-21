<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('getActiveShiftData')) {
    function getActiveShiftData()
    {
        $now = Carbon::now('Asia/Jakarta');
        $currentTime = $now->format('H:i:s');
        $dayOfWeek = $now->dayOfWeek; // 5 = Jumat

        $activeShiftRecord = null;
        $startTime = null;
        $endTime = null;

        // Ambil semua data shift dari tabel m_jam_produksi
        $shifts = DB::table('m_jam_produksi')->get();

        foreach ($shifts as $shift) {
            // Cek Shift 2 yang lintas hari
            if ($shift->m_jam_produksi_lintas_hari == 1) {
                if ($currentTime >= $shift->m_jam_produksi_jam_mulai || $currentTime <= $shift->m_jam_produksi_jam_selesai) {
                    $activeShiftRecord = $shift;
                    break;
                }
            } 
            // Cek Shift 1 (Normal / Jumat)
            else {
                if ($dayOfWeek === Carbon::FRIDAY && $shift->m_jam_produksi_ket == 'Jumat') {
                    if ($currentTime >= $shift->m_jam_produksi_jam_mulai && $currentTime <= $shift->m_jam_produksi_jam_selesai) {
                        $activeShiftRecord = $shift;
                        break;
                    }
                } elseif ($dayOfWeek !== Carbon::FRIDAY && $shift->m_jam_produksi_ket == 'Senin - Kamis') {
                    if ($currentTime >= $shift->m_jam_produksi_jam_mulai && $currentTime <= $shift->m_jam_produksi_jam_selesai) {
                        $activeShiftRecord = $shift;
                        break;
                    }
                }
            }
        }

        // Tentukan rentang waktu (start & end) berdasarkan shift aktif
        if ($activeShiftRecord) {
            $activeShiftName = $activeShiftRecord->m_jam_produksi_shift;
            
            if ($activeShiftRecord->m_jam_produksi_lintas_hari == 1) {
                if ($currentTime >= $activeShiftRecord->m_jam_produksi_jam_mulai) {
                    $startTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_mulai);
                    $endTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_selesai)->addDay();
                } else {
                    $startTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_mulai)->subDay();
                    $endTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_selesai);
                }
            } else {
                $startTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_mulai);
                $endTime = Carbon::parse($now->toDateString() . ' ' . $activeShiftRecord->m_jam_produksi_jam_selesai);
            }
        } else {
            $activeShiftName = "Diluar Jam Produksi";
            $startTime = Carbon::today();
            $endTime = Carbon::now();
        }

        return [
            'shift_name' => $activeShiftName,
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ];
    }
}