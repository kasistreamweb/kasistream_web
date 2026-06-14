<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Detail Streamer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>

body{
    background:#f4f6f9;
}

.content-card{
    background:white;
    border-radius:15px;
    padding:20px;
}

.profile-img{
    width:120px;
    height:120px;
    border-radius:50%;
    margin-left:40px;
    object-fit:cover;
}

.stat-card{
    background:white;
    border-radius:15px;
    padding:20px;
    text-align:center;
}

.table td{
    vertical-align:middle;
}

</style>

</head>
<body>

<div class="container-fluid">

<div class="row">

@include('admin.layouts.sidebar')

<div class="col-md-10 p-4">


<div class="content-card shadow-sm mb-4">

<div class="row align-items-center">

<div class="col-md-2">

@if($streamer->foto)

<img
    src="{{ asset('uploads/profile/'.$streamer->foto) }}"
    class="profile-img"
>

@else

<img
    src="{{ asset('images/default-avatar.png') }}"
    class="profile-img"
>

@endif
</div>

<div class="col-md-10">
<h2>

    {{ $streamer->name }}

</h2>

<p class="mb-1">

    <strong>Email :</strong>

    {{ $streamer->email }}

</p>

<p class="mb-1">

    <strong>Game :</strong>

    {{ $streamer->game ?? '-' }}

</p>

<p class="mb-1">

    <strong>Role :</strong>

    {{ $streamer->role }}

</p>

<p class="mb-1">

    <strong>Saldo :</strong>

    Rp {{ number_format($streamer->balance ?? 0) }}

</p>

<p class="mb-1">

    <strong>Total Donasi :</strong>

    Rp {{ number_format($streamer->total_donasi ?? 0) }}

</p>

<p>

    <strong>Status :</strong>

    @if($streamer->is_streamer)

        <span class="badge bg-success">

            Streamer Aktif

        </span>

    @else

        <span class="badge bg-danger">

            Non Streamer

        </span>

    @endif

</p>

</div>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="stat-card shadow-sm">

<h6>Followers</h6>

<h3>
{{ number_format($streamer->followers) }}
</h3>

</div>

</div>

<div class="col-md-3">

<div class="stat-card shadow-sm">

<h6>Total Donasi</h6>

<h3>
Rp {{ number_format($totalDonasi) }}
</h3>

</div>

</div>

<div class="col-md-3">

<div class="stat-card shadow-sm">

<h6>Total Transaksi</h6>

<h3>
{{ number_format($totalTransaksi) }}
</h3>

</div>

</div>

<div class="col-md-3">

<div class="stat-card shadow-sm">

<h6>Total Withdraw</h6>

<h3>
Rp {{ number_format($totalWithdraw) }}
</h3>

</div>

</div>

</div>

<div class="content-card shadow-sm mb-4">

<h4 class="mb-3">
Riwayat Donasi Masuk
</h4>

<table class="table table-bordered">

<thead>

<tr>

<th>Donatur</th>
<th>Nominal</th>
<th>Metode</th>
<th>Status</th>
<th>Tanggal</th>

</tr>

</thead>

<tbody>

@foreach($donasiMasuk as $item)

<tr>

<td>

{{ optional($item->user)->name ?? $item->guest_name }}

</td>

<td>

Rp {{ number_format($item->nominal) }}

</td>

<td>

{{ $item->payment_method }}

</td>

<td>

{{ ucfirst($item->status) }}

</td>

<td>

{{ $item->created_at->format('d M Y H:i') }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="content-card shadow-sm">

<h4 class="mb-3">
Riwayat Withdraw
</h4>


<table class="table table-bordered">

<thead>

<tr>

<th>Nominal</th>
<th>Bank</th>
<th>Rekening</th>
<th>Status</th>
<th>Tanggal</th>

</tr>

</thead>

<tbody>

@if($withdrawHistory->isEmpty())

<tr>

    <td colspan="5" class="text-center text-muted">

        Belum ada riwayat withdraw.

    </td>

</tr>

@else

    @foreach($withdrawHistory as $item)

    <tr>

        <td>

            Rp {{ number_format($item->nominal) }}

        </td>

        <td>

            {{ $item->bank }}

        </td>

        <td>

            {{ $item->rekening }}

        </td>

        <td>

            {{ ucfirst($item->status) }}

        </td>

        <td>

            {{ $item->created_at->format('d M Y H:i') }}

        </td>

    </tr>

    @endforeach

@endif

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>
</html>