<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    protected $fillable = [
        'user_id',
        'program_id',      // <-- TAMBAHKAN
        'nominal',
        'bukti_transfer',
        'catatan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}