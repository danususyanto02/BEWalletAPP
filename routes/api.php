<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataPlanController;
use App\Http\Controllers\Api\OperatorCardController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\TipController;
use App\Http\Controllers\Api\TopupController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\TransferHistoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('jwt.verify')->get('/test', function (Request $request) {
//     return 'success';
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('payment/webhook', [WebhookController::class, 'update']);
Route::post('transfer', [TransferController::class, 'store']);
Route::post('dataplan', [DataPlanController::class, 'store']);
Route::post('email-exist', [UserController::class, 'isEmailExist']);





Route::group(['middleware' => 'jwt.verify'], function ($router){

    Route::post('logout', [AuthController::class, 'logout']);


    Route::post('topup', [TopupController::class, 'store']);
    Route::post('tips', [TipController::class, 'index']);

    Route::put('user-update', [UserController::class, 'update']);

    Route::get('bank-list', [PaymentMethodController::class, 'index']);

    Route::get('list-operator', [OperatorCardController::class, 'index']);

    Route::get('transfer-history', [TransferHistoryController::class, 'index']);

    Route::get('transaction', [TransactionController::class, 'index']);

    Route::get('users', [UserController::class, 'show']);

    Route::get('users/{username}', [UserController::class, 'getUserByUsername']);

    Route::get('wallet', [WalletController::class, 'show']);
    Route::put('wallet', [WalletController::class, 'update']);

});
