<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterAnalisis extends Model
{
    use HasFactory;

    protected $table = 'parameter_analisis';

    protected $fillable = [
        'nama_parameter',
        'nama_unsur',
        'bahan_produk',
        'metode_analisis',
        'harga',
        'satuan',
        'id_jenis_sampel',
    ];
    public $timestamps = false;

    // ParameterAnalisis model
    public function jenisSampel()
    {
        return $this->belongsTo(JenisSampel::class, 'id_jenis_sampel');
    }
}
