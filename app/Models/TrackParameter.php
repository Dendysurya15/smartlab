<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackParameter extends Model
{
    use HasFactory;
    protected $table = 'track_parameter';

    public $timestamps = false;

    public function ParameterAnalisis()
    {
        return $this->belongsTo(ParameterAnalisis::class, 'id_parameter', 'id');
    }
}
