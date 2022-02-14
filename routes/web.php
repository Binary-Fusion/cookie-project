<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('buy/{cookies}', function ($cookies) {
        // We can use this too
        // $request = new Request([
        //     'cookies' => $cookies
        // ]);

        // $request->validate(
        //     [
        //         'cookies' => 'required|min:1|numeric'
        //     ],
        //     [
        //         'cookies.required' => 'Please enter cookie amount.',
        //         'cookies.min'      => 'You must buy at least 1 cookie.',
        //         'cookies.numeric'  => 'cookies amount must be a number.'
        //     ]
        // );
        
        if (! is_numeric($cookies)) {
            return redirect()->back()->with('error', 'Sorry! cookies amount must be a number.');
        }

        if ($cookies <= 0) {
            return redirect()->back()->with('error', 'Sorry! cookies amount must be grater than 0.');
        }

        $user   = Auth::user();
        $wallet = $user->wallet;

        if ($cookies > $wallet) {
            return redirect()->back()->with('error', 'Sorry! you have not enough money in your wallet to buy this amount of cookies.');
        }

        $cachedUser = Cache::get('cachedUser');

        if ($cachedUser && ($cachedUser['cookies'] == $cookies && $cachedUser['user_id'] == $user->id)) {
            return redirect()->back()->with('error', 'Sorry! You are not allowed to buy your cookies this time. Try after ' . config('cache.cache_time')/60 . ' minute');
        }

        $user->update(['wallet' => $wallet - $cookies]);

        // Cache the user buying cookies info for 1 minutes
        Cache::put('cachedUser', ['user_id' => $user->id, 'cookies' => $cookies], config('cache.cache_time'));
        //Note: we can use db transection here to make sure that the user buying cookies is the same user who is buying cookies but it will not useful at this time. because we updating just one query.
    
        Log::info('User ' . $user->email . ' have bought ' . $cookies . ' cookies');

        return redirect()->back()->with('success', 'Success, you have bought ' . $cookies . ' cookies!');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
