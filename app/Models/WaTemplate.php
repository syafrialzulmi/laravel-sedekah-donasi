<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    protected $table = 'wa_templates';

    protected $fillable = [
        'kode',
        'nama_template',
        'isi',
        'variables',
        'aktif',
    ];

    protected $casts = [
        'variables' => 'array',
        'aktif' => 'boolean',
    ];
}
