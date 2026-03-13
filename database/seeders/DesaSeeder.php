<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use App\Models\Kecamatan;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $desa = [
            // 33.19.01 - Kaliwungu
            ['kode_desa' => '33.19.01.0001', 'desa' => 'Bakalankrapyak', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0002', 'desa' => 'Prambatan Kidul', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0003', 'desa' => 'Prambatan Lor', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0004', 'desa' => 'Garung Kidul', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0005', 'desa' => 'Setrokalangan', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0006', 'desa' => 'Banget', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0007', 'desa' => 'Blimbing Kidul', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0008', 'desa' => 'Sidorejo', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0009', 'desa' => 'Gamong', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0010', 'desa' => 'Kedungdowo', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0011', 'desa' => 'Garung Lor', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0012', 'desa' => 'Karangampel', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0013', 'desa' => 'Mlajen', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0014', 'desa' => 'Kaliwungu', 'kecamatan_kode' => '33.19.01'],
            ['kode_desa' => '33.19.01.0015', 'desa' => 'Papringan', 'kecamatan_kode' => '33.19.01'],

            // 33.19.02 - Kota Kudus
            ['kode_desa' => '33.19.02.0001', 'desa' => 'Purwosari', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0002', 'desa' => 'Janggalan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0003', 'desa' => 'Demangan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0004', 'desa' => 'Sunggingan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0005', 'desa' => 'Panjunan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0006', 'desa' => 'Wergu Wetan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0007', 'desa' => 'Wergu Kulon', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0008', 'desa' => 'Mlati Kidul', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0009', 'desa' => 'Mlati Norowito', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0010', 'desa' => 'Nganguk', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0011', 'desa' => 'Kramat', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0012', 'desa' => 'Demaan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0013', 'desa' => 'Langgardalem', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0014', 'desa' => 'Kauman', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0015', 'desa' => 'Damaran', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0016', 'desa' => 'Kerjasan', 'kecamatan_kode' => '33.19.02'],
            ['kode_desa' => '33.19.02.0017', 'desa' => 'Kajeksan', 'kecamatan_kode' => '33.19.02'],

            // 33.19.03 - Jati
            ['kode_desa' => '33.19.03.0001', 'desa' => 'Jetiskapuan', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0002', 'desa' => 'Tanjungkarang', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0003', 'desa' => 'Jati Wetan', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0004', 'desa' => 'Pasuruhan Kidul', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0005', 'desa' => 'Pasuruhan Lor', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0006', 'desa' => 'Ploso', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0007', 'desa' => 'Jati Kulon', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0008', 'desa' => 'Getaspejaten', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0009', 'desa' => 'Loram Kulon', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0010', 'desa' => 'Loram Wetan', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0011', 'desa' => 'Jepangpakis', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0012', 'desa' => 'Megawon', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0013', 'desa' => 'Ngembal Kulon', 'kecamatan_kode' => '33.19.03'],
            ['kode_desa' => '33.19.03.0014', 'desa' => 'Tumpangkrasak', 'kecamatan_kode' => '33.19.03'],

            // 33.19.04 - Undaan
            ['kode_desa' => '33.19.04.0001', 'desa' => 'Wonosoco', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0002', 'desa' => 'Lambangan', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0003', 'desa' => 'Kalirejo', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0004', 'desa' => 'Medini', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0005', 'desa' => 'Sambung', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0006', 'desa' => 'Glagahwaru', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0007', 'desa' => 'Kutuk', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0008', 'desa' => 'Undaan Kidul', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0009', 'desa' => 'Undaan Tengah', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0010', 'desa' => 'Karangrowo', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0011', 'desa' => 'Larikrejo', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0012', 'desa' => 'Undaan Lor', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0013', 'desa' => 'Wates', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0014', 'desa' => 'Ngemplak', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0015', 'desa' => 'Terangmas', 'kecamatan_kode' => '33.19.04'],
            ['kode_desa' => '33.19.04.0016', 'desa' => 'Berugenjang', 'kecamatan_kode' => '33.19.04'],

            // 33.19.05 - Mejobo
            ['kode_desa' => '33.19.05.0001', 'desa' => 'Golantepus', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0002', 'desa' => 'Tenggeles', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0003', 'desa' => 'Gulang', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0004', 'desa' => 'Jepang', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0005', 'desa' => 'Payaman', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0006', 'desa' => 'Kirig', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0007', 'desa' => 'Temulus', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0008', 'desa' => 'Kesambi', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0009', 'desa' => 'Jojo', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0010', 'desa' => 'Hadiwarno', 'kecamatan_kode' => '33.19.05'],
            ['kode_desa' => '33.19.05.0011', 'desa' => 'Mejobo', 'kecamatan_kode' => '33.19.05'],

            // 33.19.06 - Jekulo
            ['kode_desa' => '33.19.06.0001', 'desa' => 'Sadang', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0002', 'desa' => 'Bulungcangkring', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0003', 'desa' => 'Bulung Kulon', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0004', 'desa' => 'Sidomulyo', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0005', 'desa' => 'Gondoharum', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0006', 'desa' => 'Terban', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0007', 'desa' => 'Pladen', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0008', 'desa' => 'Klaling', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0009', 'desa' => 'Jekulo', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0010', 'desa' => 'Hadipolo', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0011', 'desa' => 'Honggosoco', 'kecamatan_kode' => '33.19.06'],
            ['kode_desa' => '33.19.06.0012', 'desa' => 'Tanjungrejo', 'kecamatan_kode' => '33.19.06'],

            // 33.19.07 - Bae
            ['kode_desa' => '33.19.07.0001', 'desa' => 'Dersalam', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0002', 'desa' => 'Ngembalrejo', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0003', 'desa' => 'Karangbener', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0004', 'desa' => 'Gondangmanis', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0005', 'desa' => 'Pedawang', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0006', 'desa' => 'Bacin', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0007', 'desa' => 'Panjang', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0008', 'desa' => 'Peganjaran', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0009', 'desa' => 'Purworejo', 'kecamatan_kode' => '33.19.07'],
            ['kode_desa' => '33.19.07.0010', 'desa' => 'Bae', 'kecamatan_kode' => '33.19.07'],

            // 33.19.08 - Gebog
            ['kode_desa' => '33.19.08.0001', 'desa' => 'Gribig', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0002', 'desa' => 'Klumpit', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0003', 'desa' => 'Getasrabi', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0004', 'desa' => 'Padurenan', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0005', 'desa' => 'Karangmalang', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0006', 'desa' => 'Besito', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0007', 'desa' => 'Jurang', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0008', 'desa' => 'Gondosari', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0009', 'desa' => 'Kedungsari', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0010', 'desa' => 'Menawan', 'kecamatan_kode' => '33.19.08'],
            ['kode_desa' => '33.19.08.0011', 'desa' => 'Rahtawu', 'kecamatan_kode' => '33.19.08'],

            // 33.19.09 - Dawe
            ['kode_desa' => '33.19.09.0001', 'desa' => 'Samirejo', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0002', 'desa' => 'Cendono', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0003', 'desa' => 'Margorejo', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0004', 'desa' => 'Rejosari', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0005', 'desa' => 'Kandanganmas', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0006', 'desa' => 'Glagah Kulon', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0007', 'desa' => 'Tergo', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0008', 'desa' => 'Cranggang', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0009', 'desa' => 'Lau', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0010', 'desa' => 'Piji', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0011', 'desa' => 'Puyoh', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0012', 'desa' => 'Soco', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0013', 'desa' => 'Temadi', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0014', 'desa' => 'Kajjar', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0015', 'desa' => 'Kuwukan', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0016', 'desa' => 'Dukuhwaru', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0017', 'desa' => 'Japan', 'kecamatan_kode' => '33.19.09'],
            ['kode_desa' => '33.19.09.0018', 'desa' => 'Colo', 'kecamatan_kode' => '33.19.09'],

        ];

        foreach ($desa as $d) {
            $kecamatan = Kecamatan::where('kode_kecamatan', $d['kecamatan_kode'])->first();
            Desa::create([
                'kecamatan_id' => $kecamatan->id,
                'kode_desa' => $d['kode_desa'],
                'desa' => $d['desa'],
            ]);
        }
    }
}
