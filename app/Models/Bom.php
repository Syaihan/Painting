<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $table = 'm_bom';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'barcode_fg',
        'part_number_fg',
        'part_number_child_part',
        'bom_qty', // Sesuai kolom baru di database
    ];
}