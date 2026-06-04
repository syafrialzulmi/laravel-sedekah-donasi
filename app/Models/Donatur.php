<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    protected $table = 'donatur';

    protected $fillable = [
        'nomor_kode',
        'nama',
        'no_hp',
        'email',
        'alamat',
        'dukuh',
        'gang',
        'desa_id',
        'kecamatan_id',
        'status',
    ];

    /**
     * Relasi ke Desa
     */
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    /**
     * Relasi ke Kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function donasi()
    {
        return $this->hasMany(Donasi::class);
    }

    /**
     * Scope donatur aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope donatur nonaktif
     */
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }

    /**
     * Accessor alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        return collect([
            $this->alamat,
            $this->dukuh ? 'Dukuh ' . $this->dukuh : null,
            $this->gang ? 'Gang ' . $this->gang : null,
            $this->desa?->desa,
            $this->kecamatan?->kecamatan,
        ])->filter()->implode(', ');
    }

    /**
     * Accessor status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            default => '-',
        };
    }
}