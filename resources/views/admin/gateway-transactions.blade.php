<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Transaksi Gateway</title>

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

</style>

</head>
<body>

<div class="container-fluid">

<div class="row">

@include('admin.layouts.sidebar')

<div class="col-md-10 p-4">

    <div class="content-card shadow-sm">

        <h3 class="mb-4">

            Transaksi Gateway

        </h3>

        <table class="table table-hover">

            <thead>

                <tr>

                    <th>Invoice</th>
                    <th>Donatur</th>
                    <th>Streamer</th>
                    <th>Metode</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>

                </tr>

            </thead>

            <tbody>

                @forelse($transactions as $item)

                    <tr>

                        <td>
                            {{ $item->invoice_id }}
                        </td>

                        <td>
                            {{ optional($item->user)->name ?? $item->guest_name }}
                        </td>

                        <td>
                            {{ $item->streamer->name }}
                        </td>

                        <td>
                            {{ strtoupper($item->payment_method) }}
                        </td>

                        <td>
                            Rp {{ number_format($item->grand_total) }}
                        </td>

                        <td>

                            @if($item->status == 'success')

                                <span class="badge bg-success">
                                    Success
                                </span>

                            @elseif($item->status == 'pending')

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Failed
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            Belum ada transaksi gateway.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        {{ $transactions->links() }}

    </div>

</div>

</div>

</div>

</body>
</html>