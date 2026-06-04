@extends('layouts.app')

@section('title', 'Donasi')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"> Donasi</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="mb-0">Donasi</h5>
            <small class="text-muted">Daftar Donasi.</small>
        </div>
        @can('donasi-create')
          <a href="{{ route('donasi.create') }}" class="btn btn-primary rounded-pill d-flex align-items-center">
              <i class="bx bx-plus me-1"></i> Tambah
          </a>
        @endcan
        </div>

        <div class="card">
            <div class="card-body">

                {{-- Toolbar: server-side filter --}}
                <form method="GET"
                    action="{{ route('donasi.index') }}"
                    class="row g-2 align-items-end mb-3">

                    {{-- Pencarian --}}
                    <div class="col-md-4">
                        <label class="form-label">Pencarian</label>
                        <input type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="form-control"
                            placeholder="Donatur, kode, program, nominal, tahun...">
                    </div>

                    {{-- Tanggal Awal --}}
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date"
                            name="tanggal_awal"
                            value="{{ request('tanggal_awal') }}"
                            class="form-control">
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date"
                            name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="form-control">
                    </div>

                    {{-- Page Size --}}
                    <div class="col-md-1">
                        <label class="form-label">Tampilkan</label>
                        <select name="ps" class="form-select">
                            @php $ps = (int) request('ps', 10); @endphp

                            <option value="5" {{ $ps == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $ps == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $ps == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $ps == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $ps == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa fa-search me-1"></i>
                                Cari
                            </button>

                            <a href="{{ route('donasi.index') }}"
                            class="btn btn-secondary">
                                <i class="fa fa-reload me-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>

                </form>

                

                @if ($data->count())
                <div class="table-responsive mb-3 tableFixHead">
                    <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                                <th width="60">No</th>
                                <th>Donatur</th>
                                <th>Program & Periode</th>
                                <th class="text-end">Nominal</th>
                                <th>Status</th>
                                <th width="140">Action</th>
                            </tr>
                    </thead>
                    <tbody id="productTableBody">
                    @foreach ($data as $item)
                        <tr class="product-row">
                            <td class="py-1">{{ $data->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $item->donatur->nama ?? '-' }}
                                </div>

                                <small class="text-muted">
                                    {{ $item->donatur->nomor_kode ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <div>
                                    {{ $item->program->nama_program ?? '-' }}
                                </div>

                                <small class="text-muted">
                                    {{ $item->periode }}
                                </small>
                            </td>

                            <td class="text-end">
                                <div class="fw-semibold text-success">
                                    {{ $item->nominal_rupiah }}
                                </div>

                                <small class="text-muted">
                                    {{ $item->tanggal_donasi->format('d M Y') }}
                                </small>
                            </td>

                            <td>
                                @if($item->wa_terkirim)
                                    <i class="fa-solid fa-circle-check text-success fs-5" title="WA Terkirim"></i>

                                    <div>
                                        <small class="text-muted">
                                            {{ optional($item->wa_terkirim_at)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                @else
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm btn-wa"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->donatur->nama }}"
                                        data-hp="{{ $item->donatur->no_hp }}"
                                        data-program="{{ $item->program->nama_program }}"
                                        data-nominal="{{ number_format($item->nominal,0,',','.') }}"
                                        data-periode="{{ $item->periode }}">
                                        <i class="fab fa-whatsapp"></i>
                                        Kirim WA
                                    </button>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                {{-- <a class="btn btn-outline-secondary btn-sm" href="{{ route('donasi.show',$item->id) }}">
                                    <i class="fa-solid fa-list"></i>
                                </a> --}}
                                @can('donasi-edit')
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('donasi.edit',$item->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                @endcan
                                @can('donasi-delete')
                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm btn-open-delete"
                                        data-url="{{ route('donasi.destroy',$item->id) }}"
                                        data-name="{{ $item->donatur->nama ?? '-' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                    Menampilkan {{ $data->firstItem() }}-{{ $data->lastItem() }} dari {{ $data->total() }}
                    @if(request()->filled('q')) • hasil untuk: “{{ request('q') }}” @endif
                    </small>
                    {!! $data->appends(request()->only('ps','q'))->links() !!}
                </div>
                @else
                {{-- EMPTY STATE --}}
                <div class="text-center p-5">
                    <div class="mx-auto mb-3" style="width:96px;height:96px;">
                    <svg viewBox="0 0 24 24" width="96" height="96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="14" rx="2" stroke="currentColor" opacity=".2"/>
                        <path d="M7 9h10M7 12h6" stroke="currentColor" stroke-linecap="round" opacity=".4"/>
                        <path d="M8 20h8" stroke="currentColor" stroke-linecap="round" />
                    </svg>
                    </div>
                    <h5 class="mb-1">Belum ada Donasi</h5>
                    <p class="text-muted mb-3">Mulai tambahkan Donasi pertama Anda, untuk muncul di daftar.</p>
                    <a href="{{ route('donasi.create') }}" class="btn btn-primary rounded-pill">
                    <i class="bx bx-plus me-1"></i> Tambah Donasi
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" id="deleteForm" class="modal-content">
      @csrf
      @method('DELETE')
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus produk ini?</p>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <strong id="deleteItemName">Nama produk</strong>
        </div>
        <small class="text-muted d-block mt-2">Tindakan ini tidak bisa dibatalkan.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-danger">
          <i class="fa-solid fa-trash me-1"></i> Hapus
        </button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalWA" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Kirim WhatsApp
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>
                    Kirim laporan donasi kepada:
                </p>

                <div class="alert alert-primary mb-0">
                    <strong id="waNama"></strong><br>
                    <span id="waNomor"></span>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                        class="btn btn-success"
                        id="btnKirimWAFinal">
                    <i class="fab fa-whatsapp me-1"></i>
                    Kirim Sekarang
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
  /* Sticky header untuk daftar panjang */
  .tableFixHead { max-height: 65vh; overflow: auto; }
  .tableFixHead thead th { position: sticky; top: 0; z-index: 2; }
</style>
@endpush

@push('scripts')
<script>
  $(function () {
    // Ubah page size -> submit form (GET)
    $('#pageSize').on('change', function () {
      $('#filterForm').trigger('submit');
    });

    // Tekan Enter di kolom cari -> submit form (GET)
    $('input[name="q"]').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        $('#filterForm').trigger('submit');
      }
    });

    // Isi modal delete
    $(document).on('click', '.btn-open-delete', function () {
      const url  = $(this).data('url');
      const name = $(this).data('name');
      $('#deleteForm').attr('action', url);
      $('#deleteItemName').text(name);
    });
  });
</script>

<script>
$(function(){

    let currentDonasi = null;
    let modalWA = new bootstrap.Modal(
        document.getElementById('modalWA')
    );

    $('.btn-wa').on('click', function(){

        currentDonasi = {
            id      : $(this).data('id'),
            nama    : $(this).data('nama'),
            hp      : $(this).data('hp'),
            program : $(this).data('program'),
            nominal : $(this).data('nominal'),
            periode : $(this).data('periode')
        };

        $('#waNama').text(currentDonasi.nama);
        $('#waNomor').text(currentDonasi.hp);

        modalWA.show();
    });

    $('#btnKirimWAFinal').on('click', function(){

        let hp = currentDonasi.hp.replace(/^0/, '62');

        let pesan =
`Assalamu'alaikum ${currentDonasi.nama},

Terima kasih telah berdonasi pada program *${currentDonasi.program}*.

Nominal Donasi : Rp ${currentDonasi.nominal}
Periode : ${currentDonasi.periode}

Semoga Allah membalas dengan pahala yang berlipat ganda. Aamiin.`;

        $.ajax({
            url : '/admin/transaksi/donasi/' + currentDonasi.id + '/kirim-wa',
            method : 'POST',

            success : function(res){

                if(res.success){

                    window.open(
                        'https://wa.me/' + hp +
                        '?text=' + encodeURIComponent(pesan),
                        '_blank'
                    );

                    location.reload();
                }

            },

            error : function(){
                alert('Gagal mengupdate status WA');
            }
        });

    });

});
</script>
@endpush