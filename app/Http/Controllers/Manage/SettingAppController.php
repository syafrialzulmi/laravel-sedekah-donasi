<?php
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;

use App\Models\SettingApp;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingAppController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:setting-app-list|setting-app-create|setting-app-edit|setting-app-delete', ['only' => ['index','show']]);
         $this->middleware('permission:setting-app-create', ['only' => ['create','store']]);
         $this->middleware('permission:setting-app-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:setting-app-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $setting = SettingApp::first();
        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $desas = Desa::orderBy('desa')->get();

        return view('pages.admin.manage.setting_app.index', compact('setting','kecamatans','desas'));
    }

    public function store(Request $request)
    {
        // validasi (saat create)
        $validated = $request->validate([
            'name_app' => ['required', 'string', 'max:255'],
            'name_app_singkatan' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,ico,svg', 'max:1024'],
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
        ]);

        if (SettingApp::exists()) {
            return redirect()->route('setting-app.index')
                ->with('error', 'Data sudah ada. Gunakan tombol Ubah.');
        }

        $data = $validated;

        // simpan file kalau ada
        $data['logo']    = $this->storeUploadedFile($request, 'logo');
        $data['banner']  = $this->storeUploadedFile($request, 'banner');
        $data['favicon'] = $this->storeUploadedFile($request, 'favicon');

        SettingApp::create($data);

        return redirect()->route('setting-app.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function update(Request $request, SettingApp $settingApp)
    {
        // validasi (saat update)
        $validated = $request->validate([
            'name_app' => ['required', 'string', 'max:255'],
            'name_app_singkatan' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,ico,svg', 'max:1024'],
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
        ]);

        $data = $validated;

        // upload baru ⇒ hapus file lama
        foreach (['logo','banner','favicon'] as $field) {
            if ($request->hasFile($field)) {
                if ($settingApp->{$field}) {
                    Storage::disk('public')->delete($settingApp->{$field});
                }
                $data[$field] = $this->storeUploadedFile($request, $field);
            }
        }

        $settingApp->update($data);

        return redirect()->route('setting-app.index')->with('success', 'Pengaturan berhasil diubah.');
    }

    public function clear()
    {
        $setting = SettingApp::first();
        if (!$setting) {
            return redirect()->route('setting-app.index')->with('info', 'Tidak ada data untuk dikosongkan.');
        }

        // hapus file-file bila ada
        foreach (['logo','banner','favicon'] as $field) {
            if ($setting->{$field}) {
                Storage::disk('public')->delete($setting->{$field});
            }
        }

        $setting->delete();

        return redirect()->route('setting-app.index')->with('success', 'Data pengaturan telah dikosongkan.');
    }

    private function storeUploadedFile(Request $request, string $field): ?string
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file = $request->file($field);

        if (!$file->isValid()) {
            return null;
        }

        return Storage::disk('public')->putFile('setting-app', $file);
    }

    public function getDesaByKecamatan($kecamatan_id)
    {
        return Desa::where('kecamatan_id', $kecamatan_id)
            ->orderBy('desa')
            ->get(['id', 'desa']);
    }
}
