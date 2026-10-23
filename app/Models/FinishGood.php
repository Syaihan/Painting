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

    /**
     * Relasi ke semua BOM yang terkait dengan Finish Good ini.
     * Kolom foreign: m_bom.barcode_fg -> m_finish_good.barcode_fg
     */
    public function boms()
    {
        return $this->hasMany(Bom::class, 'barcode_fg', 'barcode_fg');
    }
}