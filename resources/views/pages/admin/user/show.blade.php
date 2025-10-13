@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Pengguna</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Detail Pengguna</h5>
                <small class="text-muted">Pengguna sistem.</small>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Header mini dengan avatar --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                    style="width:56px;height:56px;">
                    <i class="fa-solid fa-user text-muted"></i>
                </div>
                <div>
                    <div class="h5 mb-0">{{ $user->name }}</div>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                </div>

                {{-- Detail --}}
                <div class="row g-3">
                <div class="col-md-3 col-4 fw-semibold text-muted">Name</div>
                <div class="col-md-9 col-8">
                    <span class="badge bg-label-primary px-3 py-2 fs-6">{{ $user->name }}</span>
                </div>

                <div class="col-md-3 col-4 fw-semibold text-muted">Email</div>
                <div class="col-md-9 col-8">
                    <span class="text-body">{{ $user->email }}</span>
                </div>

                <div class="col-md-3 col-4 fw-semibold text-muted">Roles</div>
                <div class="col-md-9 col-8">
                    @php $roles = $user->getRoleNames(); @endphp
                    @if($roles && $roles->count())
                    <div class="border rounded p-2" style="max-height: 220px; overflow:auto;">
                        <div class="d-flex flex-wrap gap-2">
                        @foreach($roles as $r)
                            {{-- gunakan bg-primary-subtle jika tema mendukung; fallback: bg-primary --}}
                            <span class="badge rounded-pill bg-primary-subtle text-primary border">{{ $r }}</span>
                        @endforeach
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        {{ $roles->count() }} role{{ $roles->count() > 1 ? 's' : '' }}
                    </small>
                    @else
                    <span class="text-muted">Pengguna ini belum memiliki role.</span>
                    @endif
                </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('users.edit',$user->id) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                </a>
            </div>
            </div>


    </div>
</div>


<div class="row">

</div>
@endsection
