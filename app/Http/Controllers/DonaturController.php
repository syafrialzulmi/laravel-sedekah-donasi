<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Donatur;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DonaturController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:donatur-list|donatur-create|donatur-edit|donatur-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:donatur-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:donatur-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:donatur-delete', ['only' => ['destroy']]);
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

        $gang = $request->gang;

        $data = Donatur::query()
            ->when($q, function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%");
            })
            ->when($gang, function ($query) use ($gang) {
                $query->where('gang', $gang);
            })
            ->latest()
            ->paginate($ps)
            ->appends($request->only('q', 'gang', 'ps'));

        return view('pages.admin.donatur.index', [
            'data' => $data,
            'i' => ($data->currentPage() - 1) * $data->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        // $desas = collect();

        $kecamatan = Kecamatan::findOrFail(4);
        $desa = Desa::findOrFail(58);

        $lastKode = Donatur::where('nomor_kode', 'like', 'UL-%')
            ->orderByDesc('id')
            ->value('nomor_kode');

        if ($lastKode) {
            $nomor = (int) substr($lastKode, 3);
            $nomor++;
        } else {
            $nomor = 1;
        }

        $kodeDonatur = 'UL-'.str_pad($nomor, 4, '0', STR_PAD_LEFT);

        return view(
            'pages.admin.donatur.create',
            compact(
                'kecamatan',
                'desa',
                'kodeDonatur'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kode' => 'required|unique:donatur,nomor_kode',
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'dukuh' => 'nullable|string|max:255',
            'gang' => 'nullable|integer|min:1|max:20',
            'desa_id' => 'nullable|exists:desa,id',
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();

        try {

            // Generate kode donatur
            // $lastId = Donatur::max('id') + 1;

            // $validated['nomor_kode'] = 'DON-'.str_pad(
            //     $lastId,
            //     6,
            //     '0',
            //     STR_PAD_LEFT
            // );

            Donatur::create($validated);

            DB::commit();

            return redirect()
                ->route('donatur.index')
                ->with('success', 'Donatur berhasil ditambahkan.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data. '.$e->getMessage());
        }
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
    public function edit(Donatur $donatur)
    {
        // $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        // $desas = collect();
        $kecamatan = Kecamatan::findOrFail(4);
        $desa = Desa::findOrFail(58);

        return view('pages.admin.donatur.edit', compact('donatur', 'kecamatan', 'desa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donatur = Donatur::findOrFail($id);

        $validated = $request->validate([
            'nomor_kode' => [
                'required',
                Rule::unique('donatur', 'nomor_kode')->ignore($donatur->id),
            ],
            'nama' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'dukuh' => 'nullable|string|max:255',
            'gang' => 'nullable|integer|min:1|max:20',
            // 'desa_id' => 'nullable|exists:desa,id',
            // 'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();

        try {

            $donatur = Donatur::findOrFail($id);

            $donatur->update($validated);

            DB::commit();

            return redirect()
                ->route('donatur.index')
                ->with('success', 'Data donatur berhasil diperbarui.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donatur = Donatur::findOrFail($id);

        if ($donatur->donasi()->exists()) {
            return redirect()
                ->route('donatur.index')
                ->with('error', 'Data donatur tidak dapat dihapus karena masih memiliki data donasi.');
        }

        $donatur->delete();

        return redirect()
            ->route('donatur.index')
            ->with('success', 'Data donatur berhasil dihapus.');
    }

    public function generateKode()
    {
        $lastKode = Donatur::where('nomor_kode', 'like', 'UL-%')
            ->orderByDesc('id')
            ->value('nomor_kode');

        $nomor = $lastKode
            ? ((int) substr($lastKode, 3)) + 1
            : 1;

        return response()->json([
            'kode' => 'UL-'.str_pad($nomor, 4, '0', STR_PAD_LEFT),
        ]);
    }

    public function cariByKode(Request $request)
    {
        $donatur = Donatur::with([
            'desa',
            'kecamatan',
        ])
            ->where('nomor_kode', $request->nomor_kode)
            ->first();

        if (! $donatur) {
            return response()->json([
                'success' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $donatur,
        ]);
    }
}
