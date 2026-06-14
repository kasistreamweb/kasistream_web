<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Withdraw Request</title>

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
    transition:.3s;
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

.table td{
    vertical-align:middle;
}

.stat-card{
    border:none;
    border-radius:15px;
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

            Withdraw Request

        </h3>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <!-- SEARCH & FILTER -->

        <form
            method="GET"
            action="/admin-withdraws"
            class="row g-3 mb-4"
        >

            <div class="col-md-6">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari streamer..."
                    value="{{ request('search') }}"
                >

            </div>

            <div class="col-md-3">

                <select
                    name="status"
                    class="form-select"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="pending"
                        {{ request('status') == 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="approved"
                        {{ request('status') == 'approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="rejected"
                        {{ request('status') == 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                </select>

            </div>

            <div class="col-md-3">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >

                    <i class="fa-solid fa-magnifying-glass"></i>

                    Filter

                </button>

            </div>

        </form>

        <!-- TABLE -->

        <div class="table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Streamer</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Rekening</th>
                        <th>Nama Rekening</th>
                        <th>Status</th>
                        <th>Tanggal Request</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($withdraws as $withdraw)

                        <tr>

                            <td>

                                {{ $withdraw->user->name }}

                            </td>

                            <td>

                                Rp {{ number_format($withdraw->nominal) }}

                            </td>

                            <td>

                                {{ $withdraw->bank }}

                            </td>

                            <td>

                                {{ $withdraw->rekening }}

                            </td>

                            <td>

                                {{ $withdraw->nama_rekening }}

                            </td>

                            <td>

                                @if($withdraw->status == 'pending')

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                @elseif($withdraw->status == 'approved')

                                    <span class="badge bg-success">

                                        Approved

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Rejected

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $withdraw->created_at->format('d M Y H:i') }}

                            </td>

                            <td>


                                @if($withdraw->status == 'pending')

                                    <form
                                        action="/admin-withdraws/{{ $withdraw->id }}/approve"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        <button
                                            class="btn btn-success btn-sm"
                                        >

                                            <i class="fa-solid fa-check"></i>

                                            Approve

                                        </button>

                                    </form>

                                    <form
                                        action="/admin-withdraws/{{ $withdraw->id }}/reject"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        <button
                                            class="btn btn-danger btn-sm"
                                        >

                                            <i class="fa-solid fa-xmark"></i>

                                            Reject

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center"
                            >

                                Belum ada data withdraw.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $withdraws->links() }}

        </div>

    </div>

</div>

</div>

</div>

</body>
</html>