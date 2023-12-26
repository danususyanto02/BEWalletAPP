<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{

    public function update()
    {
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $type = $notif->payment_type;
        $transactionCode =  $notif->order_id;
        $fraudStatus = $notif->fraud_status;
        DB::beginTransaction();
        try {

            $status = null;



            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // TODO set transaction status on your database to 'success'
                    // and response with 200 OK

                    $status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $status = 'success';
            } else if (
                $transactionStatus == 'cancel' ||
                $transactionStatus == 'deny' ||
                $transactionStatus == 'expire'
            ) {
                // TODO set transaction status on your database to 'failure'
                // and response with 200 OK
                $status = 'failure';
            } else if ($transactionStatus == 'pending') {
                // TODO set transaction status on your database to 'pending' / waiting payment
                // and response with 200 OK
                $status = 'pending';
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)->first();

            if ($transaction->status != 'success') {
                $transactionAmount = $transaction->amount;
                $userID = $transaction->user_id;

                $transaction->update(['status' => $status]);

                if ($status == 'success') {
                    $wallet = Wallets::where('user_id', $userID)->increment('balance', $transactionAmount);
                }
            }

            DB::commit();
            return response()->json();
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
