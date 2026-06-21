@extends('layouts.app')

@section('title', 'Dasbor')

@section('main')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">

        {{-- Total Donatur --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-4 d-flex align-items-center justify-content-center me-3"
                        style="width:70px;height:70px;background:#4F7CF3;">
                        <i class="fa-solid fa-users text-white fs-2"></i>
                    </div>

                    <div>
                        <small class="text-muted">Total Donatur</small>

                        <h4 class="fw-bold mb-1">
                            {{ number_format($totalDonatur) }}
                        </h4>

                        <small class="text-success">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            Donatur terdaftar
                        </small>
                    </div>

                </div>
            </div>
        </div>

        {{-- Total Donasi --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-4 d-flex align-items-center justify-content-center me-3"
                        style="width:70px;height:70px;background:#35B67A;">
                        <i class="fa-solid fa-hand-holding-heart text-white fs-2"></i>
                    </div>

                    <div>
                        <small class="text-muted">Total Donasi</small>

                        <h4 class="fw-bold mb-1">
                            Rp {{ number_format($totalDonasi,0,',','.') }}
                        </h4>

                        <small class="text-success">
                            <i class="fa-solid fa-circle-check"></i>
                            Seluruh Program
                        </small>
                    </div>

                </div>
            </div>
        </div>

        @foreach($donasiPerProgram as $program)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-4 d-flex align-items-center justify-content-center me-3"
                        style="width:70px;height:70px;background:#7A4DDF;">
                        <i class="fa-solid fa-hand-holding-heart text-white fs-2"></i>
                    </div>

                    <div>
                        <small class="text-muted">
                            Total Donasi <strong>{{ $program->nama_program }}</strong>
                        </small>

                        <h4 class="fw-bold mb-1">
                            Rp {{ number_format($program->total_donasi,0,',','.') }}
                        </h4>

                        <small class="text-success">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                            Program {{ $program->nama_program }}
                        </small>
                    </div>

                </div>
            </div>
        </div>
        @endforeach



    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        Grafik Donasi Tahun {{ date('Y') }}
                    </h5>
                </div>

                <div class="card-body">
                    <div id="chartDonasi"></div>
                </div>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        Donasi Terbaru
                    </h5>
                </div>

                <div class="list-group list-group-flush">
                    @foreach($latestDonasi as $item)
                    <div class="list-group-item">
                        @php
                            $namaBulan = \Carbon\Carbon::create()
                                ->month($item->bulan)
                                ->locale('id')
                                ->translatedFormat('F');
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold fs-6">
                                    {{ $item->donatur->nama }}
                                </div>

                                <div class="text-muted small">
                                    <i class="fa-solid fa-hand-holding-heart text-success me-1"></i>
                                    {{ $item->program->nama_program }}

                                    <span class="mx-1">•</span>

                                    <i class="fa-regular fa-calendar me-1"></i>
                                    {{ $namaBulan }} {{ $item->tahun }}
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="fw-bold text-success">
                                    Rp {{ number_format($item->nominal,0,',','.') }}
                                </div>

                                <small class="text-muted">
                                    {{ $item->tanggal_donasi->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

@endsection

@push('scripts')
<script>

var options = {

    chart: {
        height: 380,
        type: 'area',
        toolbar:{
            show:false
        }
    },

    series: [{
        name: 'Donasi',
        data: @json($series)
    }],

    xaxis: {
        categories: @json($labels)
    },

    stroke: {
        curve:'smooth',
        width:4
    },

    fill:{
        type:'gradient',
        gradient:{
            opacityFrom:0.5,
            opacityTo:0.05
        }
    },

    dataLabels:{
        enabled:false
    },

    colors:['#28C76F'],

    yaxis:{
        labels:{
            formatter:function(value){
                return 'Rp ' + value.toLocaleString('id-ID');
            }
        }
    },

    tooltip:{
        y:{
            formatter:function(value){
                return 'Rp ' + value.toLocaleString('id-ID');
            }
        }
    }

};

new ApexCharts(
    document.querySelector("#chartDonasi"),
    options
).render();

</script>

@endpush
