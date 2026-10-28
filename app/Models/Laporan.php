<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
    'user_id',

    'periode',

    'saldo_awal',
    'dana_masuk',
    'dana_keluar',
    'saldo_akhir',

    'dana_yatim',
    'bingkisan_yatim',

    'dana_piatu',
    'bingkisan_piatu',

    'dana_yatim_piatu',
    'bingkisan_yatim_piatu',

    'dana_duafa_anak',
    'bingkisan_duafa_anak',

    'dana_duafa_lansia',
    'bingkisan_duafa_lansia',

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