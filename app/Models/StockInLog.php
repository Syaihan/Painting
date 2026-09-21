<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInLog extends Model
{
    protected $table = 't_log_stock_in';
    protected $primaryKey = 't_log_stock_in_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = false;

    protected $fillable = [
        't_log_stock_in_child_part',
        't_log_stock_in_part_name',
        't_log_stock_in_qty',
        't_log_stock_in_pic',
        't_log_stock_in_grup',
        't_log_stock_in_delivery',
        't_log_stock_in_timestamp',
    ];
}