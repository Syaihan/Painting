<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    // Menyesuaikan dengan nama tabel di database Anda
    protected $table = 'm_operator';

    // Karena tabel m_operator tidak memiliki kolom created_at & updated_at
    public $timestamps = false;

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'nama',
        'grup'
    ];
}