<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: DejaVu Sans;
            font-size: 9px;
        }

        h3 {
            margin: 0;
            text-align: center;
        }

        .header {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .header table {
            border: none;
        }

        .header td {
            border: none;
            padding: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: .5px solid #000;
            padding: 4px;
        }

        thead th {
            background: #e9ecef;
            text-align: center;
        }

        tfoot th {
            background: #e9ecef;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 8px;
            color: #666;
        }

        .bg-light {
            background: #f5f5f5;
        }
    </style>

</head>

<body>

<h3>LAPORAN DONASI</h3>

<div class="header">

    <table>
        <tr>
            <td width="120">Program</td>
            <td>: {{ $program->nama_program ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>: {{ $tahun }}</td>
        </tr>
        <tr>
            <td>Gang</td>
            <td>: {{ $gang ? 'Gang '.$gang : 'Semua Gang' }}</td>
        </tr>
    </table>

</div>

<table>

    <thead>
    <tr>

        <th width="30">No</th>
        <th width="60">Nomor Kode</th>
        <th width="180">Nama Donatur</th>

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

        <th width="80">Total</th>

    </tr>
    </thead>

    <tbody>

    @php
        $totalBulanan = [];

        for($i=1;$i<=12;$i++){
            $totalBulanan[$i]=0;
        }

        $grandTotalSemua = 0;
    @endphp

    @forelse($donaturList as $index => $donatur)

        @php
            $grandTotal = 0;
        @endphp

        <tr>

            <td class="text-center">
                {{ $index+1 }}
            </td>

            <td class="text-center">
                {{ $donatur->nomor_kode }}
            </td>

            <td>

                <div class="fw-bold">
                    {{ $donatur->nama }}
                </div>

                <div class="small">
                    Gang {{ $donatur->gang }}
                    |
                    {{ $donatur->no_hp }}
                </div>

            </td>

            @for($bulan=1;$bulan<=12;$bulan++)

                @php
                    $nominal = $pivot[$donatur->id][$bulan] ?? 0;

                    $grandTotal += $nominal;
                    $totalBulanan[$bulan] += $nominal;
                @endphp

                <td class="text-end">
                    {{ $nominal ? number_format($nominal,0,',','.') : '-' }}
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

    <tfoot>

    <tr>

        <th colspan="3" class="text-center">
            TOTAL
        </th>

        @for($bulan=1;$bulan<=12;$bulan++)
            <th class="text-end">
                {{ number_format($totalBulanan[$bulan],0,',','.') }}
            </th>
        @endfor

        <th class="text-end">
            {{ number_format($grandTotalSemua,0,',','.') }}
        </th>

    </tr>

    </tfoot>

</table>

</body>
</html>