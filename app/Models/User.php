<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Pengajuan;
use App\Models\Donasi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'kota',
    'foto',
    'role',
    'status_donatur',
    'last_donation_at',
];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_donation_at' => 'datetime',
    ];
}

    public function pengajuan()
    {
        return $this->hasOne(Pengajuan::class);
    }

    public function donasis()
    {
        return $this->hasMany(Donasi::class);
    }
    public function laporans()
{
    return $this->hasMany(Laporan::class, 'user_id');
}

public function laporanTahunans()
{
    return $this->hasMany(LaporanTahunan::class, 'user_id');
}

public function kegiatans()
{
    return $this->hasMany(Kegiatan::class, 'user_id');
}
}