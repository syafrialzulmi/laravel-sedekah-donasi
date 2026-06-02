@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manage/</span> Pengaturan Aplikasi</h4>

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
      @can('setting-app-delete')
      @if($setting)
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmClearModal">
          <i class="fa-solid fa-trash-can me-1"></i> Kosongkan Data
        </button>
      @endif
      @endcan
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
          <input type="text" name="name_app" class="form-control form-control-sm" value="{{ old('name_app', $setting->name_app ?? '') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Singkatan Nama Aplikasi <span class="text-danger">*</span></label>
            <input type="text" name="name_app_singkatan"
                    class="form-control form-control-sm"
                    value="{{ old('name_app_singkatan', $setting->name_app_singkatan ?? '') }}" required>
            <small class="text-muted">Contoh: "SIAKAD", "POS", dll.</small>
        </div>

        <div class="col-12">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" rows="4" class="form-control form-control-sm" placeholder="Deskripsi singkat aplikasi">{{ old('deskripsi', $setting->deskripsi ?? '') }}</textarea>
        </div>

        <div class="col-md-4">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.webp,.svg">
          @if($setting?->logo)
            <div class="mt-2">
              <div class="text-muted small">Pratinjau saat ini:</div>
              <img src="{{ asset('storage/' . $setting->logo) }}" alt="logo" class="img-thumbnail" style="max-height:120px">
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Banner</label>
          <input type="file" name="banner" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.webp,.svg">
          @if($setting?->banner)
            <div class="mt-2">
              <div class="text-muted small">Pratinjau saat ini:</div>
              <img src="{{ asset('storage/' . $setting->banner) }}" alt="banner" class="img-thumbnail" style="max-height:120px">
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <label class="form-label">Favicon</label>
          <input type="file" name="favicon" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.webp,.ico,.svg">
          @if($setting?->favicon)
            <div class="mt-2 d-flex align-items-center gap-3">
              <div>
                <div class="text-muted small">Pratinjau saat ini:</div>
                <img src="{{ asset('storage/' . $setting->favicon) }}" alt="favicon" class="img-thumbnail" style="max-height:64px; max-width:64px;">
              </div>
            </div>
          @endif
        </div>

        <div class="col-md-6">
            <label class="form-label">Kecamatan</label>
            <select name="kecamatan_id" id="kecamatan_id" class="form-select form-select-sm">
                <option value="">-- Pilih Kecamatan --</option>
                @foreach($kecamatans as $kecamatan)
                    <option value="{{ $kecamatan->id }}"
                        {{ old('kecamatan_id', $setting->kecamatan_id ?? '') == $kecamatan->id ? 'selected' : '' }}>
                        {{ $kecamatan->kecamatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Desa</label>
            <select name="desa_id" id="desa_id" class="form-select form-select-sm">
                <option value="">-- Pilih Desa --</option>
            </select>
        </div>

        <div class="col-12">
            @if($setting)
                @can('setting-app-edit')
                    <button class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Ubah
                    </button>
                @endcan
            @else
                @can('setting-app-create')
                    <button class="btn btn-success">
                    <i class="fa-solid fa-save me-1"></i> Simpan
                    </button>
                @endcan
            @endif
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Konfirmasi Kosongkan Data --}}
<div class="modal fade" id="confirmClearModal" tabindex="-1" aria-labelledby="confirmClearModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="confirmClearModalLabel">
          <i class="fa-solid fa-triangle-exclamation me-2"></i> Konfirmasi Hapus Data
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">
          Apakah Anda yakin ingin <strong>mengosongkan semua data pengaturan</strong>? <br>
          <span class="text-danger fw-bold">Tindakan ini tidak bisa dibatalkan.</span>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark me-1"></i> Batal
        </button>
        <form action="{{ route('setting-app.clear') }}" method="POST" id="clearForm">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash-can me-1"></i> Ya, Kosongkan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    let selectedDesa = "{{ old('desa_id', $setting->desa_id ?? '') }}";

    function loadDesa(kecamatanId, selected = null) {

        $.get(
            "{{ url('admin/manage/setting-app/desa-by-kecamatan') }}/" + kecamatanId,
            function(response) {

                let options = '<option value="">-- Pilih Desa --</option>';

                $.each(response, function(index, desa) {

                    let selectedAttr =
                        selected == desa.id
                            ? 'selected'
                            : '';

                    options += `
                        <option value="${desa.id}" ${selectedAttr}>
                            ${desa.desa}
                        </option>
                    `;
                });

                $('#desa_id').html(options);
            }
        );
    }

    let kecamatanAwal = $('#kecamatan_id').val();

    if (kecamatanAwal) {
        loadDesa(kecamatanAwal, selectedDesa);
    }

    $('#kecamatan_id').change(function() {
        loadDesa($(this).val());
    });

});
</script>

<script>
$(document).ready(function() {

    $('#kecamatan_id').on('change', function() {

        let kecamatanId = $(this).val();

        $('#desa_id').html(
            '<option value="">Memuat data desa...</option>'
        );

        if (kecamatanId == '') {
            $('#desa_id').html(
                '<option value="">-- Pilih Desa --</option>'
            );
            return;
        }

        $.ajax({
            url: "{{ url('admin/manage/setting-app/desa-by-kecamatan') }}/" + kecamatanId,
            type: "GET",
            dataType: "json",
            success: function(response) {

                let options = '<option value="">-- Pilih Desa --</option>';

                $.each(response, function(index, desa) {
                    options += `
                        <option value="${desa.id}">
                            ${desa.desa}
                        </option>
                    `;
                });

                $('#desa_id').html(options);
            },
            error: function() {
                alert('Gagal mengambil data desa');
            }
        });

    });

});
</script>
@endpush