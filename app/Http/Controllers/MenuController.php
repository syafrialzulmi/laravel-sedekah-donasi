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
        $ps = (int) $request->get('ps', 10);
        $q  = $request->get('q');

        // daftar (tabel) — biarkan seperti semula
        $menusQuery = Menu::query()->with('parent');
        if ($q) {
            $menusQuery->where(function ($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")
                ->orWhere('route', 'like', "%{$q}%")
                ->orWhereHas('parent', fn($p) => $p->where('title', 'like', "%{$q}%"));
            });
        }
        $menus = $menusQuery->paginate($ps)->withQueryString();

        // hirarki (tree) — tanpa pagination agar struktur tidak terpotong
        $treeQuery = Menu::query()
            ->with(['childrenRecursive', 'parent'])
            ->orderBy('title');

        if ($q) {
            $treeQuery->where(function ($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")
                ->orWhere('route', 'like', "%{$q}%")
                ->orWhereHas('parent', fn($p) => $p->where('title', 'like', "%{$q}%"));
            });
        }

        $roots = $treeQuery->whereNull('parent_id')->get();

        return view('pages.admin.manage.menu.index', compact('menus', 'roots'));
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
            'fa fa-users' => 'Users',
            'fa fa-cog' => 'Settings',
            'fa fa-list' => 'List',
            'fa fa-chart-bar' => 'Chart',
            'fa fa-envelope' => 'Envelope',
            'fa fa-bell' => 'Notification',
            'fa fa-lock' => 'Lock',
            'fa fa-circle' => 'Circle',
            'fa fa-folder-open' => 'Folder Open',
            'fa fa-file'         => 'File',
            'fa fa-database'     => 'Database',
            'fa fa-map-marker'   => 'Map Marker',
            'fa fa-calendar'     => 'Calendar',
            'fa fa-phone'        => 'Phone',
            'fa fa-comments'     => 'Comments',
            'fa fa-camera'       => 'Camera',
            'fa fa-book'         => 'Book',
            'fa fa-check'        => 'Check',
            'fa fa-trash'        => 'Trash',
            'fa fa-sitemap'          => 'Sitemap',
            'fa fa-female'           => 'Female',
            'fa fa-heartbeat'        => 'Heartbeat',
            'fa fa-child'            => 'Child',
            'fa fa-users-cog'        => 'Users Cog',
            'fa fa-hands-helping'    => 'Hands Helping',
            'fa fa-seedling'         => 'Seedling',
            'fa fa-briefcase'        => 'Briefcase',
            'fa fa-shield-alt'       => 'Shield-Alt',
        ];

        return view('pages.admin.manage.menu.create', compact('menus', 'icons'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'icon'             => ['nullable', 'string', 'max:255'],
            'icon_image'       => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
            'route'            => ['nullable', 'string', 'max:255'],
            'parent_id'        => ['nullable', 'integer', 'exists:menus,id'],
            'order'            => ['nullable', 'integer'],
            'permission_name'  => ['nullable', 'string', 'max:255'],
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        // Validasi route
        if (!empty($validated['route']) && !RouteFacade::has($validated['route'])) {
            return back()->withInput()->withErrors([
                'route' => 'Nama route tidak ditemukan di aplikasi.'
            ]);
        }

        DB::beginTransaction();

        try {
            // Upload file icon
            $imagePath = null;

            if ($request->hasFile('icon_image')) {
                $imagePath = $request->file('icon_image')->store('icon_menu', 'public');
            }

            // Simpan menu
            $menu = Menu::create([
                'title'           => trim($validated['title']),
                'icon'            => $validated['icon'] ?? null,
                'icon_image'      => $imagePath,
                'route'           => $validated['route'] ?? null,
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
            DB::rollBack();
            return back()->withInput()->withErrors([
                'general' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return view('pages.admin.manage.menu.show',compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $icons = config('app.menu_icons',[
            'fa fa-home' => 'Home',
            'fa fa-user' => 'User',
            'fa fa-users' => 'Users',
            'fa fa-cog' => 'Settings',
            'fa fa-list' => 'List',
            'fa fa-chart-bar' => 'Chart',
            'fa fa-envelope' => 'Envelope',
            'fa fa-bell' => 'Notification',
            'fa fa-lock' => 'Lock',
            'fa fa-circle' => 'Circle',
            'fa fa-folder-open' => 'Folder Open',
            'fa fa-file'         => 'File',
            'fa fa-database'     => 'Database',
            'fa fa-map-marker'   => 'Map Marker',
            'fa fa-calendar'     => 'Calendar',
            'fa fa-phone'        => 'Phone',
            'fa fa-comments'     => 'Comments',
            'fa fa-camera'       => 'Camera',
            'fa fa-book'         => 'Book',
            'fa fa-check'        => 'Check',
            'fa fa-trash'        => 'Trash',
            'fa fa-sitemap'          => 'Sitemap',
            'fa fa-female'           => 'Female',
            'fa fa-heartbeat'        => 'Heartbeat',
            'fa fa-child'            => 'Child',
            'fa fa-users-cog'        => 'Users Cog',
            'fa fa-hands-helping'    => 'Hands Helping',
            'fa fa-seedling'         => 'Seedling',
            'fa fa-briefcase'        => 'Briefcase',
            'fa fa-shield-alt'       => 'Shield-Alt',
        ]);

        $menus = Menu::where('id', '!=', $menu->id)
                 ->orderBy('title')
                 ->get(['id','title','icon']);

        return view('pages.admin.manage.menu.edit', compact('menu','icons','menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'icon'            => ['nullable', 'string', 'max:255'],
            'route'           => ['nullable', 'string', 'max:255'],
            'parent_id'       => ['nullable', 'integer', 'exists:menus,id'],
            'order'           => ['nullable', 'integer'],
            'permission_name' => ['nullable', 'string', 'max:255'],
            'icon_image'      => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:1024'],
        ]);

        if (!empty($validated['parent_id']) && (int) $validated['parent_id'] === (int) $menu->id) {
            return back()->withInput()->withErrors(['parent_id' => 'Parent tidak boleh item yang sama.']);
        }

        if (!empty($validated['route']) && !RouteFacade::has($validated['route'])) {
            return back()->withInput()->withErrors(['route' => 'Nama route tidak ditemukan di aplikasi.']);
        }

        $validated['order'] = $validated['order'] ?? 0;

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

            if ($request->hasFile('icon_image')) {

                // hapus file lama jika ada
                if ($menu->icon_image && Storage::disk('public')->exists($menu->icon_image)) {
                    Storage::disk('public')->delete($menu->icon_image);
                }

                // simpan file baru
                $validated['icon_image'] = $request->file('icon_image')->store('icon_menu', 'public');
            }

            // === Update menu ===
            $menu->update([
                'title'           => trim($validated['title']),
                'icon'            => $validated['icon'] ?? null,
                'route'           => $validated['route'] ?? null,
                'parent_id'       => $validated['parent_id'] ?? null,
                'order'           => $validated['order'],
                'permission_name' => $validated['permission_name'] ?? null,
                'icon_image'      => $validated['icon_image'] ?? $menu->icon_image,
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

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
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
