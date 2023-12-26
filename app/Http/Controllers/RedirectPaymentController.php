<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class RedirectPaymentController extends Controller
{
    public function finish_redirect(Request $request)
    {
        $transactionCode = $request->order_id;
        $transaction = Transaction::where('transaction_code', $transactionCode)->first();

        return view('payment-finish', compact('transaction'));
    }
}
