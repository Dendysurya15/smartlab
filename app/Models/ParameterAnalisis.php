<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterAnalisis extends Model
{
    use HasFactory;

    protected $table = 'parameter_analisis';

    protected $fillable = [
        'nama', // Add 'nama' attribute here if it's not already present
        'id_jenis_sampel',
    ];
    public $timestamps = false;
}
