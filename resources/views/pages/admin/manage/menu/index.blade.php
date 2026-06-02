@extends('layouts.app')

@section('title', 'Menu')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Menu</h4>

  <div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div>
        <h5 class="mb-0">Menu</h5>
        <small class="text-muted">Daftar menu sistem</small>
      </div>
      @can('menu-create')
        <a href="{{ route('menus.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center">
            <i class="bx bx-plus me-1"></i> Tambah
        </a>
      @endcan
    </div>

    <div class="card">
      <div class="card-body">

        {{-- Toolbar: server-side filter --}}
        <form id="filterForm" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
          <div class="input-group input-group-sm" style="max-width: 420px;">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input
              type="text"
              name="q"
              value="{{ request('q') }}"
              class="form-control form-control-sm"
              placeholder="Cari menu (judul, parent, route)...">
            @if(request()->filled('q'))
              <a href="{{ route('menus.index', ['ps' => request('ps', 5)]) }}" class="btn btn-light">Reset</a>
            @endif
          </div>

          <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-sm-inline">Tampilkan</span>
            <select name="ps" id="pageSize" class="form-select form-select-sm" style="width:auto;">
              @php $ps = (int) request('ps', 5); @endphp
              <option value="5"  {{ $ps===5  ? 'selected' : '' }}>5</option>
              <option value="10" {{ $ps===10 ? 'selected' : '' }}>10</option>
              <option value="20" {{ $ps===20 ? 'selected' : '' }}>20</option>
              <option value="50" {{ $ps===50 ? 'selected' : '' }}>50</option>
            </select>
          </div>
        </form>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mt-2" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-list" type="button" role="tab">
              Daftar
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tree" type="button" role="tab">
              Hirarki
            </button>
          </li>
        </ul>

        <div class="tab-content pt-3">

          {{-- TAB: DAFTAR (tabel lama) --}}
          <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
            @if ($menus->count())
              {{-- Table with sticky header --}}
              <div class="table-responsive mb-3 tableFixHead">
                <table class="table table-sm table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-nowrap">
                          <th style="width:60px;">No</th>
                          <th>Title</th>
                          <th>Parent</th>
                          <th>Route</th>
                          <th>Icon</th>
                          <th style="width:150px;">Action</th>
                        </tr>
                  </thead>
                  <tbody>
                  @foreach ($menus as $menu)
                    <tr>
                      <td class="py-1">{{ $menus->firstItem() + $loop->index }}</td>
                      <td class="fw-semibold py-1">{{ $menu->title }}</td>
                      <td class="py-1">{{ $menu->parent?->title ?? '-' }}</td>
                      <td class="py-1">    
                          @if($menu->route)
                              <span class="text-dark">
                                  {{ $menu->route }}
                              </span>
                          @endif
                          @if($menu->permission_name)
                              <small class="text-muted">
                                  {{ $menu->permission_name }}
                              </small>
                          @endif
                      </td>                      
                      <td class="py-1">
                            @if ($menu->icon_image)
                                {{-- Jika menu menggunakan gambar ikon --}}
                                <img src="{{ asset('storage/' . $menu->icon_image) }}"
                                    alt="icon"
                                    class="img-thumbnail"
                                    style="height: 32px; width: 32px; object-fit: contain;">
                            @elseif ($menu->icon)
                                {{-- Jika pakai FontAwesome --}}
                                <i class="{{ $menu->icon }} fs-4"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                      <td class="py-1">
                        <div class="btn-group">
                          <a class="btn btn-outline-secondary btn-sm" href="{{ route('menus.show', $menu->id) }}">
                            <i class="fa-solid fa-list"></i>
                          </a>
                          @can('menu-edit')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('menus.edit', $menu->id) }}">
                              <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                          @endcan
                          @can('menu-delete')
                            @if($menu->children->isEmpty())
                                <button type="button"
                                class="btn btn-outline-danger btn-sm btn-open-delete"
                                data-url="{{ route('menus.destroy', $menu->id) }}"
                                data-name="{{ $menu->title }}"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmDeleteModal">
                                <i class="fa-solid fa-trash"></i>
                                </button>
                            @else
                                <button type="button"
                                class="btn btn-outline-secondary btn-sm"
                                disabled
                                title="Menu ini memiliki sub-menu dan tidak bisa dihapus">
                                <i class="fa-solid fa-ban"></i>
                                </button>
                            @endif
                          @endcan
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>

              {{-- Pagination --}}
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  Menampilkan {{ $menus->firstItem() }}–{{ $menus->lastItem() }} dari {{ $menus->total() }}
                  @if(request()->filled('q')) • hasil untuk: “{{ request('q') }}” @endif
                </small>
                {!! $menus->appends(request()->only('ps','q'))->links() !!}
              </div>
            @else
              {{-- EMPTY STATE --}}
              <div class="text-center p-5">
                <div class="mx-auto mb-3" style="width:96px;height:96px;">
                  <svg viewBox="0 0 24 24" width="96" height="96" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="14" rx="2" stroke="currentColor" opacity=".2"/>
                    <path d="M7 9h10M7 12h6" stroke="currentColor" stroke-linecap="round" opacity=".4"/>
                    <path d="M8 20h8" stroke="currentColor" stroke-linecap="round" />
                  </svg>
                </div>
                <h5 class="mb-1">Belum ada menu</h5>
                <p class="text-muted mb-3">Mulai tambahkan menu pertama Anda untuk muncul di daftar.</p>
                <a href="{{ route('menus.create') }}" class="btn btn-primary rounded-pill">
                  <i class="bx bx-plus me-1"></i> Tambah Menu
                </a>
              </div>
            @endif
          </div>

          {{-- TAB: HIRARKI --}}
          <div class="tab-pane fade" id="tab-tree" role="tabpanel">
            @if($roots->count())
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="text-muted small">
                  {{ request()->filled('q') ? 'Hasil hirarki untuk pencarian.' : 'Semua menu dalam bentuk hirarki.' }}
                </div>
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-light" id="expandAll"><i class="fa-solid fa-plus"></i> Buka Semua</button>
                  <button class="btn btn-light" id="collapseAll"><i class="fa-solid fa-minus"></i> Tutup Semua</button>
                </div>
              </div>

              <x-menus.tree :nodes="$roots" :level="0" />
            @else
              <div class="text-center text-muted py-4">
                Tidak ada data untuk ditampilkan dalam hirarki.
              </div>
            @endif
          </div>

        </div>

      </div>
    </div>
  </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="deleteForm" class="modal-content">
      @csrf
      @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus menu ini?</p>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <strong id="deleteItemName">Nama menu</strong>
        </div>
        <small class="text-muted d-block mt-2">Tindakan ini tidak bisa dibatalkan.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">
          <i class="fa-solid fa-trash me-1"></i> Hapus
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('styles')
<style>
  /* Sticky header ketika daftar panjang */
  .tableFixHead { max-height: 65vh; overflow: auto; }
  .tableFixHead thead th { position: sticky; top: 0; z-index: 2; }

  /* Tree connector */
  .border-start { border-left: 1px dashed rgba(0,0,0,.2)!important; }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    // Submit saat page size berubah
    $('#pageSize').on('change', function () {
      $('#filterForm').trigger('submit');
    });

    // Submit saat tekan Enter di kolom cari
    $('input[name="q"]').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        $('#filterForm').trigger('submit');
      }
    });

    // Isi modal delete (delegasi klik)
    $(document).on('click', '.btn-open-delete', function () {
      const url  = $(this).data('url');
      const name = $(this).data('name');
      $('#deleteForm').attr('action', url);
      $('#deleteItemName').text(name);
    });

    // Expand/Collapse all (hirarki)
    $('#expandAll').on('click', function () {
      $('#tab-tree .collapse').collapse('show');
    });
    $('#collapseAll').on('click', function () {
      $('#tab-tree .collapse').collapse('hide');
    });
  });
</script>
@endpush
