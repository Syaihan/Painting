<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SealingSetting extends Model
{
    protected $table = 'm_setting_sealing';

    protected $fillable = [
        'min_limit',
        'max_limit',
        'cutting_qty',
    ];

    protected $casts = [
        'min_limit'   => 'integer',
        'max_limit'   => 'integer',
        'cutting_qty' => 'integer',
    ];

    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'min_limit'   => 300,
            'max_limit'   => 500,
            'cutting_qty' => 100,
        ]);
    }
}