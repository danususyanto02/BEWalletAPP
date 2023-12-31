<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPlan;
use App\Models\DataPlanHistory;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DataPlanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_plan_id' => 'required|integer',
            'phone_number' => 'required|string',
            'pin' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors', $validator->messages()], 400);
        }

        $userID = auth()->user()->id;

        $transactionType = TransactionType::where('code', 'internet')->first();
        $paymentMethod = PaymentMethod::where('code', 'walletapp')->first();

        $userWallet = Wallets::where('user_id', $userID)->first();

        $dataPlan = DataPlan::find($request->data_plan_id);

        if (!$dataPlan) {
            return response()->json(['message' => 'Data plan not found'], 404);
        }

        $pinChecker = pinChecker($request->pin);

        if (!$pinChecker) {
            return response()->json(['message' => 'Wrong PIN'], 400);
        }

        if ($userWallet->balance < $dataPlan->price) {
            return response()->json(['message' => 'Balance is not enough'], 400);
        }

        DB::beginTransaction();

        try {
            // dd($transactionType);
            $transaction = Transaction::create([
                'user_id' => $userID,
                'transaction_type_id' => $transactionType->id,
                'payment_methods_id' => $paymentMethod->id,
                'amount' => $dataPlan->price,
                'transaction_code' => strtoupper(Str::random(10)),
                'description' => 'Internet Data Plan ' . $dataPlan->name,
                'status' => 'success'

            ]);

            DataPlanHistory::create([
                'data_plan_id' => $request->data_plan_id,
                'transaction_id' => $transaction->id,
                'phone_number' => $request->phone_number,

            ]);

            $userWallet->decrement('balance', $dataPlan->price);

            DB::commit();

            return response()->json(['message' => 'Transaction Data Plan Success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }


    }
}
