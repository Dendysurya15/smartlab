<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartementTrack extends Model
{
    use HasFactory;
    protected $table = 'departemet_pelanggan';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function TrackSampel()
    {
        return $this->hasMany(TrackSampel::class, 'nama', 'departemen');
    }
}
