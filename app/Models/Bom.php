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

    /**
     * Relasi ke Finish Good (induk).
     * Kolom foreign: barcode_fg -> m_finish_good.barcode_fg
     */
    public function finishGood()
    {
        return $this->belongsTo(FinishGood::class, 'barcode_fg', 'barcode_fg');
    }

    /**
     * Relasi ke Child Part (bahan).
     * Kolom foreign: part_number_child_part -> m_child_part.part_number_child_part
     */
    public function childPart()
    {
        return $this->belongsTo(ChildPart::class, 'part_number_child_part', 'part_number_child_part');
    }
}