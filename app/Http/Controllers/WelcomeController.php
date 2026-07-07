<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $totalDonatur = Donatur::count();

        $totalDonasi = Donasi::sum('nominal');

        $donasiPerProgram = Donasi::select(
            'program_sedekah.id',
            'program_sedekah.nama_program',
            DB::raw('SUM(donasi.nominal) as total_donasi')
        )
            ->join('program_sedekah', 'program_sedekah.id', '=', 'donasi.program_id')
            ->groupBy('program_sedekah.id', 'program_sedekah.nama_program')
            ->orderBy('program_sedekah.nama_program')
            ->get();

        $driver = DB::connection()->getDriverName();
        // Grafik 12 bulan
        $monthExpression = match ($driver) {
            'pgsql' => 'EXTRACT(MONTH FROM tanggal_donasi)',
            'mysql' => 'MONTH(tanggal_donasi)',
            default => 'MONTH(tanggal_donasi)',
        };

        $chart = Donasi::selectRaw("
                {$monthExpression} AS bulan,
                SUM(nominal) AS total
            ")
            ->whereYear('tanggal_donasi', now()->year)
            ->groupByRaw($monthExpression)
            ->orderByRaw($monthExpression)
            ->pluck('total', 'bulan');

        $labels = [];
        $series = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $series[] = $chart[$i] ?? 0;
        }

        return view('welcome', compact(
            'totalDonatur',
            'totalDonasi',
            'donasiPerProgram',
            'labels',
            'series',
        ));

    }
}
