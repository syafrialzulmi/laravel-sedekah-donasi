@extends('layouts.app')

@section('title', 'Show Roles')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Roles Management</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Show Roles</h5>
                <small class="text-muted">Detail role sistem.</small>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Role name --}}
                <div class="row mb-3">
                <div class="col-md-3 col-4 fw-semibold text-muted">Name</div>
                <div class="col-md-9 col-8">
                    <span class="badge bg-label-primary px-3 py-2 fs-6">{{ $role->name }}</span>
                </div>
                </div>

                {{-- Permissions --}}
                <div class="row">
                <div class="col-md-3 col-4 fw-semibold text-muted">Permissions</div>
                <div class="col-md-9 col-8">
                    @if($rolePermissions && $rolePermissions->count())
                    <div class="border rounded p-2" style="max-height: 260px; overflow:auto;">
                        <div class="d-flex flex-wrap gap-2">
                        @foreach($rolePermissions as $perm)
                            <span class="badge rounded-pill bg-primary-subtle text-primary border">
                            {{ $perm->name }}
                            </span>
                        @endforeach
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        {{ $rolePermissions->count() }} permission{{ $rolePermissions->count() > 1 ? 's' : '' }}
                    </small>
                    @else
                    <span class="text-muted">Tidak ada permission untuk role ini.</span>
                    @endif
                </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                @can('role-edit')
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                </a>
                @endcan
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            </div>

    </div>
</div>

@endsection
