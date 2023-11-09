<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackSampel extends Model
{
    use HasFactory;
    protected $table = 'track_sampel';
    public $timestamps = false;
    protected $fillable = [
        'tanggal_penerimaan',
        'jenis_sampel',
        'asal_sampel',
        'nomor_kupa',
        'nama_pengirim',
        'departemen',
        'kode_sampel',
        'jumlah_sampel',
        'kondisi_sampel',
        'personel',
        'alat',
        'bahan',
        'kemasan_sampel',
        'nomor_surat',
        'nomor_lab',
        'estimasi',
        'tujuan',
        'parameter_analisis',
        'progress',
        'last_update',
        'admin',
        'no_hp',
        'email',
        'foto_sampel',
        'kode',
        'skala_prioritas'
    ];

    // Define the "belongs to" relationship with JenisSample
    public function jenisSampel()
    {
        return $this->belongsTo(JenisSampel::class, 'jenis_sampel', 'id');
    }

    public function progressSampel()
    {
        return $this->belongsTo(ProgressPengerjaan::class, 'progress');
    }

    public function trackParameters()
    {
        return $this->hasMany(TrackParameter::class, 'id_tracksampel', 'parameter_analisisid');
    }
}
