<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body{
            font-family: DejaVu Sans;
            font-size:10px;
        }

        h2,h4{
            text-align:center;
            margin:0;
        }

        .info{
            margin:15px 0;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:5px;
        }

        th{
            background:#efefef;
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .text-center{
            text-align:center;
        }
    </style>

</head>

<body>

<h2>LAPORAN DONASI</h2>
<h4>{{ config('app.name') }}</h4>

<div class="info">

    @if($tanggalAwal || $tanggalAkhir)
        <strong>Periode :</strong>
        {{ $tanggalAwal ?: '-' }}
        s/d
        {{ $tanggalAkhir ?: '-' }}
        <br>
    @endif

    @if($program)
        <strong>Program Sedekah :</strong> {{ $program->nama_program }}<br>
    @endif

    @if($gang)
        <strong>Gang :</strong> {{ $gang }}<br>
    @endif

    @php
    $bulan = [
        1=>'Januari',
        2=>'Februari',
        3=>'Maret',
        4=>'April',
        5=>'Mei',
        6=>'Juni',
        7=>'Juli',
        8=>'Agustus',
        9=>'September',
        10=>'Oktober',
        11=>'November',
        12=>'Desember'
    ];
    @endphp

</div>

<table>

    <thead>

    <tr>
        <th width="35">No</th>
        <th width="90">Tanggal</th>
        <th width="110">Kode</th>
        <th>Donatur</th>
        <th width="70">Gang</th>
        <th>Program</th>
        <th width="100">Periode</th>
        <th width="120">Nominal</th>
    </tr>

    </thead>

    <tbody>

    @php($total = 0)

    @forelse($data as $item)

        @php($total += $item->nominal)

        <tr>

            <td class="text-center">
                {{ $loop->iteration }}
            </td>

            <td class="text-center">
                {{ \Carbon\Carbon::parse($item->tanggal_donasi)->format('d-m-Y') }}
            </td>

            <td>
                {{ $item->donatur->nomor_kode ?? '-' }}
            </td>

            <td>
                {{ $item->donatur->nama ?? '-' }}
            </td>

            <td class="text-center">
                {{ $item->donatur->gang ?? '-' }}
            </td>

            <td>
                {{ $item->program->nama_program ?? '-' }}
            </td>

            <td class="text-center">
                {{ $bulan[$item->bulan] ?? '-' }} {{ $item->tahun }}
            </td>

            <td class="text-right">
                {{ number_format($item->nominal,0,',','.') }}
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="8" class="text-center">
                Tidak ada data.
            </td>
        </tr>

    @endforelse

    </tbody>

    <tfoot>

    <tr>

        <th colspan="7" class="text-right">
            TOTAL
        </th>

        <th class="text-right">
            {{ number_format($total,0,',','.') }}
        </th>

    </tr>

    </tfoot>

</table>

</body>
</html>