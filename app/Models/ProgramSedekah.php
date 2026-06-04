<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSedekah extends Model
{
    protected $table = 'program_sedekah';

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'jenis_target',
        'target_dana',
        'status',
    ];

    protected $casts = [
        'target_dana' => 'decimal:2',
    ];

    public function donasi()
    {
        return $this->hasMany(Donasi::class, 'program_id');
    }

    /**
     * Scope program yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope program yang draft
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Cek apakah program sukarela
     */
    public function isSukarela()
    {
        return $this->jenis_target === 'sukarela';
    }

    /**
     * Cek apakah program menggunakan target
     */
    public function isTarget()
    {
        return $this->jenis_target === 'target';
    }

    /**
     * Format target dana
     */
    public function getTargetDanaFormatAttribute()
    {
        return number_format($this->target_dana ?? 0, 0, ',', '.');
    }

    /**
     * Label status
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'ditutup' => 'Ditutup',
            default => '-',
        };
    }
}
