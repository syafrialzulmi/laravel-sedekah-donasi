<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use Illuminate\Support\Facades\DB;

class DasborController extends Controller
{
    public function index()
    {
        $totalDonatur = Donatur::count();

        $totalDonasi = Donasi::sum('nominal');

        $totalDonasiInuk = Donasi::whereHas('program', function ($q) {
            $q->where('nama_program', 'inuk');
        })->sum('nominal');

        $totalDonasiMandiri = Donasi::whereHas('program', function ($q) {
            $q->where('nama_program', 'mandiri');
        })->sum('nominal');

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

        // Donasi terbaru
        $latestDonasi = Donasi::with(['donatur', 'program'])
            ->latest()
            ->take(8)
            ->get();

        return view('pages.admin.dasbor', compact(
            'totalDonatur',
            'totalDonasi',
            'totalDonasiInuk',
            'totalDonasiMandiri',
            'labels',
            'series',
            'latestDonasi'
        ));

    }
}
