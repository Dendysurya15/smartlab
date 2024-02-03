<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeAnalisis extends Model
{
    use HasFactory;

    protected $table = 'metode_analisis';

    protected $fillable = [
        'nama', // Add 'nama' attribute here if it's not already present
        'harga',
        'satuan',
        'id_parameter',
    ];
    public $timestamps = false;

    public function parameterAnalisis()
    {
        return $this->belongsTo(ParameterAnalisis::class, 'id_parameter', 'id');
    }
}
