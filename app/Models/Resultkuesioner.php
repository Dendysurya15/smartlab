<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultkuesioner extends Model
{
    use HasFactory;
    protected $table = 'table_result_kuesioner';
    protected $guarded = ['id'];
    public $timestamps = false;
}
