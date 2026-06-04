<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';

    protected $fillable = [
        'donatur_id',
        'program_id',
        'nominal',
        'bulan',
        'tahun',
        'tanggal_donasi',
        'keterangan',
        'wa_terkirim',
        'wa_terkirim_at',
        'user_id',
    ];

    protected $casts = [
        'nominal'         => 'decimal:2',
        'tanggal_donasi'  => 'date',
        'wa_terkirim'     => 'boolean',
        'wa_terkirim_at'  => 'datetime',
    ];

    /**
     * Relasi ke Donatur
     */
    public function donatur()
    {
        return $this->belongsTo(Donatur::class);
    }

    /**
     * Relasi ke Program Sedekah
     */
    public function program()
    {
        return $this->belongsTo(ProgramSedekah::class, 'program_id');
    }

    /**
     * Relasi ke User Input
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama bulan Indonesia
     */
    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $bulan[$this->bulan] ?? '-';
    }

    /**
     * Format periode
     * Contoh: Januari 2025
     */
    public function getPeriodeAttribute()
    {
        return $this->nama_bulan . ' ' . $this->tahun;
    }

    /**
     * Format nominal rupiah
     */
    public function getNominalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}
