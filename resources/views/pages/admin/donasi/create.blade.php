@extends('layouts.app')

@section('title', 'Tambah Donasi')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"> Donasi</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Tambah Donasi</h5>
                <small class="text-muted">Donasi</small>
            </div>
            <a href="{{ route('donasi.index') }}" class="btn btn-secondary rounded-pill d-flex align-items-center">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @include('pages.admin.donasi._form', [
            'action' => route('donasi.store'),
            'isEdit' => false,
            'item' => null,
            'submitLabel' => 'Simpan'
        ])

    </div>
</div>

<div class="modal fade" id="modalKirimWA" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Donasi Berhasil Disimpan
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <p>
                    Data donasi berhasil disimpan.
                </p>

                <p>
                    Kirim notifikasi WhatsApp ke:
                </p>

                <div class="alert alert-primary mb-0">
                    <strong id="waNama"></strong><br>
                    <span id="waHp"></span>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Nanti
                </button>

                <button type="button"
                        id="btnKirimWA"
                        class="btn btn-success">
                    <i class="fab fa-whatsapp me-1"></i>
                    Kirim WA
                </button>

            </div>

        </div>
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
$(function() {

    $('#btnCariDonatur').on('click', function() {

        let nomorKode = $('#nomor_kode').val();

        if (!nomorKode) {
            alert('Masukkan nomor kode donatur');
            return;
        }

        $.get("{{ route('donatur.cari') }}", {
            nomor_kode: nomorKode
        }, function(res) {

            if (!res.success) {
                alert('Donatur tidak ditemukan');
                return;
            }

            let d = res.data;

            $('#donatur_id').val(d.id);

            $('#detail_kode').text(d.nomor_kode);
            $('#detail_nama').text(d.nama);
            $('#detail_hp').text(d.no_hp ?? '-');

            $('#detail_alamat').text(
                (d.alamat ?? '') + ' ' +
                (d.dukuh ?? '')
            );

            $('#detail_gang').text(
                d.gang ? 'Gang ' + d.gang : '-'
            );

            $('#detail_status').html(
                d.status === 'aktif'
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-secondary">Nonaktif</span>'
            );

            $('#cardDonatur').show();

        });

    });

});
</script>

<script>
$(document).ready(function() {

    $('#program_id').on('change', function() {

        let selected = $(this).find(':selected');

        if ($(this).val() === '') {
            $('#programDetail').addClass('d-none');
            return;
        }

        let nama       = selected.data('nama');
        let deskripsi  = selected.data('deskripsi');
        let jenis      = selected.data('jenis');
        let targetDana = selected.data('target');

        $('#detailNama').text(nama);
        $('#detailDeskripsi').text(deskripsi ?? '-');

        if (jenis === 'target') {
            $('#detailJenis').html(
                '<span class="badge bg-success">Target Dana</span>'
            );

            $('#detailTarget').text(targetDana);
            $('#targetDanaContainer').removeClass('d-none');
        } else {
            $('#detailJenis').html(
                '<span class="badge bg-info">Sukarela</span>'
            );

            $('#targetDanaContainer').addClass('d-none');
        }

        $('#programDetail').removeClass('d-none');
    });

});
</script>

<script>
    $('#formDonasi').on('submit', function(e){
        if($('#donatur_id').val() === ''){
            e.preventDefault();
            alert('Silakan cari dan pilih donatur terlebih dahulu.');
            return false;
        }

        $('#btnSimpan')
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
    });
</script>

@if(session('show_wa_modal'))
<script>
$(function(){

    let data = @json(session('wa_data'));

    let pesan = data.pesan;
//     let pesan =
// `Assalamu'alaikum ${data.nama},

// Terima kasih telah berdonasi pada program *${data.program}*.

// Nominal Donasi : Rp ${data.nominal}
// Periode : ${data.periode}

// Semoga Allah membalas dengan pahala yang berlipat ganda. Aamiin.`;

    let hp = data.hp.replace(/^0/, '62');

    $('#waNama').text(data.nama);
    $('#waHp').text(data.hp);

    let modal = new bootstrap.Modal(
        document.getElementById('modalKirimWA')
    );

    modal.show();

    $('#btnKirimWA').on('click', function(){

        let btn = $(this);

        btn.prop('disabled', true);

        $.ajax({
            url: '/admin/transaksi/donasi/' + data.id + '/wa-terkirim',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res){

                if(res.success){

                    let waUrl =
                        'https://wa.me/' + hp +
                        '?text=' + encodeURIComponent(pesan);

                    window.open(waUrl, '_blank');

                    modal.hide();
                }
            },
            error: function(){
                alert('Gagal mengupdate status WA.');
                btn.prop('disabled', false);
            }
        });

    });

});
</script>
@endif

@endpush
