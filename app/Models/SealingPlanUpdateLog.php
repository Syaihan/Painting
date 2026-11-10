<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SealingPlanUpdateLog extends Model
{
    protected $table = 't_log_sealing_plan_update_log';
    protected $primaryKey = 't_log_sealing_plan_update_log_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    const CREATED_AT = 't_log_sealing_plan_update_log_created_at';

    protected $fillable = [
        't_log_sealing_plan_update_log_date',
        't_log_sealing_plan_update_log_shift_name',
        't_log_sealing_plan_update_log_barcode_fg',
        't_log_sealing_plan_update_log_part_number_fg',
        't_log_sealing_plan_update_log_fg_name',
        't_log_sealing_plan_update_log_old_qty',
        't_log_sealing_plan_update_log_new_qty',
        't_log_sealing_plan_update_log_created_by',
    ];

    protected $casts = [
        't_log_sealing_plan_update_log_date' => 'date',
        't_log_sealing_plan_update_log_old_qty' => 'integer',
        't_log_sealing_plan_update_log_new_qty' => 'integer',
    ];

    public function finishGood()
    {
        return $this->belongsTo(FinishGood::class, 't_log_sealing_plan_update_log_barcode_fg', 'barcode_fg');
    }
}