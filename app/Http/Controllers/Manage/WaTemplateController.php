<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\WaTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WaTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:wa-template-list|wa-template-create|wa-template-edit|wa-template-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:wa-template-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:wa-template-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:wa-template-delete', ['only' => ['destroy']]);
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

        $data = WaTemplate::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('kode', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate($ps)
            ->appends($request->only('ps', 'q'));

        return view('pages.admin.notifikasi.index', [
            'data' => $data,
            'i' => ($data->currentPage() - 1) * $data->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $placeholders = [
            'nama',
            'nomor_kode',
            'program',
            'nominal',
            'periode',
            'perubahan',
            'tanggal',
            'jam',
            'petugas',
        ];

        return view('pages.admin.notifikasi.create', compact('placeholders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('wa_templates', 'kode'),
            ],
            'nama_template' => 'required|string|max:100',
            'isi' => 'required|string',
            'aktif' => 'required|boolean',
        ]);

        // Ambil semua placeholder dari template
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $validated['isi'], $matches);

        $variables = array_values(array_unique($matches[1]));

        WaTemplate::create([
            'kode' => strtoupper($validated['kode']),
            'nama_template' => $validated['nama_template'],
            'isi' => $validated['isi'],
            'variables' => $variables,
            'aktif' => $validated['aktif'],
        ]);

        return redirect()
            ->route('wa-template.index')
            ->with('success', 'Template WhatsApp berhasil ditambahkan.');
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
        $waTemplate = WaTemplate::findOrFail($id);

        $placeholders = [
            'nama',
            'nomor_kode',
            'program',
            'nominal',
            'periode',
            'perubahan',
            'tanggal',
            'jam',
            'petugas',
        ];

        return view('pages.admin.notifikasi.edit', compact(
            'waTemplate',
            'placeholders'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $waTemplate = WaTemplate::findOrFail($id);

        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('wa_templates', 'kode')->ignore($waTemplate->id),
            ],
            'nama_template' => 'required|string|max:100',
            'isi' => 'required|string',
            'aktif' => 'required|boolean',
        ]);

        // Ambil placeholder yang digunakan pada template
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $validated['isi'], $matches);

        $variables = array_values(array_unique($matches[1]));

        $waTemplate->update([
            'kode' => strtoupper($validated['kode']),
            'nama_template' => $validated['nama_template'],
            'isi' => $validated['isi'],
            'variables' => $variables,
            'aktif' => $validated['aktif'],
        ]);

        return redirect()
            ->route('wa-template.index')
            ->with('success', 'Template WhatsApp berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $waTemplate = WaTemplate::findOrFail($id);

        $waTemplate->delete();

        return redirect()
            ->route('wa-template.index')
            ->with('success', 'Template WhatsApp berhasil dihapus.');
    }
}
