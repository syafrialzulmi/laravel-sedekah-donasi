<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  {{-- Brand/header tetap pakai punyamu --}}
  @include('components.sidebar-brand')

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    {{-- contoh item statis pertama (opsional) --}}
    <li class="menu-item {{ request()->routeIs('dasbor') ? 'active' : '' }}">
      <a href="{{ route('dasbor') }}" class="menu-link">
        <i class="menu-icon tf-icons fa fa-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- dinamis dari DB --}}
    @foreach ($menuTree as $node)
      @include('components.sidebar-node', ['node' => $node])
    @endforeach
  </ul>
</aside>
<!-- / Menu -->
