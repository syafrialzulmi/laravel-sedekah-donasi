@extends('layouts.app')

@section('title', 'Tambah Donatur')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Donatur</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Tambah Donatur</h5>
                <small class="text-muted">Donatur</small>
            </div>
            <a href="{{ route('donatur.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @include('pages.admin.donatur._form', [
            'action' => route('donatur.store'),
            'isEdit' => false,
            'item' => null,
            'submitLabel' => 'Simpan'
        ])
    </div>
</div>
@endsection

@push('styles')
<style>
.select2-results__option .fa,
.select2-selection__rendered .fa {
  margin-right: .5rem;
  width: 1.25rem;
  text-align: center;
  font-size: 1rem;
  line-height: 1;
}

.select2-container--bootstrap4 .select2-selection--single {
  height: calc(2.5rem + 2px);
  padding: .5rem .75rem;
  display: flex;
  align-items: center;
  position: relative;
  border: 1px solid #d9dee3;
  border-radius: 0.375rem;
}
.select2-container--bootstrap4 .select2-selection__rendered {
  padding-left: 0;
  padding-right: 2rem;
  line-height: 1.25;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
}
.select2-container--bootstrap4 .select2-selection__arrow {
  height: 100%;
}
.select2-container--bootstrap4 .select2-selection__clear {
  position: absolute;
  right: .75rem;
  top: 50%;
  transform: translateY(-50%);
  margin-right: 0;
}

.select2-container--bootstrap-5 .select2-selection--single {
  min-height: calc(2.5rem + 2px);
  height: calc(2.5rem + 2px);
  padding: .5rem .75rem;
  display: flex;
  align-items: center;
  position: relative;
}
.select2-container--bootstrap-5 .select2-selection__rendered {
  padding-left: 0;
  padding-right: 2rem;
  line-height: 1.25;
  display: inline-flex;
  align-items: center;
  gap: .5rem;
}
.select2-container--bootstrap-5 .select2-selection__clear {
  position: absolute;
  right: .75rem;
  top: 50%;
  transform: translateY(-50%);
}

.select2-container .select2-results__option {
  display: flex;
  align-items: center;
  gap: .5rem;
}
</style>
@endpush


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
