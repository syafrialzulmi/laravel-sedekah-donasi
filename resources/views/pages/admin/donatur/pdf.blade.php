<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Donatur</title>

    <style>
        body{
            font-family: DejaVu Sans;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:6px;
        }

        table th{
            background:#eee;
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }
    </style>

</head>
<body>

<h2>DATA DONATUR</h2>

<table>

    <thead>
        <tr>
            <th>No</th>
            <th>Nomor Kode</th>
            <th>Nama</th>
            <th>Nomor HP</th>
            <th>Gang</th>
            <th>Alamat</th>
        </tr>
    </thead>

    <tbody>

    @foreach($donatur as $item)

    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->nomor_kode }}</td>
        <td>{{ $item->nama }}</td>
        <td>{{ $item->no_hp }}</td>
        <td>Gang {{ $item->gang }}</td>
        <td>{{ $item->alamat }}</td>
    </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>