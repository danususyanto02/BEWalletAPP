<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\TranferHistory;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransferController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only('amount', 'pin', 'send_to');

        $validator = Validator::make($data, [
            'amount' => 'required|integer|min:10000',
            'pin' => 'required|digits:6',
            'send_to' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors', $validator->messages()], 400);
        }

        $sender = auth()->user();
        $receiver = User::select('users.id', 'users.username')->join('wallet', 'wallet.user_id', 'users.id')
            ->where('users.username', $request->send_to)
            ->orWhere('wallet.card_number', $request->send_to)
            ->first();

        $pinChecker = pinChecker($request->pin);

        if (!$pinChecker) {
            return response()->json(['message' => 'Wrong PIN'], 400);
        }

        if (!$receiver) {
            return response()->json(['message' => 'Receiver Not Found'], 400);
        }

        if ($sender->id == $receiver->id) {
            return response()->json(['message' => 'Cannot Transfer To Yourself'], 400);
        }

        $senderWallet = Wallets::where('user_id', $sender->id)->first();
        if ($senderWallet->balance < $request->amount) {
            return response()->json(['message' => 'Your Balance Is Not Enough'], 400);
        }

        DB::beginTransaction();

        try {
            $transactionType = TransactionType::whereIn('code', ['receiver', 'transfer'])
                ->orderBy('code', 'asc')->get();


            $transferTransactionType = $transactionType->last();
            $receiveTransactionType = $transactionType->first();

            $transactionCode = strtoupper(Str::random(10));
            $paymentMethod = PaymentMethod::where('code', 'walletapp')->first();

            //Transaction Transfer
            $transferTransaction = Transaction::create([
                'user_id' => $sender->id,
                'transaction_type_id' => $transferTransactionType->id,
                'description' => 'Transfer To ' . $receiver->username,
                'amount' => $request->amount,
                'transaction_code' => $transactionCode,
                'status' => 'success',
                'payment_methods_id' => $paymentMethod->id,
            ]);


            $senderWallet->decrement('balance', $request->amount);


            //Transaction Receive
            $transferTransaction = Transaction::create([
                'user_id' => $receiver->id,
                'transaction_type_id' => $receiveTransactionType->id,
                'description' => 'Rececive From ' . $sender->username,
                'amount' => $request->amount,
                'transaction_code' => $transactionCode,
                'status' => 'success',
                'payment_methods_id' => $paymentMethod->id,
            ]);

            Wallets::where('user_id', $receiver->id)->increment('balance', $request->amount);

            TranferHistory::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'transaction_code' => $transactionCode
            ]);

            DB::commit();

            return response(['message' => 'Transfer Success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
