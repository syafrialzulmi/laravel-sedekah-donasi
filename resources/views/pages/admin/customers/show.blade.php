@extends('layouts.app')

@section('title','Detail Customer')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Customers/</span> Detail</h4>

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Detail Customer</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-primary btn-sm">
          <i class="fa-solid fa-pen-to-square"></i> Edit
        </a>
        <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm">Kembali</a>
      </div>
    </div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Nama</dt>
        <dd class="col-sm-9">{{ $customer->name }}</dd>

        <dt class="col-sm-3">Telepon</dt>
        <dd class="col-sm-9">{{ $customer->phone ?? '-' }}</dd>

        <dt class="col-sm-3">Email</dt>
        <dd class="col-sm-9">{{ $customer->email ?? '-' }}</dd>

        <dt class="col-sm-3">Alamat</dt>
        <dd class="col-sm-9">{{ $customer->address ?? '-' }}</dd>

        <dt class="col-sm-3">Dibuat</dt>
        <dd class="col-sm-9">{{ $customer->created_at?->format('d M Y H:i') }}</dd>

        <dt class="col-sm-3">Diubah</dt>
        <dd class="col-sm-9">{{ $customer->updated_at?->format('d M Y H:i') }}</dd>
      </dl>
    </div>
  </div>
</div>
@endsection
