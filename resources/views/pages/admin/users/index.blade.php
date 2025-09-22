@extends('layouts.app')

@section('title', 'Users')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Users Management</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Users Management</h5>
                <small class="text-muted">Daftar pengguna sistem.</small>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center">
                <i class="bx bx-plus me-1"></i> Tambah
            </a>
        </div>
        <div class="card">
            <div class="card-body">

                {{-- Toolbar --}}
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <div class="input-group" style="max-width: 360px;">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" id="userSearch" class="form-control" placeholder="Cari nama, email, role...">
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="text-muted small d-none d-sm-inline">Tampilkan</span>
                    <select id="pageSize" class="form-select form-select-sm" style="width:auto;">
                        <option value="5"  {{ request('ps')==5  ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('ps')==10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('ps')==20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('ps')==50 ? 'selected' : '' }}>50</option>
                    </select>
                    </div>
                </div>

                @if ($data->count())
                    <div class="table-responsive mb-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width:72px;">No</th>
                            <th style="min-width:220px;">Name</th>
                            <th style="min-width:220px;">Email</th>
                            <th>Roles</th>
                            <th style="width:200px;">Action</th>
                        </tr>
                        </thead>
                        <tbody id="userTableBody">
                        @foreach ($data as $key => $user)
                            <tr class="user-row">
                            <td>{{ ++$i }}</td>
                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>
                            <td class="text-muted">
                                <span class="text-truncate d-inline-block" style="max-width: 260px;">{{ $user->email }}</span>
                            </td>
                            <td>
                                @php $roles = $user->getRoleNames(); @endphp
                                @if($roles && $roles->count())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($roles as $r)
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border">{{ $r }}</span>
                                    @endforeach
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                <a class="btn btn-outline-secondary btn-sm" href="{{ route('users.show',$user->id) }}">
                                    <i class="fa-solid fa-list"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('users.edit',$user->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                    <a class="dropdown-item" href="{{ route('users.show',$user->id) }}">
                                        <i class="fa-solid fa-list me-2"></i>Show
                                    </a>
                                    </li>
                                    <li>
                                    <a class="dropdown-item" href="{{ route('users.edit',$user->id) }}">
                                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit
                                    </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa-solid fa-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                    </li>
                                </ul>
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
                        Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }}
                    </small>
                    {!! $data->appends(['ps' => request('ps')])->links('pagination::bootstrap-5') !!}
                    </div>
                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center p-5">
                    <div class="mx-auto mb-3" style="width:96px;height:96px;">
                        <svg viewBox="0 0 24 24" width="96" height="96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" opacity=".3"/>
                        <path d="M4 20c0-3.5 3.6-6 8-6s8 2.5 8 6" stroke="currentColor" opacity=".4"/>
                        </svg>
                    </div>
                    <h5 class="mb-1">Belum ada pengguna</h5>
                    <p class="text-muted mb-3">Tambahkan pengguna baru untuk mulai mengelola akses.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill">
                        <i class="bx bx-plus me-1"></i> Tambah Pengguna
                    </a>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
  $(function () {
    // Client-side search: filter nama/email/roles
    const $search = $('#userSearch');
    const $rows   = $('#userTableBody .user-row');

    function filterRows() {
      const q = ($search.val() || '').toLowerCase().trim();
      if (!q) { $rows.show(); return; }
      $rows.each(function() {
        const txt = $(this).text().toLowerCase();
        $(this).toggle(txt.indexOf(q) !== -1);
      });
    }
    $search.on('input', filterRows);

    // Page size: reload dengan query ps
    $('#pageSize').on('change', function() {
      const ps  = $(this).val();
      const url = new URL(window.location.href);
      url.searchParams.set('ps', ps);
      url.searchParams.delete('page'); // kembali ke halaman 1
      window.location.href = url.toString();
    });
  });
</script>
@endpush

@endsection
