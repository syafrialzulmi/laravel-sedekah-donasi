<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'icon',
        'route',
        'parent_id',
        'order',
        'permission_name',
        'icon_image',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->with('children')
            ->orderBy('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->whereNull('permission_name')
                ->orWhereIn('permission_name', $user->getAllPermissions()->pluck('name'));
        });
    }

    public function permissions()
    {
        return $this->hasMany(\App\Models\Permission::class, 'menu_id');
    }

    public function link(): ?string
    {
        if ($this->route) return route($this->route);
        return $this->url ? url($this->url) : null;
    }

    // relasi rekursif (eager load anak-cucu)
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
