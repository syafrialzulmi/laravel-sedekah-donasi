@extends('layouts.app')

@section('title','Tambah Customer')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Customers/</span> Tambah</h4>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Form Customer</h5>
      <a href="{{ route('customers.index') }}" class="btn btn-light">Kembali</a>
    </div>
    <div class="card-body">
      <form action="{{ route('customers.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @include('customers._form', ['customer' => $customer])
        <div class="mt-3">
          <button class="btn btn-success"><i class="fa-solid fa-save me-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
