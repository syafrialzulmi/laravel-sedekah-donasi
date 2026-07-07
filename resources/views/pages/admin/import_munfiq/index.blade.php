@extends('layouts.app')

@section('title', 'Import Munfiq')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Munfiq /</span> Import Data
    </h4>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Validation Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan :</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <h5 class="card-title mb-1">Import Data Munfiq</h5>
            <small class="text-muted">
                Upload file Excel (.xlsx atau .xls). Semua sheet akan diproses secara otomatis.
            </small>
        </div>

        <form action="{{ route('import.munfiq') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">
                        File Excel <span class="text-danger">*</span>
                    </label>

                    <input
                        type="file"
                        class="form-control @error('file') is-invalid @enderror"
                        name="file"
                        accept=".xlsx,.xls"
                        required>

                    @error('file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Format yang didukung:
                        <strong>.xlsx</strong> dan <strong>.xls</strong>
                    </div>
                </div>

                <div class="alert alert-info mb-0">
                    <strong>Informasi :</strong>
                    <ul class="mb-0 mt-2">
                        <li>File harus memiliki format sesuai template.</li>
                        <li>Header berada pada baris ke-6.</li>
                        <li>Data mulai dari baris ke-7.</li>
                        <li>Semua sheet (Gang 1, Gang 2, dst) akan diimport.</li>
                    </ul>
                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-upload"></i>
                    Import Excel
                </button>
            </div>

        </form>

    </div>

</div>

@endsection

@push('styles')
@endpush

@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function () {

    const btn = this.querySelector('button[type="submit"]');

    btn.disabled = true;

    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Sedang Import...
    `;
});
</script>
@endpush