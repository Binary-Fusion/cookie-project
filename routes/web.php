<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        if (! is_numeric($cookies)) {
            return redirect()->back()->with('error', 'Sorry! cookies amount must be a number.');
        }

        $user   = Auth::user();
        $wallet = $user->wallet;

        if ($cookies > $wallet) {
            return redirect()->back()->with('error', 'Sorry! you have not enough money in your wallet to buy this amount of cookies.');
        }

        $user->update(['wallet' => $wallet - $cookies]);
    
        Log::info('User ' . $user->email . ' have bought ' . $cookies . ' cookies');

        return redirect()->back()->with('success', 'Success, you have bought ' . $cookies . ' cookies!');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');