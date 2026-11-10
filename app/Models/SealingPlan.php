<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SealingPlan extends Model
{
    protected $table = 't_log_sealing_plan';
    protected $primaryKey = 't_log_sealing_plan_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    const CREATED_AT = 't_log_sealing_plan_created_at';
    const UPDATED_AT = 't_log_sealing_plan_updated_at';

    protected $fillable = [
        't_log_sealing_plan_date',
        't_log_sealing_plan_shift_name',
        't_log_sealing_plan_barcode_fg',
        't_log_sealing_plan_planned_qty',
        't_log_sealing_plan_actual_qty',
        't_log_sealing_plan_status',
        't_log_sealing_plan_created_by',
    ];

    protected $casts = [
        't_log_sealing_plan_date' => 'date',
        't_log_sealing_plan_planned_qty' => 'integer',
        't_log_sealing_plan_actual_qty' => 'integer',
    ];

    public function finishGood()
    {
        return $this->belongsTo(FinishGood::class, 't_log_sealing_plan_barcode_fg', 'barcode_fg');
    }
}