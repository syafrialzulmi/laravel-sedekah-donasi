@php
  $hasChildren = $node->children && $node->children->count() > 0;

  // aktif jika route sendiri match
  $selfActive = $node->route ? request()->routeIs($node->route . '*') : false;

  // aktif jika ada anak aktif (1 tingkat; tambah loop dalam jika multi-tingkat)
  $childActive = $hasChildren
      ? $node->children->contains(function($c){
          $self = $c->route ? request()->routeIs($c->route . '*') : false;
          $kid  = $c->children && $c->children->contains(fn($gc) => $gc->route ? request()->routeIs($gc->route . '*') : false);
          return $self || $kid;
        })
      : false;

  $isActive = $selfActive || $childActive;
@endphp

<li class="menu-item {{ $isActive ? 'active open' : '' }}">
  @if($hasChildren)
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons {{ $node->icon }}"></i>
      <div>{{ $node->title }}</div>
    </a>

    <ul class="menu-sub">
      @foreach($node->children as $child)
        {{-- anak sebagai leaf atau branch lagi --}}
        @include('components.sidebar-node', ['node' => $child])
      @endforeach
    </ul>
  @else
    <a href="{{ $node->link() ?: 'javascript:void(0);' }}" class="menu-link">
      {{-- <i class="menu-icon tf-icons {{ $node->icon }}"></i> --}}
      <div>{{ $node->title }}</div>
    </a>
  @endif
</li>
