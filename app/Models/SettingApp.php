<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SettingApp extends Model
{
    protected $table = 'setting_apps';

    protected $fillable = [
        'name_app', 'deskripsi', 'logo', 'banner', 'favicon', 'name_app_singkatan',
    ];

    // Helper URL untuk preview di Blade
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }
    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? Storage::url($this->banner) : null;
    }
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? Storage::url($this->favicon) : null;
    }
}
