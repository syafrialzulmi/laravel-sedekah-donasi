@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manage/</span> Pengguna
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Ubah Pengguna</h5>
                <small class="text-muted">Pengguna sistem</small>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @include('pages.admin.manage.user._form', [
            'action' => route('users.update', $user->id),
            'isEdit' => true,
            'user' => $user,
            'roles' => $roles,
            'selectedRole' => old('roles', array_key_first($userRole ?? [])),
            'submitLabel' => 'Simpan Perubahan'
        ])
    </div>
</div>
@endsection
