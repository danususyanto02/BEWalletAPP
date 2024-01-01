<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\RedirectPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment/finish', [RedirectPaymentController::class, 'finish_redirect']);
Route::group(['prefix' => 'admin'], function () {



    Route::post('login', [AuthController::class, 'login'])->name('admin.login.auth');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::view('login', 'login')->name('admin.login.index');

    Route::group(['middleware' => 'auth:web'], function () {
        Route::view('/', 'dashboard')->name('admin.dashboard');
        Route::get('transaction', [TransactionController::class, 'index'])->name('admin.transaction.index');
    });
});
