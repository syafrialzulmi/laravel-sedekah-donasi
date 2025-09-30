@extends('layouts.app')

@section('title', 'Setting App')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Setting App</h4>

  {{-- Alerts --}}
  {{-- @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
  @endif --}}
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div>
        <h5 class="mb-0">Pengaturan Aplikasi</h5>
        <small class="text-muted">Isi konfigurasi umum aplikasi Anda.</small>
      </div>

      {{-- Tombol Kosongkan Data (muncul hanya jika data ada) --}}
      @if($setting)
        <form action="{{ route('setting-app.clear') }}" method="POST" onsubmit="return confirm('Kosongkan semua data pengaturan? Tindakan ini tidak bisa dibatalkan.')">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">
            <i class="fa-solid fa-trash-can me-1"></i> Kosongkan Data
          </button>
        </form>
      @endif
    </div>

    <div class="card-body">
      @php
        $action = $setting
          ? route('setting-app.update', $setting->id)
          : route('setting-app.store');
        $method = $setting ? 'PUT' : 'POST';
      @endphp

      <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf
        @if($setting)
          @method('PUT')
        @endif

        <div class="col-md-6">
          <label class="form-label">Nama Aplikasi <span class="text-danger">*</span></label>
          <input type="text" name="name_app" class="form-control" value="{{ old('name_app', $setting->name_app ?? '') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Singkatan Nama Aplikasi</label>
            <input type="text" name="name_app_singkatan"
                    class="form-control"
                    value="{{ old('name_app_singkatan', $setting->name_app_singkatan ?? '') }}">
            <small class="text-muted">Contoh: "SIAKAD", "POS", dll.</small>
        </div>

        <div class="col-12">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" rows="4" class="form-control" placeholder="Deskripsi singkat aplikasi">{{ old('deskripsi', $setting->deskripsi ?? '') }}</textarea>
        </div>

        <div class="col-md-4">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-control" accept=".png,.jpg,.jpeg,.webp,.svg">
          @if($setting?->logo_url)
            <div class="mt-2">
              <div class="text-muted small">Pratinjau saat ini:</div>
              <img src="{{ $setting->logo_url }}" alt="logo" class="img-thumbnail" style="max-height:120px">
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Banner</label>
          <input type="file" name="banner" class="form-control" accept=".png,.jpg,.jpeg,.webp,.svg">
          @if($setting?->banner_url)
            <div class="mt-2">
              <div class="text-muted small">Pratinjau saat ini:</div>
              <img src="{{ $setting->banner_url }}" alt="banner" class="img-thumbnail" style="max-height:120px">
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Favicon</label>
          <input type="file" name="favicon" class="form-control" accept=".png,.jpg,.jpeg,.webp,.ico,.svg">
          @if($setting?->favicon_url)
            <div class="mt-2 d-flex align-items-center gap-3">
              <div>
                <div class="text-muted small">Pratinjau saat ini:</div>
                <img src="{{ $setting->favicon_url }}" alt="favicon" class="img-thumbnail" style="max-height:64px; max-width:64px;">
              </div>
            </div>
          @endif
        </div>

        <div class="col-12">
          @if($setting)
            <button class="btn btn-primary">
              <i class="fa-solid fa-floppy-disk me-1"></i> Ubah
            </button>
          @else
            <button class="btn btn-success">
              <i class="fa-solid fa-save me-1"></i> Simpan
            </button>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
