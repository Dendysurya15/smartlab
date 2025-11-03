<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layoutkue extends Model
{
    use HasFactory;
    protected $table = 'layout';
    protected $guarded = ['id'];
    public $timestamps = false;
}
