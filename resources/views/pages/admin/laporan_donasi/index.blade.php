@extends('layouts.app')

@section('title', 'Laporan Donasi')

@section('main')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        Laporan Donasi
    </h4>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Program Sekedah</label>

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
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>

                    <select name="tahun" class="form-select">
                        @for($i = date('Y') + 1; $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}"
                                {{ $tahun == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary">
                        <i class="fa fa-search me-1"></i>
                        Tampilkan
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
                                <td>{{ $donatur->nama }}</td>
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

@endsection