<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SettingApp extends Model
{
    protected $table = 'setting_apps';

    protected $fillable = [
        'name_app', 'deskripsi', 'logo', 'banner', 'favicon', 'name_app_singkatan',
        'desa_id', 'kecamatan_id',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
