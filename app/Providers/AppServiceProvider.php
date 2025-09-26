<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('components.sidebar', function ($view) {
            $userId = auth()->id() ?: 0;

            $tree = Cache::remember("menu.tree.user.$userId", now()->addMinutes(10), function () {
                // Ambil semua top-level + anak-anaknya (2 tingkat; tambah 'children.children' kalau perlu 3 tingkat)
                $tops = Menu::with([
                        'children' => fn($q) => $q->orderBy('order')
                            ->with(['children' => fn($qq) => $qq->orderBy('order')])
                    ])
                    ->whereNull('parent_id')
                    ->orderBy('order')
                    ->get();

                // PRUNE/FILTER: izin & kosong
                $user = auth()->user();
                $prune = function ($nodes) use (&$prune, $user) {
                    return $nodes->map(function ($n) use ($prune, $user) {
                            // prune anak-anak dulu
                            $n->setRelation('children', $prune($n->children));

                            // cek izin (kalau permission_name diisi dan user tidak boleh, node dipotong
                            $allowed = true;
                            if ($n->permission_name && method_exists($user, 'can')) {
                                $allowed = $user?->can($n->permission_name);
                            }

                            // node tetap jika diizinkan atau punya anak yang lolos
                            $n->visible = $allowed || $n->children->isNotEmpty();

                            return $n;
                        })
                        ->filter(fn($n) => $n->visible)
                        ->values();
                };

                return $prune($tops);
            });

            $view->with('menuTree', $tree);
        });
    }
}
