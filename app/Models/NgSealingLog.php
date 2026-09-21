<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgSealingLog extends Model
{
    protected $table = 't_log_ng_sealing';
    protected $primaryKey = 't_log_ng_sealing_id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    public $timestamps = false;

    protected $fillable = [
        't_log_ng_sealing_child_part',
        't_log_ng_sealing_part_name',
        't_log_ng_sealing_qty',
        't_log_ng_sealing_ket',
        't_log_ng_sealing_operator',
        't_log_ng_sealing_grup',
        't_log_ng_sealing_timestamp',
    ];
}