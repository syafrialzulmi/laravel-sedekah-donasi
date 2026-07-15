@extends('layouts.app')

@section('title','Review Import')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div>
            <h5>Review Data Import</h5>
            <small>{{ $data->total() }} data ditemukan</small>
        </div>

        <a href="{{ route('import-munfiq.index') }}"
           class="btn btn-secondary">
            Import Ulang
        </a>
    </div>

    <div class="card">
        <div class="card-body border-bottom">
            <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('import-munfiq.review') }}" class="row g-2 mb-3">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Gang</label>
                                <select name="gang"
                                        class="form-select"
                                        onchange="this.form.submit()">
                                    <option value="">-- Semua Gang --</option>
                                    @foreach($gangList as $item)
                                        <option value="{{ $item }}"
                                            {{ request('gang') == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Jenis Kode</label>

                                <select name="kode"
                                        class="form-select"
                                        onchange="this.form.submit()">

                                    <option value="">-- Semua Jenis Kode --</option>

                                    <option value="UL"
                                        {{ request('kode') == 'UL' ? 'selected' : '' }}>
                                        UL
                                    </option>

                                    <option value="ULM"
                                        {{ request('kode') == 'ULM' ? 'selected' : '' }}>
                                        ULM
                                    </option>

                                    <option value="TANPA"
                                        {{ request('kode') == 'TANPA' ? 'selected' : '' }}>
                                        Tanpa Kode
                                    </option>

                                    <option value="INVALID"
                                        {{ request('kode') == 'INVALID' ? 'selected' : '' }}>
                                        Kode Tidak Valid
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Bulan</label>

                                <select name="bulan"
                                        class="form-select"
                                        onchange="this.form.submit()">

                                    <option value="">-- Semua Bulan --</option>

                                    @php
                                        $bulanList = [
                                            'jan'=>'Januari',
                                            'feb'=>'Februari',
                                            'mar'=>'Maret',
                                            'apr'=>'April',
                                            'mei'=>'Mei',
                                            'jun'=>'Juni',
                                            'jul'=>'Juli',
                                            'agt'=>'Agustus',
                                            'sept'=>'September',
                                            'okt'=>'Oktober',
                                            'nov'=>'November',
                                            'des'=>'Desember',
                                        ];
                                    @endphp

                                    @foreach($bulanList as $key=>$value)
                                        <option value="{{ $key }}"
                                            {{ request('bulan')==$key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Cari Nama / Kode</label>

                                <input type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="Masukkan nama atau kode..."
                                    value="{{ request('q') }}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end gap-2">

                                <button class="btn btn-primary">
                                    <i class="bx bx-search"></i>
                                    Cari
                                </button>

                                @if(request()->filled('gang') ||
                                    request()->filled('kode') ||
                                    request()->filled('q'))

                                    <a href="{{ route('import-munfiq.review') }}"
                                    class="btn btn-outline-secondary">
                                        Reset
                                    </a>

                                @endif

                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-4 text-end">
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#dashboardModal">
                        <i class="bx bx-bar-chart"></i>
                        Dashboard
                    </button>

                    <button class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#rekapModal">
                        <i class="bx bx-table"></i>
                        Rekap
                    </button>
                </div>
            </div>

            {{-- cart total nama orang, total orang UL-, total orang ULM-, total tidak mempunyai kode --}}
            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="card border-start border-primary border-4">
                        <div class="card-body">
                            <small class="text-muted">Total Orang</small>
                            <h3 class="mb-0">
                                {{ number_format($statistik['total']) }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-success border-4">
                        <div class="card-body">
                            <small class="text-muted">Kode UL-</small>
                            <h3 class="mb-0 text-success">
                                {{ number_format($statistik['ul']) }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-info border-4">
                        <div class="card-body">
                            <small class="text-muted">Kode ULM-</small>
                            <h3 class="mb-0 text-info">
                                {{ number_format($statistik['ulm']) }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-danger border-4">
                        <div class="card-body">
                            <small class="text-muted">Belum Memiliki Kode</small>
                            <h3 class="mb-0 text-danger">
                                {{ number_format($statistik['tanpa_kode']) }}
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            {{-- card total nominal, total nomial UL-, total nominal ULM-, total nomila tidak mempunyai kode --}}
            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="card border-start border-primary border-4">
                        <div class="card-body">
                            <small class="text-muted">Total Nominal</small>
                            <h5 class="mb-0 text-primary">
                                Rp {{ number_format($nominal['total'], 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-success border-4">
                        <div class="card-body">
                            <small class="text-muted">Nominal UL-</small>
                            <h5 class="mb-0 text-success">
                                Rp {{ number_format($nominal['ul'], 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-info border-4">
                        <div class="card-body">
                            <small class="text-muted">Nominal ULM-</small>
                            <h5 class="mb-0 text-info">
                                Rp {{ number_format($nominal['ulm'], 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-start border-danger border-4">
                        <div class="card-body">
                            <small class="text-muted">Nominal Tanpa Kode</small>
                            <h5 class="mb-0 text-danger">
                                Rp {{ number_format($nominal['tanpa_kode'], 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>

            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <h6 class="alert-heading mb-2">
                        <i class="bx bx-error-circle me-1"></i>
                        Gagal menyimpan data
                    </h6>

                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th width="80">Aksi</th>
                        <th>Gang</th>
                        {{-- <th>No</th> --}}
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        @if($selectedBulan)
                            <th>{{ $bulanList[$selectedBulan] }}</th>
                        @else
                            @foreach($bulanList as $field => $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>

                            <button
                                class="btn btn-sm btn-warning btn-edit"

                                data-id="{{ $item->id }}"
                                data-sheet="{{ $item->sheet_name }}"
                                data-kode="{{ $item->kode }}"
                                data-nama="{{ $item->nama }}"
                                data-hp="{{ $item->no_hp }}"

                                data-bs-toggle="modal"
                                data-bs-target="#editModal">

                                <i class="bx bx-edit"></i>

                            </button>

                        </td>
                        <td>{{ $item->sheet_name }}</td>
                        {{-- <td>{{ $item->no }}</td> --}}
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->no_hp }}</td>

                        @if($selectedBulan)
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        {{ $item->bulan ? number_format($item->bulan) : '-' }}
                                    </span>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary btn-edit-nominal"
                                        data-id="{{ $item->id }}"
                                        data-bulan="{{ $selectedBulan }}"
                                        data-label="{{ $bulanList[$selectedBulan] }}"
                                        data-nominal="{{ $item->bulan }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editNominalModal">
                                        <i class="bx bx-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        @else
                            @foreach($bulanList as $field => $label)
                                <td>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            {{ $item->$field ? number_format($item->$field) : '-' }}
                                        </span>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-edit-nominal"
                                            data-id="{{ $item->id }}"
                                            data-bulan="{{ $field }}"
                                            data-label="{{ $label }}"
                                            data-nominal="{{ $item->$field }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editNominalModal">
                                            <i class="bx bx-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            @endforeach
                        @endif
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">

                <div class="row align-items-center">

                    {{-- Pagination --}}
                    <div class="row">
                        <div class="col-md-6 mb-2 mb-md-0">
                            {{ $data->links() }}
                        </div>
                    </div>

                    {{-- Sinkronisasi --}}
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            {{-- Kosong atau informasi lain --}}
                        </div>

                        <div class="col-md-6">

                            <div class="d-flex justify-content-end gap-2 flex-wrap">

                                {{-- Tombol Hapus Data Import --}}
                                <form action="{{ route('import-munfiq.truncate') }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus seluruh data hasil import?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger"
                                            {{ $statistik['total'] == 0 ? 'disabled' : '' }}>
                                        <i class="bx bx-trash me-1"></i>
                                        Hapus Data Import
                                    </button>

                                </form>

                                {{-- Tombol Sinkronisasi --}}
                                @if($statistik['tanpa_kode'] == 0)

                                    <form action="{{ route('import-munfiq.sync') }}"
                                        method="POST"
                                        onsubmit="return confirm('Sinkronkan data ke tabel Donatur dan Donasi?')">

                                        @csrf

                                        <div class="d-flex align-items-center gap-2">

                                            <select name="tahun"
                                                    class="form-select"
                                                    style="width:140px"
                                                    required>

                                                <option value="">Pilih Tahun</option>

                                                @for($tahun=2020;$tahun<=date('Y')+5;$tahun++)
                                                    <option value="{{ $tahun }}"
                                                        {{ $tahun==date('Y') ? 'selected':'' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endfor

                                            </select>

                                            <button type="submit" class="btn btn-success">
                                                <i class="bx bx-refresh me-1"></i>
                                                Sinkronkan Data
                                            </button>

                                        </div>

                                    </form>

                                @else

                                    <div class="alert alert-warning d-inline-flex align-items-start text-start mb-0">

                                        <i class="bx bx-error-circle fs-4 me-2 mt-1"></i>

                                        <div>

                                            <strong>Sinkronisasi Belum Dapat Dilakukan</strong>

                                            <div class="small mt-1">

                                                Ditemukan
                                                <strong>{{ number_format($statistik['tanpa_kode']) }}</strong>
                                                donatur yang belum memiliki kode.

                                                <br>

                                                Lengkapi terlebih dahulu data tersebut sebelum melakukan sinkronisasi.

                                            </div>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade"
     id="dashboardModal"
     tabindex="-1">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">
                    Dashboard Hasil Import
                </h4>
                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                @foreach($dashboard as $gang => $item)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            {{ $gang }}
                        </h5>
                    </div>

                    <div class="card-body">
                        {{-- ========================= --}}
                        {{-- JUMLAH ORANG --}}
                        {{-- ========================= --}}
                        <h6 class="fw-bold mb-3">
                            Jumlah Orang
                        </h6>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card border-start border-primary border-4">
                                    <div class="card-body">
                                        <small>Total</small>
                                        <h3>{{ number_format($item['summary']['orang']['total']) }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-success border-4">
                                    <div class="card-body">
                                        <small>UL-</small>
                                        <h3>{{ number_format($item['summary']['orang']['ul']) }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-info border-4">
                                    <div class="card-body">
                                        <small>ULM-</small>
                                        <h3>{{ number_format($item['summary']['orang']['ulm']) }}</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-danger border-4">
                                    <div class="card-body">
                                        <small>Tanpa Kode</small>
                                        <h3>{{ number_format($item['summary']['orang']['tanpa']) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ========================= --}}
                        {{-- TOTAL NOMINAL --}}
                        {{-- ========================= --}}

                        <h6 class="fw-bold mb-3">
                            Total Nominal Setahun
                        </h6>

                        <div class="row mb-4">

                            <div class="col-md-3">
                                <div class="card border-start border-primary border-4">
                                    <div class="card-body">
                                        <small>Total</small>
                                        <h5>Rp {{ number_format($item['summary']['nominal']['total'],0,',','.') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-success border-4">
                                    <div class="card-body">
                                        <small>UL-</small>
                                        <h5>Rp {{ number_format($item['summary']['nominal']['ul'],0,',','.') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-info border-4">
                                    <div class="card-body">
                                        <small>ULM-</small>
                                        <h5>Rp {{ number_format($item['summary']['nominal']['ulm'],0,',','.') }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card border-start border-danger border-4">
                                    <div class="card-body">
                                        <small>Tanpa Kode</small>
                                        <h5>Rp {{ number_format($item['summary']['nominal']['tanpa'],0,',','.') }}</h5>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- ========================= --}}
                        {{-- PER BULAN --}}
                        {{-- ========================= --}}

                        <h6 class="fw-bold mb-3">
                            Nominal Per Bulan
                        </h6>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bulan</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">UL-</th>
                                        <th class="text-end">ULM-</th>
                                        <th class="text-end">Tanpa Kode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item['bulan'] as $bulan=>$nominal)
                                    <tr>
                                        <td class="text-uppercase">
                                            {{ $bulan }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($nominal['total'],0,',','.') }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($nominal['ul'],0,',','.') }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($nominal['ulm'],0,',','.') }}
                                        </td>

                                        <td class="text-end">
                                            {{ number_format($nominal['tanpa'],0,',','.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<div class="modal fade"
     id="rekapModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">
                    Rekap Nominal Bulanan
                </h4>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                {{-- TAB BULAN --}}
                <ul class="nav nav-tabs mb-3" id="rekapTab" role="tablist">

                    @foreach($rekap as $key => $bulan)

                        <li class="nav-item" role="presentation">

                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{ $key }}"
                                    type="button">

                                {{ $bulan['title'] }}

                            </button>

                        </li>

                    @endforeach

                </ul>

                {{-- CONTENT --}}
                <div class="tab-content">

                    @foreach($rekap as $key => $bulan)

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="{{ $key }}">

                        <div class="card shadow-sm">

                            <div class="card-header bg-success text-white">

                                <h5 class="mb-0">

                                    Bulan {{ $bulan['title'] }}

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover align-middle">

                                        <thead class="table-light">

                                            <tr>

                                                <th width="120">Gang</th>

                                                <th class="text-end">
                                                    Total
                                                </th>

                                                <th class="text-end">
                                                    Total UL
                                                </th>

                                                <th class="text-end">
                                                    Total ULM
                                                </th>

                                                <th class="text-end">
                                                    Tanpa Kode
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach($bulan['rows'] as $row)

                                            <tr>

                                                <td>
                                                    {{ $row['gang'] }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($row['total'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($row['ul'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($row['ulm'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($row['tanpa'],0,',','.') }}
                                                </td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                        <tfoot>

                                            <tr class="table-primary fw-bold">

                                                <td>
                                                    Total Semua
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($bulan['grand_total']['total'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($bulan['grand_total']['ul'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($bulan['grand_total']['ulm'],0,',','.') }}
                                                </td>

                                                <td class="text-end">
                                                    {{ number_format($bulan['grand_total']['tanpa'],0,',','.') }}
                                                </td>

                                            </tr>

                                        </tfoot>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <form method="POST"
              id="formEdit">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Data Import</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            Gang
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="sheet_name"
                            id="sheet_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Kode
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="kode"
                            id="kode"
                            placeholder="Contoh : UL-0001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Nama
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="nama"
                            id="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            No HP
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="no_hp"
                            id="no_hp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button
                        class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade"
     id="editNominalModal"
     tabindex="-1">

    <div class="modal-dialog">

        <form method="POST" id="formNominal">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">

                    <h5>
                        Edit Nominal
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Bulan
                        </label>

                        <input
                            type="text"
                            id="bulanLabel"
                            class="form-control"
                            readonly>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nominal
                        </label>

                        <input
                            type="number"
                            min="0"
                            class="form-control"
                            id="nominal"
                            name="nominal">

                    </div>

                    <input
                        type="hidden"
                        id="bulan"
                        name="bulan">

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-danger"
                        id="btnKosongkan">

                        <i class="bx bx-eraser me-1"></i>
                        Kosongkan

                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bx bx-save me-1"></i>
                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')

<script>

document.querySelectorAll('.btn-edit').forEach(function(btn){

    btn.addEventListener('click', function(){

        let id=this.dataset.id;

        document.getElementById('sheet_name').value=this.dataset.sheet;

        document.getElementById('kode').value=this.dataset.kode;

        document.getElementById('nama').value=this.dataset.nama;

        document.getElementById('no_hp').value=this.dataset.hp;

        document.getElementById('formEdit').action=
            "/admin/transaksi/import-munfiq/"+id;

    });

});

</script>

<script>

document.querySelectorAll('.btn-edit-nominal').forEach(function(btn){

    btn.addEventListener('click', function(){

        document.getElementById('bulan').value=this.dataset.bulan;

        document.getElementById('bulanLabel').value=this.dataset.label;

        document.getElementById('nominal').value=this.dataset.nominal ?? '';

        document.getElementById('formNominal').action=
            "/admin/transaksi/import-munfiq/"+this.dataset.id+"/nominal";

    });

});

document.getElementById('btnKosongkan').addEventListener('click', function () {

    if (!confirm('Kosongkan nominal bulan ini?')) {
        return;
    }

    document.getElementById('nominal').value = '';

    document.getElementById('formNominal').submit();

});

</script>

@endpush