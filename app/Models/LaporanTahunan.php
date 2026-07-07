<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanTahunan extends Model
{
    protected $fillable = [

        'periode',

        'dokumen',

        'keterangan',

        'status',

        'published_at',

    ];
}