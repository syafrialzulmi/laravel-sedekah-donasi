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
                {{-- Header dengan avatar --}}
                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-4">
                    <div class="avatar avatar-xl position-relative">
                        @if($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" alt="Foto {{ $user->name }}" class="rounded-circle" style="width:70px;height:70px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                style="width:70px;height:70px;">
                                <i class="fa-solid fa-user fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-muted d-block">{{ $user->email }}</small>
                        <span class="badge bg-label-primary mt-1">{{ $user->roles->implode('name', ', ') ?: 'Tanpa Role' }}</span>
                    </div>
                </div>

                {{-- Detail Informasi --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fa-solid fa-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted small">Nama Lengkap</div>
                                <div class="text-dark small">{{ $user->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fa-solid fa-user text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted small">Username</div>
                                <div class="text-dark small">{{ $user->username }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fa-solid fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted small">Email</div>
                                <div class="text-dark small">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fa-solid fa-user-shield text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted small">Roles</div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach ($user->getRoleNames() as $role)
                                        <span class="badge rounded-pill bg-primary-subtle text-primary border">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="me-2">
                                <i class="fa-solid fa-phone text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-muted small">Nomor HP</div>
                                <div class="text-dark small">{{ $user->no_hp ?: '—' }}</div>
                            </div>
                        </div>
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
