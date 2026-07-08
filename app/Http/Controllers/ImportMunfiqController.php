<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\ImportMunfiq;
use App\Models\ProgramSedekah;
use App\Models\SettingApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMunfiqController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:import-munfiq-list|import-munfiq-create|import-munfiq-edit|import-munfiq-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:import-munfiq-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:import-munfiq-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:import-munfiq-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        return view('pages.admin.import_munfiq.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        ImportMunfiq::truncate();

        $spreadsheet = IOFactory::load($request->file('file'));

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {

            $sheetName = $sheet->getTitle();

            $rows = $sheet->toArray();

            foreach (array_slice($rows, 6) as $row) {

                $kode = trim($row[1] ?? '');
                $nama = trim($row[2] ?? '');

                if ($nama == '') {
                    continue;
                }

                if (in_array(strtoupper($nama), [
                    'JUMLAH',
                    'TOTAL',
                ])) {
                    continue;
                }

                ImportMunfiq::create([
                    'sheet_name' => $sheetName,
                    'no' => $row[0],
                    'kode' => trim($row[1]),
                    'nama' => trim($row[2]),
                    'no_hp' => trim($row[3]),

                    'jan' => $this->angka($row[4]),
                    'feb' => $this->angka($row[5]),
                    'mar' => $this->angka($row[6]),
                    'apr' => $this->angka($row[7]),
                    'mei' => $this->angka($row[8]),
                    'jun' => $this->angka($row[9]),
                    'jul' => $this->angka($row[10]),
                    'agt' => $this->angka($row[11]),
                    'sept' => $this->angka($row[12]),
                    'okt' => $this->angka($row[13]),
                    'nov' => $this->angka($row[14]),
                    'des' => $this->angka($row[15]),
                ]);
            }
        }

        // return back()->with('success', 'Import berhasil');
        return redirect()
            ->route('import-munfiq.review')
            ->with('success', 'Import berhasil. Silakan review data terlebih dahulu.');
    }

    private function angka($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        // Kosong atau hanya tanda "-"
        if ($value === '' || $value === '-') {
            return null;
        }

        // Hilangkan spasi
        $value = str_replace(' ', '', $value);

        // Hilangkan pemisah ribuan
        $value = str_replace(',', '', $value);

        // Jika bukan angka
        if (! is_numeric($value)) {
            return null;
        }

        return $value;
    }

    public function review(Request $request)
    {
        $gang = $request->gang;
        $kode = $request->kode;
        $q = trim($request->q);

        $query = ImportMunfiq::query();

        if (! empty($gang)) {
            $query->where('sheet_name', $gang);
        }

        if ($kode == 'UL') {

            $query->where('kode', 'like', 'UL-%');

        } elseif ($kode == 'ULM') {

            $query->where('kode', 'like', 'ULM-%');

        } elseif ($kode == 'TANPA') {

            $query->where(function ($q) {
                $q->whereNull('kode')
                    ->orWhere('kode', '')
                    ->orWhere('kode', '-');
            });

        } elseif ($kode == 'INVALID') {

            $query->whereNotNull('kode')
                ->where('kode', '<>', '')
                ->where('kode', '<>', '-')
                ->where(function ($q) {
                    $q->where('kode', 'not like', 'UL-%')
                        ->where('kode', 'not like', 'ULM-%');
                });

        }

        if ($q) {
            $query->where(function ($x) use ($q) {

                $x->where('nama', 'like', "%{$q}%")
                ->orWhere('kode', 'like', "%{$q}%");

            });
        }

        // $data = $query
        //     ->orderBy('sheet_name')
        //     ->orderBy('no')
        //     ->paginate(100)
        //     ->withQueryString();
        $data = $query
            ->orderByRaw("
                CAST(COALESCE(REGEXP_SUBSTR(sheet_name, '[0-9]+'),0) AS UNSIGNED)
            ")
            ->orderByRaw("
                COALESCE(REGEXP_SUBSTR(sheet_name, '[A-Za-z]+$'),'')
            ")
            ->orderBy('no')
            ->paginate(100)
            ->withQueryString();

        $gangList = ImportMunfiq::select('sheet_name')
            ->distinct()
            ->orderByRaw("
                CAST(COALESCE(REGEXP_SUBSTR(sheet_name, '[0-9]+'), 0) AS UNSIGNED)
            ")
            ->orderByRaw("
                COALESCE(REGEXP_SUBSTR(sheet_name, '[A-Za-z]+$'), '')
            ")
            ->pluck('sheet_name');

        $statistik = [
            'total' => (clone $query)->count(),

            'ul' => (clone $query)
                ->where('kode', 'like', 'UL-%')
                ->count(),

            'ulm' => (clone $query)
                ->where('kode', 'like', 'ULM-%')
                ->count(),

            'tanpa_kode' => (clone $query)
                ->where(function ($q) {
                    $q->whereNull('kode')
                        ->orWhere('kode', '')
                        ->orWhere('kode', '-');
                })
                ->count(),
        ];

        $nominal = [
            'total' => (clone $query)->sum(
                \DB::raw('
                    COALESCE(jan,0)+COALESCE(feb,0)+COALESCE(mar,0)+
                    COALESCE(apr,0)+COALESCE(mei,0)+COALESCE(jun,0)+
                    COALESCE(jul,0)+COALESCE(agt,0)+COALESCE(sept,0)+
                    COALESCE(okt,0)+COALESCE(nov,0)+COALESCE(des,0)
                ')
            ),

            'ul' => (clone $query)
                ->where('kode', 'like', 'UL-%')
                ->sum(
                    \DB::raw('
                        COALESCE(jan,0)+COALESCE(feb,0)+COALESCE(mar,0)+
                        COALESCE(apr,0)+COALESCE(mei,0)+COALESCE(jun,0)+
                        COALESCE(jul,0)+COALESCE(agt,0)+COALESCE(sept,0)+
                        COALESCE(okt,0)+COALESCE(nov,0)+COALESCE(des,0)
                    ')
                ),

            'ulm' => (clone $query)
                ->where('kode', 'like', 'ULM-%')
                ->sum(
                    \DB::raw('
                        COALESCE(jan,0)+COALESCE(feb,0)+COALESCE(mar,0)+
                        COALESCE(apr,0)+COALESCE(mei,0)+COALESCE(jun,0)+
                        COALESCE(jul,0)+COALESCE(agt,0)+COALESCE(sept,0)+
                        COALESCE(okt,0)+COALESCE(nov,0)+COALESCE(des,0)
                    ')
                ),

            'tanpa_kode' => (clone $query)
                ->where(function ($q) {
                    $q->whereNull('kode')
                        ->orWhere('kode', '')
                        ->orWhere('kode', '-');
                })
                ->sum(
                    \DB::raw('
                        COALESCE(jan,0)+COALESCE(feb,0)+COALESCE(mar,0)+
                        COALESCE(apr,0)+COALESCE(mei,0)+COALESCE(jun,0)+
                        COALESCE(jul,0)+COALESCE(agt,0)+COALESCE(sept,0)+
                        COALESCE(okt,0)+COALESCE(nov,0)+COALESCE(des,0)
                    ')
                ),
        ];

        // ==

        $dashboard = [];

        $gangs = ImportMunfiq::select('sheet_name')
            ->distinct()
            ->pluck('sheet_name')
            ->sort(function ($a, $b) {

                preg_match('/(\d+)([A-Z]*)/i', $a, $ma);
                preg_match('/(\d+)([A-Z]*)/i', $b, $mb);

                $na = (int) ($ma[1] ?? 0);
                $nb = (int) ($mb[1] ?? 0);

                if ($na == $nb) {
                    return strcmp($ma[2] ?? '', $mb[2] ?? '');
                }

                return $na <=> $nb;
            })
            ->values();

        $bulan = [
            'jan', 'feb', 'mar', 'apr', 'mei', 'jun',
            'jul', 'agt', 'sept', 'okt', 'nov', 'des',
        ];

        foreach ($gangs as $gang) {

            $dashboard[$gang] = [];

            $baseQuery = ImportMunfiq::where('sheet_name', $gang);

            // Jumlah Orang + Total Nominal Setahun
            $dashboard[$gang]['summary'] = $this->dashboardQuery($baseQuery);

            // Per Bulan
            foreach ($bulan as $b) {

                $dashboard[$gang]['bulan'][$b] = $this->dashboardQuery(
                    ImportMunfiq::where('sheet_name', $gang),
                    $b
                )['nominal'];

            }

        }

        // ==

        $rekap = [];

        $bulan = [
            'jan' => 'JAN',
            'feb' => 'FEB',
            'mar' => 'MAR',
            'apr' => 'APR',
            'mei' => 'MEI',
            'jun' => 'JUN',
            'jul' => 'JUL',
            'agt' => 'AGT',
            'sept' => 'SEPT',
            'okt' => 'OKT',
            'nov' => 'NOV',
            'des' => 'DES',
        ];

        // $gangs = ImportMunfiq::select('sheet_name')
        //     ->distinct()
        //     ->orderBy('sheet_name')
        //     ->pluck('sheet_name');
        $gangs = ImportMunfiq::select('sheet_name')
            ->distinct()
            ->orderGang()
            ->pluck('sheet_name');

        foreach ($bulan as $field => $label) {

            $rekap[$field] = [
                'title' => $label,
                'rows' => [],
                'grand_total' => [
                    'total' => 0,
                    'ul' => 0,
                    'ulm' => 0,
                    'tanpa' => 0,
                ],
            ];

            foreach ($gangs as $gang) {

                $query = ImportMunfiq::where('sheet_name', $gang);

                $row = [

                    'gang' => $gang,

                    'total' => (clone $query)->sum($field),

                    'ul' => (clone $query)
                        ->where('kode', 'like', 'UL-%')
                        ->sum($field),

                    'ulm' => (clone $query)
                        ->where('kode', 'like', 'ULM-%')
                        ->sum($field),

                    'tanpa' => (clone $query)
                        ->where(function ($q) {
                            $q->whereNull('kode')
                                ->orWhere('kode', '')
                                ->orWhere('kode', '-');
                        })
                        ->sum($field),

                ];

                $rekap[$field]['rows'][] = $row;

                $rekap[$field]['grand_total']['total'] += $row['total'];
                $rekap[$field]['grand_total']['ul'] += $row['ul'];
                $rekap[$field]['grand_total']['ulm'] += $row['ulm'];
                $rekap[$field]['grand_total']['tanpa'] += $row['tanpa'];
            }

        }

        return view('pages.admin.import_munfiq.review', compact(
            'data',
            'gangList',
            'gang',
            'kode',
            'q',
            'statistik',
            'nominal',
            'dashboard',
            'rekap'
        ));
    }

    private function dashboardQuery($query, $field = null)
    {
        $sumNominal = DB::raw('
            COALESCE(jan,0)+COALESCE(feb,0)+COALESCE(mar,0)+
            COALESCE(apr,0)+COALESCE(mei,0)+COALESCE(jun,0)+
            COALESCE(jul,0)+COALESCE(agt,0)+COALESCE(sept,0)+
            COALESCE(okt,0)+COALESCE(nov,0)+COALESCE(des,0)
        ');

        $result = [];

        // Total Orang
        $result['orang'] = [
            'total' => (clone $query)->count(),
            'ul' => (clone $query)->where('kode', 'like', 'UL-%')->count(),
            'ulm' => (clone $query)->where('kode', 'like', 'ULM-%')->count(),
            'tanpa' => (clone $query)
                ->where(function ($q) {
                    $q->whereNull('kode')
                        ->orWhere('kode', '')
                        ->orWhere('kode', '-');
                })
                ->count(),
        ];

        // Nominal
        if ($field == null) {

            $result['nominal'] = [
                'total' => (clone $query)->sum($sumNominal),

                'ul' => (clone $query)
                    ->where('kode', 'like', 'UL-%')
                    ->sum($sumNominal),

                'ulm' => (clone $query)
                    ->where('kode', 'like', 'ULM-%')
                    ->sum($sumNominal),

                'tanpa' => (clone $query)
                    ->where(function ($q) {
                        $q->whereNull('kode')
                            ->orWhere('kode', '')
                            ->orWhere('kode', '-');
                    })
                    ->sum($sumNominal),
            ];
        } else {

            $result['nominal'] = [

                'total' => (clone $query)->sum($field),

                'ul' => (clone $query)
                    ->where('kode', 'like', 'UL-%')
                    ->sum($field),

                'ulm' => (clone $query)
                    ->where('kode', 'like', 'ULM-%')
                    ->sum($field),

                'tanpa' => (clone $query)
                    ->where(function ($q) {
                        $q->whereNull('kode')
                            ->orWhere('kode', '')
                            ->orWhere('kode', '-');
                    })
                    ->sum($field),
            ];
        }

        return $result;
    }

    public function update(Request $request, ImportMunfiq $importMunfiq)
    {
        $request->validate([
            'sheet_name' => 'required',
            'nama' => 'required',
            'kode' => [
                'nullable',
                'max:20',
                Rule::unique('import_munfiq', 'kode')->ignore($importMunfiq->id),
            ],
            'no_hp' => 'nullable|max:20',
        ], [
            'kode.unique' => 'Kode sudah digunakan oleh data lain.',
        ]);

        $importMunfiq->update([
            'sheet_name' => $request->sheet_name,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
        ]);

        return back()->with(
            'success',
            'Data berhasil diperbarui.'
        );
    }

    public function sync(Request $request)
    {
        $request->validate([
            'tahun' => ['required', 'digits:4'],
        ]);

        $tahun = $request->tahun;

        $setting = SettingApp::first();

        $desaId = $setting?->desa_id;
        $kecamatanId = $setting?->kecamatan_id;

        try {
            DB::transaction(function () use ($tahun, $desaId, $kecamatanId) {
                $programs = ProgramSedekah::pluck('id', 'kode');
                $rows = ImportMunfiq::all();

                foreach ($rows as $row) {
                    // ============================
                    // Gang
                    // ============================
                    $gang = null;
                    if (! empty($row->sheet_name)) {
                        preg_match('/(\d+)/', $row->sheet_name, $match);
                        $gang = $match[1] ?? null;
                    }
                    // ============================
                    // Program
                    // ============================
                    $programKode = null;
                    if (str_starts_with($row->kode, 'ULM-')) {
                        $programKode = 'ULM';
                    } elseif (str_starts_with($row->kode, 'UL-')) {
                        $programKode = 'UL';
                    }
                    $programId = $programs[$programKode] ?? null;
                    // ============================
                    // Donatur
                    // ============================
                    $donatur = Donatur::updateOrCreate(
                        [
                            'nomor_kode' => $row->kode,
                        ],
                        [
                            'nama' => $row->nama,
                            'no_hp' => $row->no_hp,
                            'alamat' => $row->sheet_name,
                            'gang' => $gang,
                            'status' => 1,
                            'desa_id' => $desaId,
                            'kecamatan_id' => $kecamatanId,
                        ]
                    );
                    // ============================
                    // Donasi
                    // ============================
                    if ($programId) {
                        $bulanMap = [
                            1 => 'jan',
                            2 => 'feb',
                            3 => 'mar',
                            4 => 'apr',
                            5 => 'mei',
                            6 => 'jun',
                            7 => 'jul',
                            8 => 'agt',
                            9 => 'sept',
                            10 => 'okt',
                            11 => 'nov',
                            12 => 'des',
                        ];

                        foreach ($bulanMap as $bulan => $field) {
                            $nominal = $row->$field;
                            if (empty($nominal)) {
                                continue;
                            }
                            Donasi::updateOrCreate(
                                [
                                    'donatur_id' => $donatur->id,
                                    'program_id' => $programId,
                                    'bulan' => $bulan,
                                    'tahun' => $tahun,
                                ],
                                [
                                    'nominal' => $nominal,
                                    'tanggal_donasi' => sprintf('%04d-%02d-25', $tahun, $bulan),
                                    'keterangan' => 'Import Excel',
                                    'user_id' => auth()->id(),
                                ]
                            );
                        }
                    }
                }
            });

            // Hanya dijalankan jika transaction sukses
            ImportMunfiq::truncate();

            return redirect()
                ->route('import-munfiq.index')
                ->with('success', 'Sinkronisasi berhasil. Data import telah dibersihkan.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Sinkronisasi gagal: '.$e->getMessage());

        }
    }

    public function truncate()
    {
        ImportMunfiq::truncate();

        return redirect()
            ->route('import-munfiq.index')
            ->with('success', 'Seluruh data hasil import berhasil dihapus.');
    }

    public function updateNominal(Request $request, ImportMunfiq $importMunfiq)
    {
        $request->validate([
            'bulan' => 'required|in:jan,feb,mar,apr,mei,jun,jul,agt,sept,okt,nov,des',
            'nominal' => 'nullable|numeric|min:0',
        ]);

        $importMunfiq->update([
            $request->bulan => $request->filled('nominal')
                ? $request->nominal
                : null,
        ]);

        return back()->with('success', 'Nominal berhasil diperbarui.');
    }
}
