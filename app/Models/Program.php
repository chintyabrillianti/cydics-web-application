<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Program extends Model
{
    protected $fillable = [
    'nama',
    'deskripsi',
    'foto',
    'status',
];
}
