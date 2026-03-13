<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';
    protected $fillable = ['kode_kecamatan', 'kecamatan'];

    public function desa()
    {
        return $this->hasMany(Desa::class, 'kecamatan_id');
    }
}
