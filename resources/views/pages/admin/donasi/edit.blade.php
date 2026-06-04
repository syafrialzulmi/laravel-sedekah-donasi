@extends('layouts.app')

@section('title', 'Edit Donasi')

@section('main')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        Edit Donasi
    </h4>

    <div class="card">

        <div class="card-header d-flex justify-content-between">

            <div>
                <h5 class="mb-0">Edit Donasi</h5>
                <small class="text-muted">
                    Perubahan data donasi
                </small>
            </div>

            <a href="{{ route('donasi.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

        </div>

        @include('pages.admin.donasi._form', [
            'action' => route('donasi.update', $donasi->id),
            'isEdit' => true,
            'item' => $donasi,
            'submitLabel' => 'Simpan Perubahan'
        ])

    </div>

</div>

<div class="modal fade" id="modalKirimUlangWA" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Kirim Ulang WhatsApp
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>
                    Data donasi berhasil diperbarui.
                </p>

                <div class="alert alert-warning">
                    <strong>Perubahan:</strong>

                    <ul class="mb-0 mt-2" id="listPerubahan"></ul>
                </div>

                <div class="alert alert-primary mb-0">
                    <strong id="waNamaUpdate"></strong><br>
                    <span id="waHpUpdate"></span>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Nanti
                </button>

                <button type="button"
                        id="btnKirimUlangWA"
                        class="btn btn-success">
                    <i class="fab fa-whatsapp me-1"></i>
                    Kirim Ulang WA
                </button>

            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('show_wa_modal_update'))
<script>
$(function(){

    let data = @json(session('wa_update_data'));

    $('#waNamaUpdate').text(data.nama);
    $('#waHpUpdate').text(data.hp);

    let html = '';

    data.changes.forEach(function(item){
        html += '<li>' + item + '</li>';
    });

    $('#listPerubahan').html(html);

    let modal = new bootstrap.Modal(
        document.getElementById('modalKirimUlangWA')
    );

    modal.show();

    $('#btnKirimUlangWA').on('click', function(){

        let perubahan = data.changes
            .map(x => '• ' + x)
            .join('\n');

        let pesan =
`Assalamu'alaikum ${data.nama},

Data donasi Anda telah diperbarui.

Program : ${data.program}
Nominal : Rp ${data.nominal}
Periode : ${data.periode}

Perubahan:
${perubahan}

Terima kasih atas partisipasi Anda dalam program sedekah kami.`;

        let hp = data.hp.replace(/^0/, '62');

        let waUrl =
            'https://wa.me/' + hp +
            '?text=' + encodeURIComponent(pesan);

        window.open(waUrl, '_blank');

        modal.hide();
    });

});
</script>
@endif
@endpush
