<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelManagement extends Model
{
    use HasFactory;

    protected $table = 'petugas';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jabatan',
        'status',
    ];
}
