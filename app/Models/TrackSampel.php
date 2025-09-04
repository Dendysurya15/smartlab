<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackSampel extends Model
{
    use HasFactory;

    protected $table = 'track_sampel';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $casts = [
        // 'foto_sampel' => 'array', // Removed - data is stored as string separated by %
    ];

    public function jenisSampel()
    {
        return $this->belongsTo(JenisSampel::class, 'jenis_sampel', 'id');
    }

    public function progressSampel()
    {
        return $this->belongsTo(ProgressPengerjaan::class, 'progress');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin', 'id');
    }

    public function trackParameters()
    {
        return $this->hasMany(TrackParameter::class, 'id_tracksampel', 'parameter_analisisid');
    }
    public function Invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function DepartementTrack()
    {
        return $this->belongsTo(DepartementTrack::class, 'departemen', 'nama');
    }
}
