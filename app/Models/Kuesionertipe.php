<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesionertipe extends Model
{
    use HasFactory;
    protected $table = 'tipe_pertanyaan_kuesioner';
    protected $guarded = ['id'];
    public $timestamps = false;
}
