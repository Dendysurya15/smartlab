<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackSampel extends Model
{
    use HasFactory;
    protected $table = 'track_sample';
    public $timestamps = false;
    protected $fillable = [
        'tanggal_penerimaan',
        'jenis_sample',
        'asal_sampel',
        'nomor_kupa',
        'nama_pengirim',
        'departemen',
        'kode_sample',
        'nomor_surat',
        'estimasi',
        'tujuan',
        'parameter_analisis',
        'progress',
        'last_update',
        'admin',
        'no_hp',
        'email',
        'foto_sample',
    ];
}
