<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMsg extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_surat', // Add 'nama' attribute here if it's not already present
        'penerima',
        'progress',
        'kodesample',
    ];
    public $timestamps = false;

    protected $table = 'send_msg';
}
