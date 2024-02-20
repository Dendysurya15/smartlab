<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMsg extends Model
{
    use HasFactory;
    protected $fillable = [
        'penerima',
        'kodesample',
        'no_surat',
        'progres',
        'type',
    ];
    public $timestamps = false;

    protected $table = 'send_msg';
}
