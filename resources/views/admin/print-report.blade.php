<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Laporan KAistream</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    padding:30px;
    font-size:14px;
}

.header{
    text-align:center;
    margin-bottom:30px;
}

.header h2{
    margin:0;
}

.table{
    margin-top:20px;
}

.summary{
    margin-top:30px;
}

@media print{
    .no-print{
        display:none;
    }
}

</style>

</head>
<body>

<div class="header">

    <h2>LAPORAN DONASI KAISTREAM</h2>

    <p>
        Dicetak :
        {{ now()->format('d/m/Y H:i') }}
    </p>

</div>

<div class="no-print mb-3">

    <button
        onclick="window.print()"
        class="btn btn-primary">

        Print Sekarang

    </button>

</div>

<table class="table table-bordered">

    <thead class="table-dark">

        <tr>

            <th>No</th>
            <th>Tanggal</th>
            <th>Donatur</th>
            <th>Streamer</th>
            <th>Nominal</th>
            <th>Admin Fee</th>
            <th>Status</th>

        </tr>

    </thead>

    <tbody>

        @foreach($laporanDonasi as $item)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>
                {{ $item->created_at->format('d/m/Y H:i') }}
            </td>

            <td>
                {{ optional($item->user)->name ?? $item->guest_name }}
            </td>

            <td>
                {{ optional($item->streamer)->name }}
            </td>

            <td>
                Rp {{ number_format($item->nominal) }}
            </td>

            <td>
                Rp {{ number_format($item->admin_fee) }}
            </td>

            <td>
                {{ ucfirst($item->status) }}
            </td>

        </tr>

        @endforeach

    </tbody>

</table>

<div class="summary">

    <table class="table table-bordered">

        <tr>
            <th width="300">
                Total Transaksi
            </th>
            <td>
                {{ $totalTransaksi }}
            </td>
        </tr>

        <tr>
            <th>
                Total Donasi
            </th>
            <td>
                Rp {{ number_format($totalDonasi) }}
            </td>
        </tr>

        <tr>
            <th>
                Pendapatan Platform
            </th>
            <td>
                Rp {{ number_format($totalPendapatanPlatform) }}
            </td>
        </tr>

    </table>

</div>

<script>
window.onload = function(){
    window.print();
}
</script>

</body>
</html>