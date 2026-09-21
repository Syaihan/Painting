<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildPart extends Model
{
    protected $table = 'm_child_part';
    protected $primaryKey = 'part_number_child_part';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'part_number_child_part',
        'material_name',
        'child_part_qty', // Sesuai kolom baru di database
    ];
}