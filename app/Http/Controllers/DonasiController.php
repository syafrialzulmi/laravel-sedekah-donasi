<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\ProgramSedekah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:donasi-list|donasi-create|donasi-edit|donasi-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:donasi-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:donasi-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:donasi-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $allowedPageSizes = [5, 10, 20, 50];
        $ps = (int) $request->input('ps', 10);

        if (! in_array($ps, $allowedPageSizes, true)) {
            $ps = 10;
        }

        $q = trim((string) $request->input('q', ''));
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $gang = $request->gang;

        $programId = $request->input('program_id', 1);
        $programs = ProgramSedekah::orderBy('nama_program')
            ->get();

        $data = Donasi::with([
            'donatur',
            'program',
        ])

                // Filter pencarian
            ->when($q, function ($query) use ($q) {

                $query->where(function ($sub) use ($q) {

                    $sub->whereHas('donatur', function ($d) use ($q) {
                        $d->where('nama', 'like', "%{$q}%")
                            ->orWhere('nomor_kode', 'like', "%{$q}%");
                    })

                        ->orWhereHas('program', function ($p) use ($q) {
                            $p->where('nama_program', 'like', "%{$q}%");
                        })

                        ->orWhere('bulan', 'like', "%{$q}%")
                        ->orWhere('tahun', 'like', "%{$q}%")
                        ->orWhere('nominal', 'like', "%{$q}%")
                        ->orWhere('keterangan', 'like', "%{$q}%");
                });

            })
                // Filter tanggal awal
            ->when($tanggalAwal, function ($query) use ($tanggalAwal) {
                $query->whereDate(
                    'tanggal_donasi',
                    '>=',
                    $tanggalAwal
                );
            })

                // Filter tanggal akhir
            ->when($tanggalAkhir, function ($query) use ($tanggalAkhir) {
                $query->whereDate(
                    'tanggal_donasi',
                    '<=',
                    $tanggalAkhir
                );
            })
                // Filter gang
            ->when($gang, function ($query) use ($gang) {
                $query->whereHas('donatur', function ($q) use ($gang) {
                    $q->where('gang', $gang);
                });
            })

            // Filter program sedekah
            ->when($programId, function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })

            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($ps)
            ->appends([
                'q' => $q,
                'gang' => $gang,
                'program_id' => $programId,
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'ps' => $ps,
            ]);

        return view('pages.admin.donasi.index', [
            'data' => $data,
            'i' => ($data->currentPage() - 1) * $data->perPage(),
            'programs' => $programs,
            'programId' => $programId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = ProgramSedekah::orderBy('nama_program')->get();

        return view('pages.admin.donasi.create', compact(
            'programs',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donatur_id' => ['required', 'exists:donatur,id'],
            'program_id' => ['required', 'exists:program_sedekah,id'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'digits:4'],
            'tanggal_donasi' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Cek donasi periode yang sama
        $sudahAda = Donasi::where('donatur_id', $validated['donatur_id'])
            ->where('program_id', $validated['program_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'donatur_id' => 'Donasi untuk program dan periode tersebut sudah pernah diinput.',
                ]);
        }

        $donasi = Donasi::create([
            'donatur_id' => $validated['donatur_id'],
            'program_id' => $validated['program_id'],
            'nominal' => $validated['nominal'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'tanggal_donasi' => $validated['tanggal_donasi'],
            'keterangan' => $validated['keterangan'] ?? null,
            'wa_terkirim' => false,
            'wa_terkirim_at' => null,
            'user_id' => auth()->id(),
        ]);

        $donasi->load([
            'donatur',
            'program',
        ]);

        return redirect()
            ->route('donasi.create')
            ->with('show_wa_modal', true)
            ->with('wa_data', [
                'id' => $donasi->id,
                'nama' => $donasi->donatur->nama,
                'hp' => $donasi->donatur->no_hp,
                'program' => $donasi->program->nama_program,
                'nominal' => number_format($donasi->nominal, 0, ',', '.'),
                'periode' => $donasi->periode,
            ]);

    }

    public function waTerkirim($id)
    {
        $donasi = Donasi::findOrFail($id);

        $donasi->update([
            'wa_terkirim' => true,
            'wa_terkirim_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function kirimWa(Donasi $donasi)
    {
        $donasi->update([
            'wa_terkirim' => true,
            'wa_terkirim_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
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
        $donasi = Donasi::with([
            'donatur',
            'program',
        ])->findOrFail($id);

        $programs = ProgramSedekah::orderBy('nama_program')->get();

        return view('pages.admin.donasi.edit', compact(
            'donasi',
            'programs'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donasi = Donasi::with([
            'donatur',
            'program',
        ])->findOrFail($id);

        $request->validate([
            'program_id' => 'required',
            'donatur_id' => 'required',
            'tanggal_donasi' => 'required|date',
            'bulan' => 'required',
            'tahun' => 'required',
            'nominal' => 'required|numeric|min:0',
        ]);

        // simpan data lama
        $oldData = [
            'program_id' => $donasi->program_id,
            'tanggal_donasi' => $donasi->tanggal_donasi,
            'bulan' => $donasi->bulan,
            'tahun' => $donasi->tahun,
            'nominal' => $donasi->nominal,
            'keterangan' => $donasi->keterangan,
        ];

        $donasi->update([
            'program_id' => $request->program_id,
            'donatur_id' => $request->donatur_id,
            'tanggal_donasi' => $request->tanggal_donasi,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
        ]);

        $donasi->refresh();
        $donasi->load(['donatur', 'program']);

        // buat daftar perubahan
        $changes = [];

        if ($oldData['program_id'] != $donasi->program_id) {
            $changes[] = 'Program donasi diubah';
        }

        if ($oldData['tanggal_donasi'] != $donasi->tanggal_donasi) {
            $changes[] = 'Tanggal donasi diubah';
        }

        if ($oldData['bulan'] != $donasi->bulan ||
            $oldData['tahun'] != $donasi->tahun) {
            $changes[] = 'Periode donasi diubah';
        }

        if ($oldData['nominal'] != $donasi->nominal) {
            $changes[] =
                'Nominal donasi dari Rp '
                .number_format($oldData['nominal'], 0, ',', '.')
                .' menjadi Rp '
                .number_format($donasi->nominal, 0, ',', '.');
        }

        if ($oldData['keterangan'] != $donasi->keterangan) {
            $changes[] = 'Keterangan donasi diubah';
        }

        return redirect()
            ->route('donasi.edit', $donasi->id)
            ->with('success', 'Data donasi berhasil diperbarui')
            ->with('show_wa_modal_update', true)
            ->with('wa_update_data', [
                'id' => $donasi->id,
                'nama' => $donasi->donatur->nama,
                'hp' => $donasi->donatur->no_hp,
                'program' => $donasi->program->nama_program,
                'nominal' => number_format($donasi->nominal, 0, ',', '.'),
                'periode' => $donasi->bulan.'/'.$donasi->tahun,
                'changes' => $changes,
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $donasi = Donasi::findOrFail($id);

            $donasi->delete();

            return redirect()
                ->route('donasi.index')
                ->with('success', 'Data donasi berhasil dihapus.');

        } catch (\Exception $e) {

            return redirect()
                ->route('donasi.index')
                ->with('error', 'Data donasi gagal dihapus.');
        }
    }

    public function print(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $gang = $request->input('gang');
        $programId = $request->input('program_id');

        $data = Donasi::with([
            'donatur',
            'program',
        ])

                // Pencarian
            ->when($q, function ($query) use ($q) {

                $query->where(function ($sub) use ($q) {

                    $sub->whereHas('donatur', function ($d) use ($q) {
                        $d->where('nama', 'like', "%{$q}%")
                            ->orWhere('nomor_kode', 'like', "%{$q}%")
                            ->orWhere('gang', 'like', "%{$q}%");
                    })

                        ->orWhereHas('program', function ($p) use ($q) {
                            $p->where('nama_program', 'like', "%{$q}%");
                        })

                        ->orWhere('bulan', 'like', "%{$q}%")
                        ->orWhere('tahun', 'like', "%{$q}%")
                        ->orWhere('nominal', 'like', "%{$q}%")
                        ->orWhere('keterangan', 'like', "%{$q}%");
                });

            })

                // Filter gang
            ->when($gang, function ($query) use ($gang) {
                $query->whereHas('donatur', function ($q) use ($gang) {
                    $q->where('gang', $gang);
                });
            })

            // Filter program
            ->when($programId, function ($query) use ($programId) {
                $query->where('program_id', $programId);
            })

                // Filter tanggal awal
            ->when($tanggalAwal, function ($query) use ($tanggalAwal) {
                $query->whereDate('tanggal_donasi', '>=', $tanggalAwal);
            })

                // Filter tanggal akhir
            ->when($tanggalAkhir, function ($query) use ($tanggalAkhir) {
                $query->whereDate('tanggal_donasi', '<=', $tanggalAkhir);
            })
            ->orderBy('tanggal_donasi')
            ->orderBy('id')
            ->get();

        $filename = 'laporan_donasi_'.now()->format('Ymd_His').'.pdf';

        $program = null;

        if ($programId) {
            $program = ProgramSedekah::find($programId);
        }

        $pdf = Pdf::loadView('pages.admin.donasi.pdf', [
            'data' => $data,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'gang' => $gang,
            'programId' => $programId,
            'program' => $program,
            'q' => $q,
        ])
            ->setPaper('a4', 'landscape');

        return $pdf->stream($filename);
    }
}
