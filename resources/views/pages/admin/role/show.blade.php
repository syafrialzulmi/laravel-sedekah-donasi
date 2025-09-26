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

                {{-- Permissions (group by menu) --}}
                <div class="row">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Permissions</div>

                    <div class="col-md-9 col-8">
                        @if(isset($groups) && $groups->isNotEmpty())
                        <div class="row g-3">
                            @foreach($groups as $menuId => $perms)
                            @php
                                $menuTitle = optional($perms->first()->menu)->title ?? 'Tanpa Menu';
                            @endphp

                            <div class="col-12">
                                <div class="card border shadow-sm-sm">
                                <div class="card-header d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-bars-staggered text-muted"></i>
                                    <span class="fw-semibold">{{ $menuTitle }}</span>
                                    </div>
                                    <span class="badge bg-secondary">{{ $perms->count() }} item</span>
                                </div>
                                <div class="card-body py-3">
                                    <div class="d-flex flex-wrap gap-2">
                                    @foreach($perms as $perm)
                                        <span class="badge rounded-pill bg-primary-subtle text-primary border">
                                        {{ $perm->name }}
                                        </span>
                                    @endforeach
                                    </div>
                                </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <small class="text-muted d-block mt-3">
                            Total: {{ $rolePermissions->count() }} permission{{ $rolePermissions->count() > 1 ? 's' : '' }}
                        </small>
                        @else
                        <span class="text-muted">Tidak ada permission untuk role ini.</span>
                        @endif
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

@push('styles')
<style>
  .shadow-sm-sm { box-shadow: 0 1px 8px rgba(0,0,0,.04); }
  .bg-primary-subtle { background-color: var(--bs-primary-bg-subtle, rgba(var(--bs-primary-rgb), .08)); }
</style>
@endpush

@endsection
