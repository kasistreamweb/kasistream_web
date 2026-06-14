<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Donasi;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Exports\AdminReportExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{

public function dashboard()
{
    $totalUser = User::count();

    $totalStreamer = User::where(
        'is_streamer',
        true
    )->count();

    $totalDonasi = Donasi::where(
        'status',
        'success'
    )->sum('nominal');

    $transaksiHariIni = Donasi::whereDate(
        'created_at',
        today()
    )->count();

    $donasiTerbaru = Donasi::with([
        'user',
        'streamer'
    ])
    ->latest()
    ->take(5)
    ->get();

    $totalPendapatanPlatform = Donasi::where(
        'status',
        'success'
    )->sum('admin_fee');

    $withdrawPending = Withdraw::where(
        'status',
        'pending'
    )->sum('nominal');

    $labels = [];

    $donasiPerHari = [];

    foreach(
        CarbonPeriod::create(
            now()->subDays(6),
            now()
        ) as $date
    ){

        $labels[] =
            $date->format('d M');

        $donasiPerHari[] =
            Donasi::where(
                'status',
                'success'
            )
            ->whereDate(
                'created_at',
                $date
            )
            ->sum('nominal');
    }

    return view(
        'admin.admin-dashboard',
        compact(
            'totalUser',
            'totalStreamer',
            'totalDonasi',
            'transaksiHariIni',
            'donasiTerbaru',
            'totalPendapatanPlatform',
            'withdrawPending',
            'labels',
            'donasiPerHari'
        )
    );
}

public function users()
{
    $query = User::query();

    if(request('search'))
    {
        $query->where(function($q){

            $q->where(
                'name',
                'like',
                '%' . request('search') . '%'
            )
            ->orWhere(
                'email',
                'like',
                '%' . request('search') . '%'
            );

        });
    }

    if(request('role'))
    {
        $query->where(
            'role',
            request('role')
        );
    }

    if(request('streamer') !== null &&
       request('streamer') !== '')
    {
        $query->where(
            'is_streamer',
            request('streamer')
        );
    }

    $users = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view(
        'admin.admin-users',
        compact('users')
    );
}

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if($user->role == 'admin'){
            return back()->with(
                'error',
                'Admin tidak bisa dihapus.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }

    public function streamers()
{
    $streamers = User::where('is_streamer', true)
        ->latest()
        ->paginate(10);

    return view(
        'admin.admin-streamers',
        compact('streamers')
    );
}

public function streamerDetail($id)
{
    $streamer = User::findOrFail($id);

    $totalDonasi = Donasi::where(
        'streamer_id',
        $id
    )
    ->where(
        'status',
        'success'
    )
    ->sum('nominal');

    $totalTransaksi = Donasi::where(
        'streamer_id',
        $id
    )->count();

    $totalWithdraw = Withdraw::where(
        'user_id',
        $id
    )
    ->where(
        'status',
        'approved'
    )
    ->sum('nominal');

    $donasiMasuk = Donasi::with('user')
        ->where('streamer_id', $id)
        ->latest()
        ->take(20)
        ->get();

    $withdrawHistory = Withdraw::where(
        'user_id',
        $id
    )
    ->latest()
    ->take(20)
    ->get();

    return view(
        'admin.admin-streamer-detail',
        compact(
            'streamer',
            'totalDonasi',
            'totalTransaksi',
            'totalWithdraw',
            'donasiMasuk',
            'withdrawHistory'
        )
    );
}

    public function removeStreamer($id)
{
    $user = User::findOrFail($id);

    $user->is_streamer = false;

    $user->bio = null;
    $user->game = null;

    $user->instagram = null;
    $user->youtube = null;
    $user->tiktok = null;
    $user->discord = null;

    $user->save();

    return back()->with(
        'success',
        'Status streamer berhasil dicabut.'
    );
}
public function donations()
{
    $query = Donasi::with([
        'user',
        'streamer'
    ]);

    if(request('search'))
    {
        $query->where(function($q){

            $q->where('guest_name','like','%' . request('search') . '%')

            ->orWhereHas('user',function($u){

                $u->where(
                    'name',
                    'like',
                    '%' . request('search') . '%'
                );

            })

            ->orWhereHas('streamer',function($s){

                $s->where(
                    'name',
                    'like',
                    '%' . request('search') . '%'
                );

            });

        });
    }

    if(request('status'))
    {
        $query->where(
            'status',
            request('status')
        );
    }

    if(request('metode'))
    {
        $query->where(
            'payment_method',
            request('metode')
        );
    }

    if(request('dari'))
    {
        $query->whereDate(
            'created_at',
            '>=',
            request('dari')
        );
    }

    if(request('sampai'))
    {
        $query->whereDate(
            'created_at',
            '<=',
            request('sampai')
        );
    }

    $donasi = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();

    $totalDonasi =
        (clone $query)->sum('nominal');

    $totalAdminFee =
        (clone $query)->sum('admin_fee');

    $jumlahTransaksi =
        (clone $query)->count();

    $donasiHariIni =
        Donasi::whereDate(
            'created_at',
            today()
        )->sum('nominal');

    return view(
        'admin.admin-donations',
        compact(
            'donasi',
            'totalDonasi',
            'totalAdminFee',
            'jumlahTransaksi',
            'donasiHariIni'
        )
    );
}

public function donationDetail($id)
{
    $donasi = Donasi::with([
        'user',
        'streamer'
    ])->findOrFail($id);

    return view(
        'admin.admin-donation-detail',
        compact('donasi')
    );
}

    public function withdraws()
    {
        $withdraws = Withdraw::with('user')
                        ->latest()
                        ->paginate(10);

        return view(
            'admin.admin-withdraws',
            compact('withdraws')
        );
    }

    public function approveWithdraw($id)
    {
        $withdraw = Withdraw::findOrFail($id);

        if($withdraw->status != 'pending')
        {
            return back();
        }

        $user = $withdraw->user;

        $user->balance -= $withdraw->nominal;

        $user->save();

        $withdraw->status = 'approved';

        $withdraw->save();

        return back()->with(
            'success',
            'Withdraw berhasil disetujui.'
        );
    }

    public function rejectWithdraw($id)
    {
        $withdraw = Withdraw::findOrFail($id);

        $withdraw->status = 'rejected';

        $withdraw->save();

        return back()->with(
            'success',
            'Withdraw ditolak.'
        );
    }

   public function reports(Request $request)
{
    $query = Donasi::query();

    if ($request->filled('dari')) {
        $query->whereDate(
            'created_at',
            '>=',
            $request->dari
        );
    }

    if ($request->filled('sampai')) {
        $query->whereDate(
            'created_at',
            '<=',
            $request->sampai
        );
    }

    $totalUser = User::count();

    $totalStreamer = User::where(
        'is_streamer',
        true
    )->count();

    $totalDonasi = (clone $query)->sum(
        'nominal'
    );

    $totalWithdraw = Withdraw::where(
        'status',
        'approved'
    )->sum(
        'nominal'
    );

    $totalTransaksi = (clone $query)->count();

    $totalPendapatanPlatform = (clone $query)
        ->where(
            'status',
            'success'
        )
        ->sum(
            'admin_fee'
        );

    $gatewaySuccess = (clone $query)
        ->where(
            'status',
            'success'
        )
        ->count();

    $gatewayPending = (clone $query)
        ->where(
            'status',
            'pending'
        )
        ->count();

    $gatewayFailed = (clone $query)
        ->where(
            'status',
            'failed'
        )
        ->count();

    $topStreamer = User::where(
        'is_streamer',
        true
    )
    ->orderByDesc(
        'total_donasi'
    )
    ->take(5)
    ->get();

    $topDonatur = Donasi::join(
        'users',
        'users.id',
        '=',
        'donasis.user_id'
    )
    ->where(
        'donasis.status',
        'success'
    )
    ->selectRaw(
        '
        users.name,
        SUM(donasis.nominal) as total_donasi_user
        '
    )
    ->groupBy(
        'users.name'
    )
    ->orderByDesc(
        'total_donasi_user'
    )
    ->limit(5)
    ->get();

    $topMetode = Donasi::selectRaw(
            'payment_method,
             COUNT(*) as total'
        )
        ->groupBy(
            'payment_method'
        )
        ->orderByDesc(
            'total'
        )
        ->get();

    return view(
        'admin.admin-reports',
        compact(
            'totalUser',
            'totalStreamer',
            'totalDonasi',
            'totalWithdraw',
            'totalTransaksi',
            'totalPendapatanPlatform',
            'gatewaySuccess',
            'gatewayPending',
            'gatewayFailed',
            'topStreamer',
            'topDonatur',
            'topMetode'
        )
    );
}

    public function streamerRanking()
{
    $streamers = User::where(
        'is_streamer',
        true
    )
    ->orderByDesc('total_donasi')
    ->paginate(20);

    return view(
        'admin.admin-streamer-ranking',
        compact('streamers')
    );
}

public function gatewayTransactions()
{
    $transactions = Donasi::with([
        'user',
        'streamer'
    ])
    ->whereNotNull('invoice_id')
    ->latest()
    ->paginate(20);

    return view(
        'admin.gateway-transactions',
        compact('transactions')
    );
}

public function exportExcel()
{
    return Excel::download(
        new AdminReportExport,
        'laporan-kasistream.xlsx'
    );
}

public function printReport(Request $request)
{
    $query = Donasi::with([
        'user',
        'streamer'
    ]);

    if ($request->filled('dari')) {
        $query->whereDate(
            'created_at',
            '>=',
            $request->dari
        );
    }

    if ($request->filled('sampai')) {
        $query->whereDate(
            'created_at',
            '<=',
            $request->sampai
        );
    }

    $laporanDonasi = $query
        ->latest()
        ->get();

    $totalDonasi = $laporanDonasi->sum('nominal');

    $totalTransaksi = $laporanDonasi->count();

    $totalPendapatanPlatform = $laporanDonasi->sum('admin_fee');

    return view(
        'admin.print-report',
        compact(
            'laporanDonasi',
            'totalDonasi',
            'totalTransaksi',
            'totalPendapatanPlatform'
        )
    );
}

}