<?php

namespace App\Http\Controllers;

use App\Models\ProgramSedekah;
use App\Models\Donatur;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LaporanDonasiController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:laporan-donasi-list|laporan-donasi-create|laporan-donasi-edit|laporan-donasi-delete', ['only' => ['index','show']]);
         $this->middleware('permission:laporan-donasi-create', ['only' => ['create','store']]);
         $this->middleware('permission:laporan-donasi-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:laporan-donasi-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $programs = ProgramSedekah::orderBy('nama_program')->get();
        // default program_id = 1
        $programId = $request->input('program_id', 1);
        $tahun = $request->tahun ?? date('Y');

        $donaturList = Donatur::orderBy('nomor_kode')
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

        return view('pages.admin.laporan_donasi.index', [
            'tahun' => $tahun,
            'programId'   => $programId,
            'donaturList' => $donaturList,
            'pivot' => $pivot,
            'programs' => $programs,
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
}
