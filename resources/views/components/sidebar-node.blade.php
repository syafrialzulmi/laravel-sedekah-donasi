{{-- =========================================================
   HELPER: DETEKSI CHILD VALID
   ========================================================= --}}
@php
if (!function_exists('nodeHasValidChildren')) {
    function nodeHasValidChildren($node) {
        if (!$node->children || $node->children->isEmpty()) {
            return false;
        }

        foreach ($node->children as $child) {
            $hasChildren = $child->children && $child->children->isNotEmpty();
            $hasRoute = !empty($child->route);

            // Jika child valid → parent valid
            if ($hasChildren || $hasRoute) {
                return true;
            }
        }

        return false; // Semua child tidak valid → parent tidak valid
    }
}
@endphp


{{-- =========================================================
   HELPER: DETEKSI ACTIVE STATE
   ========================================================= --}}
@php
if (!function_exists('nodeIsActive')) {
    function nodeIsActive($node, $level = 0) {
        $routePrefix = $node->route
            ? preg_replace('/\.index$/', '', $node->route)
            : null;

        // Cek match diri sendiri
        $self = false;
        if ($routePrefix) {
            $self =
                request()->routeIs($routePrefix) ||
                request()->routeIs($routePrefix . '.*');
        }

        // Jika tidak punya children → return self saja
        if (!$node->children || $node->children->isEmpty()) {
            return $self;
        }

        // Jika punya child → cek child aktif
        foreach ($node->children as $child) {
            if (nodeIsActive($child, $level + 1)) {
                return true;
            }
        }

        return $self;
    }
}
@endphp


{{-- =========================================================
   LOGIKA NODE UTAMA
   ========================================================= --}}
@php
$level = $level ?? 0;

$hasChildren   = $node->children && $node->children->isNotEmpty();
$validChildren = nodeHasValidChildren($node);
$hasRoute      = !empty($node->route);

// Tentukan apakah node aktif
$isActive = nodeIsActive($node);

// SKIP node jika:
// - Tidak punya route
// - Tidak punya children valid
if (!$hasRoute && !$validChildren) {
    return; // STOP render node ini
}
@endphp


{{-- =========================================================
   DEBUG: Muncul hanya saat APP_DEBUG=true
   ========================================================= --}}
{{-- @if(env('APP_DEBUG'))
<pre style="font-size:11px; background:#111; color:#0f0; padding:5px; margin:3px 0;">
LEVEL: {{ $level }}
TITLE: {{ $node->title }}
ROUTE: {{ $node->route ?? 'NULL' }}

HAS CHILDREN: {{ $hasChildren ? 'YES' : 'NO' }}
VALID CHILDREN: {{ $validChildren ? 'YES' : 'NO' }}
CHILD COUNT: {{ $node->children?->count() ?? 0 }}

ACTIVE NODE: {{ $isActive ? 'YES' : 'NO' }}

CURRENT ROUTE: {{ request()->route()->getName() }}
</pre>
@endif --}}


{{-- =========================================================
   RENDER ITEM MENU
   ========================================================= --}}
<li class="menu-item {{ $hasChildren ? 'has-children' : 'leaf' }} {{ $isActive ? 'active open' : '' }}">

    @if($hasChildren)
        {{-- Parent item --}}
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            {{-- <i class="menu-icon tf-icons {{ $node->icon }}"></i> --}}
            {{-- TAMPILKAN GAMBAR JIKA ADA --}}
            @if ($node->icon_image)
                <img src="{{ asset('storage/' . $node->icon_image) }}"
                    alt="icon"
                    class="menu-icon"
                    style="width: 22px; height: 22px; object-fit: contain; margin-right: 10px;">

            {{-- TAMPILKAN FONT AWESOME IKON --}}
            @elseif ($node->icon)
                <i class="menu-icon tf-icons {{ $node->icon }}"></i>

            {{-- FALLBACK --}}
            @else
                <i class="menu-icon tf-icons fa-solid fa-circle-question"></i>
            @endif

            <div>{{ $node->title }}</div>
        </a>

        <ul class="menu-sub">
            @foreach($node->children as $child)
                @include('components.sidebar-node', [
                    'node'  => $child,
                    'level' => $level + 1
                ])
            @endforeach
        </ul>

    @else
        {{-- Leaf item --}}
        <a href="{{ $node->link() ?: 'javascript:void(0);' }}" class="menu-link">
            <div>{{ $node->title }}</div>
        </a>
    @endif
</li>
