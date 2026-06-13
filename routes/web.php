<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\StreamerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WithdrawController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $streamers = User::where('is_streamer', 1)
                    ->latest()
                    ->take(6)
                    ->get();

    $totalStreamer = User::where('is_streamer', 1)->count();

    $totalUser = User::count();

    return view('welcome', compact(
        'streamers',
        'totalStreamer',
        'totalUser'
    ));

});

/*
|--------------------------------------------------------------------------
| REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin-dashboard',
    [AdminController::class, 'dashboard']
)->middleware('auth');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/user-dashboard', function () {

    $streamers = User::where('is_streamer', 1)->get();

    return view('user-dashboard', compact('streamers'));

})->middleware('auth');

/*
|--------------------------------------------------------------------------
| STREAMER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/streamer-dashboard', [StreamerController::class, 'dashboard'])
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| DETAIL STREAMER
|--------------------------------------------------------------------------
*/

Route::get('/streamer/{id}', function ($id) {

    $streamer = User::findOrFail($id);

    $isFollowing = false;

    if(auth()->check()){

        $isFollowing = \App\Models\Follower::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'streamer_id',
            $streamer->id
        )
        ->exists();
    }

    return view(
        'streamer-detail',
        compact(
            'streamer',
            'isFollowing'
        )
    );

});

/*
|--------------------------------------------------------------------------
| donate
|--------------------------------------------------------------------------
*/

Route::get(
    '/donasi/{id}',
    [DonasiController::class, 'create']
);

Route::post(
    '/donasi',
    [DonasiController::class, 'store']
);

