@extends('layouts.app')

@section('title', 'Hak Akses')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Hak Akses</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0">Hak Akses</h5>
            <small class="text-muted">Daftar hak akses sistem.</small>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center">
            <i class="bx bx-plus me-1"></i> Tambah
        </a>
        </div>

        <div class="card">
        <div class="card-body">

            {{-- Toolbar: server-side filter --}}
            <form id="filterForm" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <div class="input-group" style="max-width: 420px;">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="form-control"
                placeholder="Cari role...">
                @if(request()->filled('q'))
                <a href="{{ route('roles.index', ['ps' => request('ps', 5)]) }}" class="btn btn-light">Reset</a>
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

            @if ($roles->count())
            <div class="table-responsive mb-3 tableFixHead">
                <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                    <th style="width:72px;">No</th>
                    <th>Name</th>
                    <th>Guard Name</th>
                    <th style="width:200px;">Action</th>
                    </tr>
                </thead>
                <tbody id="roleTableBody">
                @foreach ($roles as $role)
                    <tr class="role-row">
                    <td>{{ $roles->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $role->name }}</td>
                    <td class="text-muted">{{ $role->guard_name }}</td>
                    <td>
                        <div class="btn-group">
                        <a class="btn btn-outline-secondary btn-sm" href="{{ route('roles.show',$role->id) }}">
                            <i class="fa-solid fa-list"></i>
                        </a>
                        @can('role-edit')
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('roles.edit',$role->id) }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        @endcan
                        @can('role-delete')
                            <button type="button"
                            class="btn btn-outline-danger btn-sm btn-open-delete"
                            data-url="{{ route('roles.destroy',$role->id) }}"
                            data-name="{{ $role->name }}"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmDeleteModal">
                            <i class="fa-solid fa-trash"></i>
                            </button>
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
                Menampilkan {{ $roles->firstItem() }}–{{ $roles->lastItem() }} dari {{ $roles->total() }}
                @if(request()->filled('q')) • hasil untuk: “{{ request('q') }}” @endif
                </small>
                {!! $roles->appends(request()->only('ps','q'))->links('pagination::bootstrap-5') !!}
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
                <h5 class="mb-1">Belum ada role</h5>
                <p class="text-muted mb-3">Buat role baru untuk mulai mengatur akses.</p>
                <a href="{{ route('roles.create') }}" class="btn btn-primary rounded-pill">
                <i class="bx bx-plus me-1"></i> Tambah Role
                </a>
            </div>
            @endif

        </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Delete --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="deleteForm" class="modal-content">
      @csrf
      @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Hapus Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">Anda yakin ingin menghapus role berikut?</p>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <strong id="deleteItemName">—</strong>
        </div>
        <small class="text-muted d-block mt-2">Tindakan ini tidak dapat dibatalkan.</small>
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

@push('styles')
<style>
  /* Sticky header saat daftar panjang */
  .tableFixHead { max-height: 65vh; overflow: auto; }
  .tableFixHead thead th { position: sticky; top: 0; z-index: 2; }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    // Submit GET saat ubah page size
    $('#pageSize').on('change', function () {
      $('#filterForm').trigger('submit');
    });

    // Tekan Enter di kolom cari -> submit GET
    $('input[name="q"]').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        $('#filterForm').trigger('submit');
      }
    });

    // Isi modal delete
    $(document).on('click', '.btn-open-delete', function () {
      const url  = $(this).data('url');
      const name = $(this).data('name');
      $('#deleteForm').attr('action', url);
      $('#deleteItemName').text(name);
    });
  });
</script>
@endpush
@endsection
