<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Kegiatan extends Model
{
    protected $fillable = [
    'user_id',
    'judul',
    'deskripsi',
    'foto',
    'tanggal',
];
    public function admin()
{
    return $this->belongsTo(User::class, 'user_id');
}
}