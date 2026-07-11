<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\ProgramSedekah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanDonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan-donasi-list|laporan-donasi-create|laporan-donasi-edit|laporan-donasi-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:laporan-donasi-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:laporan-donasi-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:laporan-donasi-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $programs = ProgramSedekah::orderBy('nama_program')
            ->get();
        $years = Donasi::select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        // default program_id = 1
        $programId = $request->input('program_id', 1);
        $tahun = $request->tahun ?? date('Y');
        $gang = $request->input('gang');

        $program = ProgramSedekah::find($programId);

        // UL-0001 -> Inuk UL , ULM-0001 -> Mandiri ULM
        $donaturList = Donatur::query()
            ->when($gang, function ($query) use ($gang) {
                $query->where('gang', $gang);
            })
            ->when($program, function ($query) use ($program) {
                $query->where('nomor_kode', 'like', $program->kode . '-%');
            })
            ->orderBy('gang')
            ->orderBy('nomor_kode')
            ->get();

        $donasi = Donasi::selectRaw('
                donatur_id,
                bulan,
                SUM(nominal) as total_nominal
            ')
            ->where('tahun', $tahun)
            ->where('program_id', $programId)
            ->groupBy('donatur_id', 'bulan')
            ->get();

        $pivot = [];

        foreach ($donasi as $item) {
            $pivot[$item->donatur_id][$item->bulan] = $item->total_nominal;
        }

        $rekapBulanan = Donasi::query()
            ->selectRaw("
                bulan,
                CASE
                    WHEN donatur.gang = 12 AND donatur.alamat LIKE '%12A%' THEN '12A'
                    WHEN donatur.gang = 12 AND donatur.alamat LIKE '%12B%' THEN '12B'
                    ELSE CAST(donatur.gang AS CHAR)
                END AS gang_group,
                SUM(donasi.nominal) AS total
            ")
            ->join('donatur', 'donatur.id', '=', 'donasi.donatur_id')
            ->where('donasi.tahun', $tahun)
            ->where('donasi.program_id', $programId)
            ->groupBy('bulan', 'gang_group')
            ->orderBy('bulan')
            ->get();

        $rekap = [];

        foreach ($rekapBulanan as $item) {
            $rekap[$item->bulan][$item->gang_group] = $item->total;
        }

        return view('pages.admin.laporan_donasi.index', [
            'tahun' => $tahun,
            'programId' => $programId,
            'gang' => $gang,
            'donaturList' => $donaturList,
            'pivot' => $pivot,
            'programs' => $programs,
            'years' => $years,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function print(Request $request)
    {
        $programs = ProgramSedekah::orderBy('nama_program')->get();

        $programId = $request->input('program_id', 1);
        $tahun = $request->input('tahun', date('Y'));
        $gang = $request->input('gang');

        $program = ProgramSedekah::find($programId);

        $donaturList = Donatur::query()
            ->when($gang, function ($query) use ($gang) {
                $query->where('gang', $gang);
            })
            ->when($program, function ($query) use ($program) {
                $query->where('nomor_kode', 'like', $program->kode . '-%');
            })
            ->orderBy('gang')
            ->orderBy('nomor_kode')
            ->get();

        $donasi = Donasi::selectRaw('
                donatur_id,
                bulan,
                SUM(nominal) as total_nominal
            ')
            ->where('tahun', $tahun)
            ->where('program_id', $programId)
            ->groupBy('donatur_id', 'bulan')
            ->get();

        $pivot = [];

        foreach ($donasi as $item) {
            $pivot[$item->donatur_id][$item->bulan] = $item->total_nominal;
        }

        $filename = 'laporan_donasi_'.now()->format('Ymd_His').'.pdf';

        // dd($donaturList->count());
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $pdf = Pdf::loadView('pages.admin.laporan_donasi.pdf', [
            'tahun' => $tahun,
            'gang' => $gang,
            'program' => $program,
            'programId' => $programId,
            'donaturList' => $donaturList,
            'pivot' => $pivot,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream($filename);
    }

    public function printRekap(Request $request)
    {
        $programId = $request->input('program_id', 1);
        $tahun = $request->input('tahun', date('Y'));

        $rekapBulanan = Donasi::query()
            ->selectRaw("
                bulan,
                CASE
                    WHEN donatur.gang = 12 AND donatur.alamat LIKE '%12A%' THEN '12A'
                    WHEN donatur.gang = 12 AND donatur.alamat LIKE '%12B%' THEN '12B'
                    ELSE CAST(donatur.gang AS CHAR)
                END AS gang_group,
                SUM(donasi.nominal) AS total
            ")
            ->join('donatur', 'donatur.id', '=', 'donasi.donatur_id')
            ->where('donasi.tahun', $tahun)
            ->where('donasi.program_id', $programId)
            ->groupBy('bulan', 'gang_group')
            ->get();

        $rekap = [];

        foreach ($rekapBulanan as $item) {
            $rekap[$item->bulan][$item->gang_group] = $item->total;
        }

        $program = ProgramSedekah::find($programId);

        $pdf = Pdf::loadView(
            'pages.admin.laporan_donasi.pdf_rekap',
            compact('rekap', 'program', 'tahun')
        )->setPaper('a4', 'landscape');

        return $pdf->stream('rekap_donasi.pdf');
    }
}
