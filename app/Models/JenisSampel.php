<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampel extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampel';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function parameterAnalisis()
    {
        return $this->hasMany(ParameterAnalisis::class, 'id_jenis_sampel', 'id');
    }
}
