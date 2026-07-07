<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Donasi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body {
            background: #f5f7fb;
        }

        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
        }

        .icon-box {
            width:60px;
            height:60px;
            border-radius:15px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
            color:#fff;
        }

        .bg-blue{
            background:#0d6efd;
        }

        .bg-green{
            background:#198754;
        }

        .table-card{
            border:none;
            border-radius:18px;
            box-shadow:0 5px 20px rgba(0,0,0,.08);
        }

        #chart{
            min-height:350px;
        }

        h2{
            font-weight:700;
        }

        .currency{
            font-size:28px;
            font-weight:bold;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Donasi</h2>
            <p class="text-muted mb-0">
                Statistik Donasi Tahun {{ now()->year }}
            </p>
        </div>

        <div>
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn btn-success">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            @endguest
        </div>
    </div>

    <div class="row g-4 mb-4">

        <!-- Donatur -->

        <div class="col-lg-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="icon-box bg-blue">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div class="ms-3">

                            <div class="text-muted">
                                Total Donatur
                            </div>

                            <div class="display-6 fw-bold">
                                {{ number_format($totalDonatur) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Donasi -->

        <div class="col-lg-6">

            <div class="card stat-card">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div class="icon-box bg-green">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div class="ms-3">

                            <div class="text-muted">
                                Total Donasi
                            </div>

                            <div class="currency text-success">
                                Rp {{ number_format($totalDonasi,0,',','.') }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        <!-- Chart -->

        <div class="col-lg-8">

            <div class="card table-card">

                <div class="card-header bg-white border-0 pt-4">
                    <h5>Grafik Donasi Tahun {{ now()->year }}</h5>
                </div>

                <div class="card-body">

                    <div id="chart"></div>

                </div>

            </div>

        </div>


        <!-- Tabel -->

        <div class="col-lg-4">

            <div class="card table-card">

                <div class="card-header bg-white border-0 pt-4">
                    <h5>Donasi per Program</h5>
                </div>

                <div class="card-body p-0">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                        <tr>
                            <th>Program</th>
                            <th class="text-end">Total</th>
                        </tr>

                        </thead>

                        <tbody>

                        @forelse($donasiPerProgram as $program)

                            <tr>

                                <td>
                                    {{ $program->nama_program }}
                                </td>

                                <td class="text-end fw-semibold text-success">
                                    Rp {{ number_format($program->total_donasi,0,',','.') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="2" class="text-center py-4 text-muted">
                                    Belum ada data
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

var options = {

    chart: {
        type: 'area',
        height: 350,
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

    stroke:{
        curve:'smooth',
        width:3
    },

    fill:{
        opacity:.3
    },

    dataLabels:{
        enabled:false
    },

    colors:['#0d6efd'],

    yaxis:{
        labels:{
            formatter:function(value){
                return 'Rp '+new Intl.NumberFormat('id-ID').format(value);
            }
        }
    },

    tooltip:{
        y:{
            formatter:function(value){
                return 'Rp '+new Intl.NumberFormat('id-ID').format(value);
            }
        }
    }

};

new ApexCharts(document.querySelector("#chart"), options).render();

</script>

</body>
</html>