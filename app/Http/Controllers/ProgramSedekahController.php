<?php

namespace App\Http\Controllers;

use App\Models\ProgramSedekah;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramSedekahController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:program-sedekah-list|program-sedekah-create|program-sedekah-edit|program-sedekah-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:program-sedekah-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:program-sedekah-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:program-sedekah-delete', ['only' => ['destroy']]);
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

        $data = ProgramSedekah::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('nama_program', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate($ps)
            ->appends($request->only('ps', 'q'));

        return view('pages.admin.program_sedekah.index', [
            'data' => $data,
            'i' => ($data->currentPage() - 1) * $data->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.program_sedekah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode' => 'nullable|string',
            'jenis_target' => 'required|in:sukarela,target',
            'target_dana' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,aktif,selesai,ditutup',
        ]);

        // Jika sukarela, target dana otomatis null
        if ($validated['jenis_target'] === 'sukarela') {
            $validated['target_dana'] = null;
        }

        ProgramSedekah::create($validated);

        return redirect()
            ->route('program-sedekah.index')
            ->with('success', 'Program sedekah berhasil ditambahkan.');
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
    public function edit(ProgramSedekah $program_sedekah)
    {
        return view('pages.admin.program_sedekah.edit', compact('program_sedekah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode' => 'nullable|string',
            'jenis_target' => 'required|in:sukarela,target',
            'target_dana' => 'required_if:jenis_target,target|nullable|numeric|min:0',
            'status' => 'required|in:draft,aktif,selesai,ditutup',
        ]);

        $programSedekah = ProgramSedekah::findOrFail($id);

        // Jika jenis target sukarela, target dana dikosongkan
        if ($validated['jenis_target'] === 'sukarela') {
            $validated['target_dana'] = null;
        }

        $programSedekah->update($validated);

        return redirect()
            ->route('program-sedekah.index')
            ->with('success', 'Program sedekah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $programSedekah = ProgramSedekah::findOrFail($id);

        $programSedekah->delete();

        return redirect()
            ->route('program-sedekah.index')
            ->with('success', 'Program sedekah berhasil dihapus.');
    }
}
