<?php

namespace App\Http\Controllers;

use App\Models\SettingApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingAppController extends Controller
{
    public function index()
    {
        $setting = SettingApp::first();
        return view('pages.admin.setting_app.index', compact('setting'));
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
        if (!$request->hasFile($field)) return null;
        // path di disk "public"
        return $request->file($field)->store('setting-app', 'public');
    }
}