Route::get('/become-streamer', function () {
    return view('become-streamer');
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| user to streamer
|--------------------------------------------------------------------------
*/


Route::post('/become-streamer', function (Illuminate\Http\Request $request) {

    $request->validate([
        'bio' => 'required',
        'game' => 'required'
    ]);

    $user = Auth::user();

    $user->bio = $request->bio;
    $user->game = $request->game;
    $user->instagram = $request->instagram;
    $user->youtube = $request->youtube;
    $user->tiktok = $request->tiktok;
    $user->discord = $request->discord;

    $user->is_streamer = true;

    $user->save();

    return redirect('/user-dashboard')
    ->with('success', 'Selamat! Akun Anda sekarang menjadi streamer.');
    })->middleware('auth');

    Route::get('/streamer-donations',
    [StreamerController::class, 'donations']
    )->middleware('auth');


    Route::get(
        '/streamer-statistics',
        [StreamerController::class, 'statistics']
    )->middleware('auth');

    Route::get(
    '/riwayat-donasi',
    [DonasiController::class, 'history']
    )->middleware('auth');

    Route::get(
        '/profile',
        [AuthController::class, 'profile']
    )->middleware('auth');

    Route::post(
        '/profile',
        [AuthController::class, 'updateProfile']
    )->middleware('auth');

    Route::get(
    '/admin-users',
    [AdminController::class, 'users']
    )->middleware('auth');

    Route::delete(
    '/admin-users/{id}',
    [AdminController::class, 'deleteUser']
    )->middleware('auth');

    Route::get(
    '/admin-streamers',
    [AdminController::class, 'streamers']
    )->middleware('auth');

    Route::post(
    '/admin-streamers/remove/{id}',
    [AdminController::class, 'removeStreamer']
    )->middleware('auth');

    Route::get(
    '/admin-donations',
    [AdminController::class, 'donations']
    )->middleware('auth');

    Route::get('/wallet', function () {

        if(!auth()->user()->is_streamer){
            abort(403);
        }

        $donasi = \App\Models\Donasi::with('user')
            ->where(
                'streamer_id',
                auth()->id()
            )
            ->get();

        $withdraws = \App\Models\Withdraw::where(
            'user_id',
            auth()->id()
        )->get();

        $withdrawPending = \App\Models\Withdraw::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'status',
            'pending'
        )
        ->sum('nominal');

        $saldoTersedia =
            auth()->user()->balance
            - $withdrawPending;

        $transaksi = collect();

        foreach($donasi as $item){

            $transaksi->push([

                'tanggal' => $item->created_at,

                'keterangan' =>
                    'Donasi dari ' .
                    optional($item->user)->name
                    ?? $item->guest_name,

                'nominal' =>
                    $item->nominal,

                'status' =>
                    'success',

                'jenis' =>
                    'donasi'

            ]);
        }

        foreach($withdraws as $item){

            $transaksi->push([

                'tanggal' => $item->created_at,

                'keterangan' =>
                    'Withdraw Request',

                'nominal' =>
                    $item->nominal,

                'status' =>
                    $item->status,

                'jenis' =>
                    'withdraw'

            ]);
        }

        $transaksi = $transaksi
            ->sortByDesc('tanggal')
            ->take(10);

        return view(
            'wallet',
            compact(
                'transaksi',
                'withdrawPending',
                'saldoTersedia'
            )
        );

    })->middleware('auth');

    Route::get(
    '/withdraw',
    [WithdrawController::class,'create']
    )->middleware('auth');

    Route::post(
    '/withdraw',
    [WithdrawController::class,'store']
    )->middleware('auth');

    Route::get(
    '/admin-withdraws',
    [AdminController::class,'withdraws']
    )->middleware('auth');

    Route::post(
    '/admin-withdraws/{id}/approve',
    [AdminController::class,'approveWithdraw']
    )->middleware('auth');

    Route::post(
    '/admin-withdraws/{id}/reject',
    [AdminController::class,'rejectWithdraw']
    )->middleware('auth');

    Route::get(
    '/admin-reports',
    [AdminController::class,'reports']
    )->middleware('auth');

    Route::get('/wallet-history', function () {

    if(!auth()->user()->is_streamer){
        abort(403);
    }

    $donasi = \App\Models\Donasi::with('user')
        ->where(
            'streamer_id',
            auth()->id()
        )
        ->get();

    $withdraws = \App\Models\Withdraw::where(
        'user_id',
        auth()->id()
    )->get();

    $transaksi = collect();

    foreach($donasi as $item){

        $transaksi->push([

            'tanggal' => $item->created_at,
            'jenis' => 'Donasi',
            'keterangan' =>
                'Donasi dari ' .
                optional($item->user)->name
             ?? $item->guest_name,
            'nominal' => $item->nominal,
            'status' => 'success'

        ]);
    }

    foreach($withdraws as $item){

        $transaksi->push([

            'tanggal' => $item->created_at,
            'jenis' => 'Withdraw',
            'keterangan' =>
                'Withdraw Request',
            'nominal' => $item->nominal,
            'status' => $item->status

        ]);
    }

    $transaksi = $transaksi
        ->sortByDesc('tanggal');

    return view(
        'wallet-history',
        compact('transaksi')
    );

})->middleware('auth');

    Route::post(
    '/follow/{id}',
    [StreamerController::class,'follow']
    )->middleware('auth');

    Route::post(
    '/unfollow/{id}',
    [StreamerController::class,'unfollow']
    )->middleware('auth');

    Route::get(
    '/streamers',
    [StreamerController::class,'index']
    );

    Route::get(
    '/following',
    [StreamerController::class,'following']
    )->middleware('auth');

   Route::post('/payment-method', function (Request $request) {

    return view('payment-method', [

        'streamer_id' => $request->streamer_id,

        'nominal' => $request->nominal,

        'pesan' => $request->pesan,

        'fitur' => $request->fitur ?? []

    ]);

});

Route::post('/payment-detail', function (Request $request) {

    $streamer = User::find($request->streamer_id);

    $nominal = (int) $request->nominal;

    $fiturTotal = 0;

    if ($request->fitur) {

        foreach ($request->fitur as $fitur) {

            $fiturTotal += (int) $fitur;
        }
    }

    $adminFee = 1500;

    $grandTotal =
        $nominal +
        $fiturTotal +
        $adminFee;

    return view('payment-detail', [

        'streamer'   => $streamer,

        'nominal'    => $nominal,

        'pesan'      => $request->pesan,

        'metode'     => $request->metode,

        'fitur'      => $request->fitur ?? [],

        'fiturTotal' => $fiturTotal,

        'adminFee'   => $adminFee,

        'grandTotal' => $grandTotal

    ]);

});

Route::get('/payment-detail', function () {

    return redirect('/user-dashboard');

});
    Route::get('/payment-method', function () {
    return view('payment-method');
    });

Route::post(
    '/konfirmasi-pembayaran',
    [DonasiController::class, 'store']
);

Route::get(
    '/payment-success/{id}',
    [DonasiController::class,'paymentSuccess']
)->name('payment.success');

Route::get(
    '/payment-qr/{id}',
    [DonasiController::class, 'qrPayment']
)->name('payment.qr');

Route::post(
    '/payment-onopay/{id}',
    [DonasiController::class, 'payOnopay']
)->name('payment.onopay');

Route::get(
    '/payment-check/{id}',
    [DonasiController::class,'checkPayment']
)->name('payment.check');

Route::get(
    '/payment-success-manual/{id}',
    [DonasiController::class,'simulateQris']
);
/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout']);