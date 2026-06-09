@extends('layouts.app')

@section('title', 'Tambah Notifikasi WA')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master /</span> Notifikasi WA</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Tambah Notifikasi WA</h5>
                <small class="text-muted">Notifikasi WA</small>
            </div>
            <a href="{{ route('wa-template.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @include('pages.admin.notifikasi._form', [
            'action' => route('wa-template.store'),
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
$(function () {

    $('#variables').select2({
        theme: 'bootstrap-5', // atau bootstrap4 sesuai project
        placeholder: 'Pilih placeholder...',
        allowClear: true,
        width: '100%',
        closeOnSelect: false
    });

});

$('.btn-placeholder').click(function(){

    let value = $(this).data('value');

    let textarea = document.getElementById('isi');

    let start = textarea.selectionStart;
    let end = textarea.selectionEnd;

    textarea.value =
        textarea.value.substring(0,start) +
        value +
        textarea.value.substring(end);

    textarea.focus();

    textarea.selectionStart = textarea.selectionEnd =
        start + value.length;

});

const templates = {

    donasi_baru: {

        kode: 'DONASI_BARU',

        nama: 'Notifikasi Donasi Baru',

        aktif: 1,

        variables: [
            'nama',
            'program',
            'nominal',
            'periode'
        ],

        isi: `Assalamu'alaikum {nama},

Terima kasih telah berdonasi pada program *{program}*.

Nominal Donasi : Rp {nominal}
Periode : {periode}

Semoga Allah membalas dengan pahala yang berlipat ganda. Aamiin.`

    },

    donasi_update: {

        kode: 'DONASI_UPDATE',

        nama: 'Notifikasi Update Donasi',

        aktif: 1,

        variables: [
            'nama',
            'program',
            'nominal',
            'periode',
            'perubahan'
        ],

        isi: `Assalamu'alaikum {nama},

Data donasi Anda telah diperbarui.

Program : {program}
Nominal : Rp {nominal}
Periode : {periode}

Perubahan:
{perubahan}

Terima kasih atas partisipasi Anda dalam program sedekah kami.`

    },

    donasi_hapus: {

        kode: 'DONASI_HAPUS',

        nama: 'Notifikasi Hapus Donasi',

        aktif: 1,

        variables: [
            'nama',
            'program'
        ],

        isi: `Assalamu'alaikum {nama},

Data donasi pada program *{program}* telah dihapus dari sistem.

Apabila terdapat kekeliruan, silakan menghubungi admin.

Terima kasih.`

    }

};

$('.btn-default-template').click(function () {

    const key = $(this).data('template');

    const template = templates[key];

    if (!template) return;

    if ($('#isi').val().trim() !== '') {

        if (!confirm('Semua isian form akan diganti dengan template default. Lanjutkan?')) {
            return;
        }

    }

    // kode
    $('input[name="kode"]').val(template.kode);

    // nama
    $('input[name="nama_template"]').val(template.nama);

    // status
    $('select[name="aktif"]').val(template.aktif);

    // isi
    $('#isi').val(template.isi);

    // placeholder
    // $('#variables').val(template.variables).trigger('change');

});

</script>


@endpush
