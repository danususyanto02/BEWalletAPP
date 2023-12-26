<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only('amount', 'pin', 'payment_method_code');
        $validator = Validator::make($data, [
            'amount' => 'required|integer|min:10000',
            'pin' => 'required|digits:6',
            'payment_method_code' => 'required|in:mandiri_va,bni_va,bca_va'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        $pinChecker = pinChecker($request->pin);
        if (!$pinChecker) {
            return response()->json(['messages' => 'Wrong Pin']);
        }

        $transactionType = TransactionType::where('code', 'topup')->first();
        $paymentMethod = PaymentMethod::where('code', $request->payment_method_code)->first();

        // dd( $paymentMethod->code);

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'payment_methods_id' => $paymentMethod->id,
                'transaction_type_id' => $transactionType->id,
                'amount' => $request->amount,
                'transaction_code' => strtoupper(Str::random(10)),
                'description' => 'Topup Via' . $paymentMethod->name,
                'status' => 'Pending',
            ]);

            $params = $this->buildMidtransParameter([
                'transaction_code' =>  $transaction->transaction_code,
                'amount' =>  $transaction->amount,
                'payment_method' =>  $paymentMethod->code,
            ]);

            $midtrans = $this->callMidtrans($params);

            // dd($params);
            DB::commit();

            return response()->json($midtrans);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['messages' => $th->getMessage()], 500);
        }
    }

    private function callMidtrans(array $params)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$isSanitized = (bool)  env('MIDTRANS_IS_SANITIZED');
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_IS_3DS');

        $createTransaction = \Midtrans\Snap::createTransaction($params);

        return [
            'redirect_url' => $createTransaction->redirect_url,
            'token' => $createTransaction->token,
        ];
    }

    private function buildMidtransParameter(array $params)
    {

        // dd($params('transaction_code'));

        $trasactionDetails = [
            'order_id' => $params['transaction_code'],
            'gross_amount' =>  $params['amount'],
        ];



        $user = auth()->user();
        $splitName = $this->splitName($user->name);

        $customerDetails = [
            'first_name' => $splitName['first_name'],
            'last_name' =>   $splitName['last_name'],
            'email' => $user->email,

        ];
        // dd($customerDetails);
        $enablePayment  = [
            $params['payment_method'],
        ];

        return [
            'transaction_details' => $trasactionDetails,
            'customer_details' => $customerDetails,
            'enable_payments' => $enablePayment,
        ];
    }

    private function splitName($fullName)
    {
        $name = explode(' ', $fullName);
        $lastName = count($name) > 1 ? array_pop($name) : $fullName;
        $firstName = implode(' ', $name);

        return [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
    }
}
