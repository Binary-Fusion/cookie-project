<?php

use Illuminate\Http\Request;
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
            return Blade::render('Cookies amount must be a number.');
        }

        $user   = Auth::user();
        $wallet = $user->wallet;

        if ($cookies > $wallet) {
            return Blade::render('You have not enough money in your wallet to buy this cookies.');
        }

        $user->update(['wallet' => $wallet - $cookies]);
    
        Log::info('User ' . $user->email . ' have bought ' . $cookies . ' cookies');

        return Blade::render('Success, you have bought, {{ $cookies }} cookies!', ['cookies' => $cookies]);
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
