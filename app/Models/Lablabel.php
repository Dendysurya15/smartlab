<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lablabel extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'nomor_lab_label';

    protected $guarded = ['id'];

    public $timestamps = false;

    // public function ParameterAnalisis()
    // {
    //     return $this->belongsTo(ParameterAnalisis::class, 'id_parameter');
    // }
}
