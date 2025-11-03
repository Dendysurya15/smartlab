<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesionerjawaban extends Model
{
    use HasFactory;
    protected $table = 'jawaban';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function Tipe()
    {
        return $this->belongsTo(Kuesionertipe::class, 'tipe', 'id');
    }
}
