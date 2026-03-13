<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_kecamatan' => '33.19.01', 'kecamatan' => 'Kaliwungu'],
            ['kode_kecamatan' => '33.19.02', 'kecamatan' => 'Kota Kudus'],
            ['kode_kecamatan' => '33.19.03', 'kecamatan' => 'Jati'],
            ['kode_kecamatan' => '33.19.04', 'kecamatan' => 'Undaan'],
            ['kode_kecamatan' => '33.19.05', 'kecamatan' => 'Mejobo'],
            ['kode_kecamatan' => '33.19.06', 'kecamatan' => 'Jekulo'],
            ['kode_kecamatan' => '33.19.07', 'kecamatan' => 'Bae'],
            ['kode_kecamatan' => '33.19.08', 'kecamatan' => 'Gebog'],
            ['kode_kecamatan' => '33.19.09', 'kecamatan' => 'Dawe'],
        ];

        Kecamatan::insert($data);
    }
}
