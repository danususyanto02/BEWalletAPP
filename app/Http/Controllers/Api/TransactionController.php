<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit') ? $request->query('limit') : 10;


        $user  = auth()->user();

        $relations = ['transactionType:id,name,code,thumbnail', 'paymentMethod:id,name,code,thumbnail'];

        $transaction = Transaction::with($relations)
            ->where('user_id', $user->id)
            ->where('status', 'success')
            ->orderBy('id', 'desc')
            ->paginate($limit);

        $transaction->getCollection()->transform(function ($item) {
            $paymentMethod = $item->paymentMethod;
            $item->paymentMethod->thumbnail =  $paymentMethod->thumbnail ? url('banks/' . $paymentMethod->thumbnail) : '';

            $transactionType = $item->transactionType;
            $item->transactionType->thumbnail = $transactionType->thumbnail ? url('transaction-type/' . $transactionType->thumbnail) : '';

            return $paymentMethod;
        });

        return response()->json($transaction);
    }
}
