<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TopupController;
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



Route::group(['middleware' => 'jwt.verify'], function ($router){
    Route::post('topup', [TopupController::class, 'store']);
});
