<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route as RouteFacade;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:menu-list|menu-create|menu-edit|menu-delete', ['only' => ['index','show']]);
         $this->middleware('permission:menu-create', ['only' => ['create','store']]);
         $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedPageSizes = [5, 10, 20, 50];
        $ps = (int) $request->input('ps', 5);
        if (! in_array($ps, $allowedPageSizes, true)) {
            $ps = 5;
        }

        $q = trim((string) $request->input('q', ''));

        $menus = Menu::with('parent')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('route', 'like', "%{$q}%")
                    ->orWhereHas('parent', fn ($p) => $p->where('title', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate($ps)
            ->appends($request->only('ps', 'q')); // keep q & ps on links

        return view('pages.admin.menu.index', [
            'menus' => $menus,
            'i'     => ($menus->currentPage() - 1) * $menus->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::all();

        // daftar icon (class Font Awesome)
        $icons = [
            'fa fa-home' => 'Home',
            'fa fa-user' => 'User',
            'fa fa-cog' => 'Settings',
            'fa fa-list' => 'List',
            'fa fa-chart-bar' => 'Chart',
            'fa fa-envelope' => 'Envelope',
            'fa fa-bell' => 'Notification',
            'fa fa-lock' => 'Lock',
        ];

        return view('pages.admin.menu.create', compact('menus', 'icons'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'icon'             => ['nullable', 'string', 'max:255'],
            'route'            => ['nullable', 'string', 'max:255'],
            'parent_id'        => ['nullable', 'integer', 'exists:menus,id'],
            'order'            => ['nullable', 'integer'],
            'permission_name'  => ['nullable', 'string', 'max:255'],
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        if (!empty($validated['route']) && !RouteFacade::has($validated['route'])) {
            return back()
                ->withInput()
                ->withErrors(['route' => 'Nama route tidak ditemukan di aplikasi. Pastikan rute sudah terdaftar.']);
        }

        DB::beginTransaction();

        try {
            $menu = Menu::create([
                'title'           => trim($validated['title']),
                'icon'            => $validated['icon'] ?? null,
                'route'           => !empty($validated['route']) ? trim($validated['route']) : null,
                'parent_id'       => $validated['parent_id'] ?? null,
                'order'           => $validated['order'],
                'permission_name' => $validated['permission_name'] ?? null,
            ]);

            if (!empty($menu->route) && !empty($validated['permission_name'])) {
                // Hilangkan akhiran "-list" bila ada, jadikan basis
                $base = preg_replace('/-list$/i', '', trim($validated['permission_name']));

                // Kalau setelah trim kosong, skip untuk menghindari " -list"
                if ($base !== '') {
                    $acts = ['list', 'create', 'edit', 'delete'];
                    foreach ($acts as $act) {
                        $permName = $base . '-' . $act;   // contoh: "menu-list", "menu-create", dst.
                        Permission::firstOrCreate(['name' => $permName, 'menu_id' => $menu->id]);
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            // Bisa juga log error: \Log::error($e);
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan saat menyimpan data.']);
        }

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return view('pages.admin.menu.show',compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $icons = config('app.menu_icons',[
            'fa fa-home' => 'Home',
            'fa fa-user' => 'User',
            'fa fa-cog' => 'Settings',
            'fa fa-list' => 'List',
            'fa fa-chart-bar' => 'Chart',
            'fa fa-envelope' => 'Envelope',
            'fa fa-bell' => 'Notification',
            'fa fa-lock' => 'Lock',
        ]);

        $menus = Menu::where('id', '!=', $menu->id)
                 ->orderBy('title')
                 ->get(['id','title','icon']);

        return view('pages.admin.menu.edit', compact('menu','icons','menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        // 1) Validasi
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'icon'            => ['nullable', 'string', 'max:255'],
            'route'           => ['nullable', 'string', 'max:255'],
            'parent_id'       => ['nullable', 'integer', 'exists:menus,id'],
            'order'           => ['nullable', 'integer'],
            'permission_name' => ['nullable', 'string', 'max:255'],
        ]);

        // parent tidak boleh dirinya sendiri
        if (!empty($validated['parent_id']) && (int) $validated['parent_id'] === (int) $menu->id) {
            return back()->withInput()->withErrors(['parent_id' => 'Parent tidak boleh item yang sama.']);
        }

        $validated['order'] = $validated['order'] ?? 0;

        // 2) Cek route bila diisi
        if (!empty($validated['route']) && !RouteFacade::has($validated['route'])) {
            return back()->withInput()->withErrors(['route' => 'Nama route tidak ditemukan di aplikasi.']);
        }

        // 3) Hitung base permission lama & baru
        $oldBase = $menu->permission_name ? preg_replace('/-list$/i', '', trim($menu->permission_name)) : null;
        $newBase = !empty($validated['permission_name'])
            ? preg_replace('/-list$/i', '', trim($validated['permission_name']))
            : null;

        // 4) Kebutuhan permission baru (jika route tidak null & ada base)
        $shouldHavePerms = !empty($validated['route']) && !empty($newBase);

        $acts = ['list','create','edit','delete'];
        $oldNames = $oldBase ? array_map(fn($a) => "{$oldBase}-{$a}", $acts) : [];
        $newNames = $shouldHavePerms ? array_map(fn($a) => "{$newBase}-{$a}", $acts) : [];

        DB::beginTransaction();
        try {
            // 5) Update menu
            $menu->update([
                'title'           => trim($validated['title']),
                'icon'            => $validated['icon'] ?? null,
                'route'           => !empty($validated['route']) ? trim($validated['route']) : null,
                'parent_id'       => $validated['parent_id'] ?? null,
                'order'           => $validated['order'],
                // simpan permission_name seperti input (mis. "menu-list")
                'permission_name' => $validated['permission_name'] ?? null,
            ]);

            // 6) Kelola permissions
            if ($shouldHavePerms) {
                // a) Pastikan permission baru ada & tertaut ke menu_id ini
                foreach ($newNames as $permName) {
                    $perm = Permission::firstOrCreate(['name' => $permName]);
                    if ($perm->menu_id !== $menu->id) {
                        $perm->menu_id = $menu->id;
                        $perm->save();
                    }
                }

                // b) Jika base berubah, hapus permission lama milik menu ini yang tidak lagi dipakai
                if ($oldBase && $oldBase !== $newBase) {
                    Permission::where('menu_id', $menu->id)
                        ->whereIn('name', $oldNames)
                        ->whereNotIn('name', $newNames)
                        ->delete();
                }
            } else {
                // Route kosong atau permission_name kosong → hapus semua permission milik menu ini
                Permission::where('menu_id', $menu->id)->delete();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan: '.$e->getMessage()]);
        }

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menu berhasil diperbarui'.($shouldHavePerms ? ' beserta permissions.' : '.'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('menus.index')
                        ->with('success','Menu deleted successfully');
    }
}
