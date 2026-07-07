<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [

        'periode',

        'dana_keluar',

        'dokumen',

        'keterangan',

        'status',

        'published_at',

    ];
}