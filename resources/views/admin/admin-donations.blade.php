<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Donasi - KAistream</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>

body{
    background:#f4f6f9;
}

.sidebar{
    min-height:100vh;
    background:linear-gradient(
        180deg,
        #0f172a,
        #111827
    );
}

.logo{
    color:white;
    font-size:24px;
    font-weight:bold;
}

.menu-item{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px 15px;
    border-radius:10px;
    margin-bottom:8px;
}

.menu-item:hover{
    background:rgba(255,255,255,.1);
    color:white;
}

.active-menu{
    background:rgba(255,255,255,.15);
}

.content-card{
    background:white;
    border-radius:15px;
    padding:20px;
}

.badge-success{
    background:#198754;
}

.badge-warning{
    background:#ffc107;
    color:black;
}

.table td{
    vertical-align:middle;
}

.badge.bg-info{
    color:white;
}

</style>

</head>
<body>

<div class="container-fluid">

<div class="row">

<!-- SIDEBAR -->

@include('admin.layouts.sidebar')

<!-- CONTENT -->

<div class="col-md-10 p-4">

    <div class="content-card shadow-sm">

        <h3 class="mb-4">

            Data Donasi Platform

        </h3>

        <div class="row mb-4">

   <div class="col-6 col-md-3 mb-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <small>Total Donasi</small>

                <h4>
                    Rp {{ number_format($totalDonasi) }}
                </h4>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <small>Total Transaksi</small>

                <h4>
                    {{ number_format($jumlahTransaksi) }}
                </h4>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <small>Pendapatan Platform</small>

                <h4>
                    Rp {{ number_format($totalAdminFee) }}
                </h4>

            </div>

        </div>

    </div>

    <div class="col-6 col-md-3 mb-3">

        <div class="card shadow-sm">

            <div class="card-body">

                <small>Donasi Hari Ini</small>

                <h4>
                    Rp {{ number_format($donasiHariIni) }}
                </h4>

            </div>

        </div>

    </div>

</div>

<form method="GET" action="/admin-donations" class="row g-3 mb-4">

    <div class="col-md-4">

        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Cari donatur atau streamer..."
            value="{{ request('search') }}"
        >

    </div>

    <div class="col-md-2">

        <select
            name="status"
            class="form-select"
        >

            <option value="">
                Semua Status
            </option>

            <option
                value="success"
                {{ request('status') == 'success' ? 'selected' : '' }}
            >
                Success
            </option>

            <option
                value="pending"
                {{ request('status') == 'pending' ? 'selected' : '' }}
            >
                Pending
            </option>

            <option
                value="failed"
                {{ request('status') == 'failed' ? 'selected' : '' }}
            >
                Failed
            </option>

        </select>

    </div>

    <div class="col-md-2">

        <input
            type="date"
            name="dari"
            class="form-control"
            value="{{ request('dari') }}"
        >

    </div>

    <div class="col-md-2">

        <input
            type="date"
            name="sampai"
            class="form-control"
            value="{{ request('sampai') }}"
        >

    </div>

    <div class="col-md-2">

        <button
            type="submit"
            class="btn btn-primary w-100"
        >

            <i class="fa-solid fa-magnifying-glass"></i>

            Filter

        </button>

    </div>

</form>

        <div class="table-responsive">
    <table class="table table-hover donation-table">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Donatur</th>
                    <th>Streamer</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                    <th>Admin Fee</th>
                    <th>Grand Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($donasi as $item)

                    <tr>

                        <td>
                            #{{ $item->id }}
                        </td>


    <td>

    {{ optional($item->user)->name ?? $item->guest_name }}

</td>

<td>

    @if($item->user)

        <span class="badge bg-primary">

            Member

        </span>

    @else

        <span class="badge bg-secondary">

            Guest

        </span>

    @endif

</td>
                        <td>
                            {{ $item->streamer->name }}
                        </td>

                        <td>
    Rp {{ number_format($item->nominal) }}
</td>

<td>
    Rp {{ number_format($item->admin_fee ?? 0) }}
</td>

<td>
    Rp {{ number_format($item->grand_total ?? $item->nominal) }}
</td>

<td>

    @if($item->payment_method)

        <span class="badge bg-info">

            {{ $item->payment_method }}

        </span>

    @else

        -

    @endif

</td>

                        <td>

                            @if($item->status == 'success')

                                <span class="badge bg-success">

                                    Success

                                </span>

                            @else

                                <span class="badge bg-warning">

                                    Pending

                                </span>

                            @endif

                        </td>

                        <td>

                            {{ $item->created_at->format('d M Y H:i') }}

                        </td>

<td>

    <div class="action-buttons">

        <a
            href="/admin-donations/{{ $item->id }}"
            class="btn btn-sm btn-info"
        >
            Detail
        </a>

    </div>

</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center">

                            Belum ada transaksi.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>
</div>
        <div class="mt-3">

            {{ $donasi->links() }}

        </div>

    </div>

</div>

</div>

</div>

</body>
</html>