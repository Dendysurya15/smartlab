<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesionerpertanyaan extends Model
{
    use HasFactory;
    protected $table = 'pertanyaan';
    protected $guarded = ['id'];
    public $timestamps = false;
    public function Tipe()
    {
        return $this->belongsTo(Kuesionertipe::class, 'id_tipe', 'id');
    }

    public function template_jawaban()
    {
        return $this->belongsTo(Kuesionerjawaban::class, 'id_jawaban', 'id');
    }
}
