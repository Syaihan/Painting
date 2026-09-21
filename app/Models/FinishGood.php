<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishGood extends Model
{
    protected $table = 'm_finish_good';
    protected $primaryKey = 'barcode_fg';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'barcode_fg',
        'part_number_fg',
        'fg_name',
    ];
}