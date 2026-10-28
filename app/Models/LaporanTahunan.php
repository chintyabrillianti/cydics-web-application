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

    public function admin()
{
    return $this->belongsTo(User::class, 'user_id');
}
}