@extends('layouts.app')

@section('title','Edit Customer')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Customers/</span> Edit</h4>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Edit Customer</h5>
      <a href="{{ route('customers.index') }}" class="btn btn-light">Kembali</a>
    </div>
    <div class="card-body">
      <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf @method('PUT')
        @include('customers._form', ['customer' => $customer])
        <div class="mt-3">
          <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Ubah</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
