<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $table = 't_log_scan';
    protected $primaryKey = 't_log_scan_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    // Karena tabel ini menggunakan kolom TIMESTAMP kustom ('t_log_scan_timestamp') 
    // dan tidak memakai created_at/updated_at bawaan Laravel:
    public $timestamps = false; 

    protected $fillable = [
        't_log_scan_fg',
        't_log_scan_fg_part_number',
        't_log_scan_qty',
        't_log_scan_operator',
        't_log_scan_pic',
        't_log_scan_grup',
        't_log_scan_timestamp',
    ];
}