@extends('layouts.app')

@section('title', 'Show Products')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master/</span> Product</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Show Product</h5>
                <small class="text-muted">Detail produk</small>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                <div class="col-md-3 col-4 fw-semibold text-muted">Name</div>
                <div class="col-md-9 col-8">
                    <span class="badge bg-label-primary px-3 py-2 fs-6">{{ $product->name }}</span>
                </div>
                </div>

                <div class="row mb-3">
                <div class="col-md-3 col-4 fw-semibold text-muted">Details</div>
                <div class="col-md-9 col-8">
                    <p class="mb-0">{{ $product->detail }}</p>
                </div>
                </div>
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary me-2">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                </a>

            </div>
            </div>

    </div>
</div>

@endsection
