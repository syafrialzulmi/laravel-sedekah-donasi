@extends('layouts.app')

@section('title', 'Laporan Donasi')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">Laporan Donasi</h4>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0">Laporan Donasi</h5>
                <small class="text-muted">Daftar Laporan Donasi.</small>
            </div>
            {{-- <form action="{{ route('backup.database') }}"
                method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin melakukan backup database?')">

                @csrf

                <button type="submit"
                        class="btn btn-success rounded-pill">
                    <i class="fa-solid fa-database me-1"></i>
                    Backup Database
                </button>

            </form> --}}
        </div>

        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET" class="row g-3 mb-3">

                    <div class="col-md-3">
                        <label class="form-label">Program Sedekah</label>

                        <select name="program_id"
                                id="program_id"
                                class="form-select">

                            @foreach($programs as $program)
                                <option value="{{ $program->id }}"
                                    {{ $programId == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Tahun</label>

                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>

                            @foreach($years as $item)
                                <option value="{{ $item }}"
                                    {{ $tahun == $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Gang</label>
                        <select name="gang" class="form-select">
                            <option value="">Semua Gang</option>
                            @for($i = 1; $i <= 40; $i++)
                                <option value="{{ $i }}"
                                    {{ request('gang') == $i ? 'selected' : '' }}>
                                    Gang {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-5 d-flex align-items-end justify-content-end gap-2">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Tampilkan
                        </button>

                        <a href="{{ route('laporan-donasi.index') }}"
                        class="btn btn-secondary">
                            <i class="fa fa-rotate-left me-1"></i>
                            Reset
                        </a>

                        <a href="{{ route('laporan-donasi.print', request()->query()) }}"
                        target="_blank"
                        class="btn btn-danger">
                            <i class="fa fa-file-pdf me-1"></i>
                            Cetak
                        </a>

                        <button type="button"
                                class="btn btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRekapBulanan">
                            <i class="fa fa-table me-1"></i>
                            Rekap Bulanan
                        </button>

                    </div>

                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor Kode</th>
                                <th>Nama Donatur</th>

                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Agu</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>

                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $totalBulanan = [];

                                for($i = 1; $i <= 12; $i++) {
                                    $totalBulanan[$i] = 0;
                                }

                                $grandTotalSemua = 0;
                            @endphp
                            @forelse($donaturList as $index => $donatur)
                                @php
                                    $grandTotal = 0;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $donatur->nomor_kode }}</td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $donatur->nama }}
                                        </div>

                                        <small class="text-muted">
                                            Gang {{ $donatur->gang }}
                                            &nbsp;&nbsp;|&nbsp;&nbsp;
                                            {{ $donatur->no_hp }}
                                        </small>
                                    </td>
                                    @for($bulan = 1; $bulan <= 12; $bulan++)
                                        @php
                                            $nominal = $pivot[$donatur->id][$bulan] ?? 0;
                                            $grandTotal += $nominal;
                                            $totalBulanan[$bulan] += $nominal;
                                        @endphp

                                        <td class="text-end">
                                            {{ $nominal > 0 ? number_format($nominal,0,',','.') : '-' }}
                                        </td>
                                    @endfor
                                    @php
                                        $grandTotalSemua += $grandTotal;
                                    @endphp
                                    <td class="text-end fw-bold bg-light">
                                        {{ number_format($grandTotal,0,',','.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-center">
                                    TOTAL
                                </th>
                                @for($bulan = 1; $bulan <= 12; $bulan++)
                                    <th class="text-end">
                                        {{ number_format($totalBulanan[$bulan], 0, ',', '.') }}
                                    </th>
                                @endfor
                                <th class="text-end">
                                    {{ number_format($grandTotalSemua, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
     id="modalRekapBulanan"
     tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Rekap Donasi Bulanan per Gang
                </h5>

                <div class="d-flex gap-2">

                    <a href="{{ route('laporan-donasi.print-rekap', request()->query()) }}"
                    target="_blank"
                    class="btn btn-danger btn-sm">
                        <i class="fa fa-file-pdf me-1"></i>
                        Cetak PDF
                    </a>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>
            </div>

            <div class="modal-body">

                <div class="table-responsive">

                    @php
                    $gangs = [];

                    for($i=1;$i<=11;$i++){
                        $gangs[] = $i;
                    }

                    $gangs[] = '12A';
                    $gangs[] = '12B';

                    for($i=13;$i<=32;$i++){
                        $gangs[] = $i;
                    }
                    @endphp

                    <table class="table table-bordered table-sm">

                        <thead class="table-light">

                            <tr>
                                <th>Bulan</th>

                                @foreach($gangs as $gang)
                                    <th>G{{ $gang }}</th>
                                @endforeach

                                <th>Total</th>
                            </tr>

                        </thead>

                        <tbody>

                        @php

                        $namaBulan = [
                            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',
                            5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',
                            9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'
                        ];

                        $totalGang = [];

                        foreach($gangs as $gang){
                            $totalGang[$gang] = 0;
                        }

                        $grandTotal = 0;

                        @endphp

                        @for($bulan=1;$bulan<=12;$bulan++)

                            @php
                                $totalPerBulan=0;
                            @endphp

                            <tr>

                                <td>{{ $namaBulan[$bulan] }}</td>

                                @foreach($gangs as $gang)

                                    @php
                                        $nominal = $rekap[$bulan][$gang] ?? 0;

                                        $totalPerBulan += $nominal;
                                        $totalGang[$gang] += $nominal;
                                    @endphp

                                    <td class="text-end">
                                        {{ $nominal ? number_format($nominal,0,',','.') : '-' }}
                                    </td>

                                @endforeach

                                @php
                                    $grandTotal += $totalPerBulan;
                                @endphp

                                <td class="text-end fw-bold">
                                    {{ number_format($totalPerBulan,0,',','.') }}
                                </td>

                            </tr>

                        @endfor

                        </tbody>

                        <tfoot class="table-light">

                            <tr>

                                <th>Total</th>

                                @foreach($gangs as $gang)

                                    <th class="text-end">
                                        {{ number_format($totalGang[$gang],0,',','.') }}
                                    </th>

                                @endforeach

                                <th class="text-end">
                                    {{ number_format($grandTotal,0,',','.') }}
                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection