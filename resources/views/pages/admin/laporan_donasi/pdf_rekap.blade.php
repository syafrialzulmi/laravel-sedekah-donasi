<style>
    @page {
        margin: 15px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
    }

    h3 {
        margin: 0 0 10px 0;
        text-align: center;
    }

    p {
        margin: 0 0 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    tr {
        page-break-inside: avoid;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px;
        vertical-align: middle;
    }

    th {
        background: #e9ecef;
        text-align: center;
        font-weight: bold;
    }

    td.text-right {
        text-align: right;
    }

    td.text-center {
        text-align: center;
    }

    .total {
        font-weight: bold;
        background: #f5f5f5;
    }
</style>

@php
    $gangs = [];

    for ($i = 1; $i <= 11; $i++) {
        $gangs[] = $i;
    }

    $gangs[] = '12A';
    $gangs[] = '12B';

    for ($i = 13; $i <= 32; $i++) {
        $gangs[] = $i;
    }

    $namaBulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];

    $totalGang = [];

    foreach ($gangs as $gang) {
        $totalGang[$gang] = 0;
    }

    $grandTotal = 0;
@endphp

<h3>Rekap Donasi Bulanan Per Gang</h3>

<p>
    <strong>Program :</strong> {{ $program->nama_program }}<br>
    <strong>Tahun :</strong> {{ $tahun }}
</p>

<table>

    <thead>

        <tr>
            <th style="width:55px;">Bulan</th>

            @foreach($gangs as $gang)
                <th>G{{ $gang }}</th>
            @endforeach

            <th style="width:70px;">Total</th>
        </tr>

    </thead>

    <tbody>

        @for($bulan = 1; $bulan <= 12; $bulan++)

            @php
                $totalPerBulan = 0;
            @endphp

            <tr>

                <td class="text-center">
                    {{ $namaBulan[$bulan] }}
                </td>

                @foreach($gangs as $gang)

                    @php
                        $nominal = $rekap[$bulan][$gang] ?? 0;

                        $totalPerBulan += $nominal;
                        $totalGang[$gang] += $nominal;
                    @endphp

                    <td class="text-right">
                        {{ $nominal > 0 ? number_format($nominal,0,',','.') : '-' }}
                    </td>

                @endforeach

                @php
                    $grandTotal += $totalPerBulan;
                @endphp

                <td class="text-right total">
                    {{ number_format($totalPerBulan,0,',','.') }}
                </td>

            </tr>

        @endfor

    </tbody>

    <tfoot>

        <tr>

            <th>Total</th>

            @foreach($gangs as $gang)

                <th style="text-align:right;">
                    {{ number_format($totalGang[$gang],0,',','.') }}
                </th>

            @endforeach

            <th style="text-align:right;">
                {{ number_format($grandTotal,0,',','.') }}
            </th>

        </tr>

    </tfoot>

</table>