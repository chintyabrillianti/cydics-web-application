<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'banner',
        'visi',
        'misi',
        'telepon',
        'email',
        'alamat',
        'instagram',
        'youtube'
    ];
}