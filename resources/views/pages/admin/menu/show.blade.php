@extends('layouts.app')

@section('title', 'Show Menu')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Menu</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Show Menu</h5>
                <small class="text-muted">Detail menu</small>
            </div>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Title --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Judul</div>
                    <div class="col-md-9 col-8">
                        <span class="badge bg-label-primary px-3 py-2 fs-6">{{ $menu->title }}</span>
                    </div>
                </div>

                {{-- Icon --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Icon</div>
                    <div class="col-md-9 col-8">
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i> <span>{{ $menu->icon }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                {{-- Route --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Route</div>
                    <div class="col-md-9 col-8">
                        {{ $menu->route ?? '-' }}
                    </div>
                </div>

                {{-- Parent --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Parent</div>
                    <div class="col-md-9 col-8">
                        {{ $menu->parent ? $menu->parent->title : '-' }}
                    </div>
                </div>

                {{-- Order --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Urutan</div>
                    <div class="col-md-9 col-8">
                        {{ $menu->order }}
                    </div>
                </div>

                {{-- Permission --}}
                <div class="row mb-3">
                    <div class="col-md-3 col-4 fw-semibold text-muted">Permission</div>
                    <div class="col-md-9 col-8">
                        {{ $menu->permission_name ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-primary me-2">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                </a>
                <a href="{{ route('menus.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            </div>

    </div>
</div>

@endsection
