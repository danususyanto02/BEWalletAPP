<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $wallet = Wallets::select('pin', 'balance', 'card_number')
            ->where('user_id', $user->id)
            ->first();

        return response()->json($wallet);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'previous_pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }

        if (!pinChecker($request->previous_pin)) {
            return response()->json(['messages' => 'Invalid previous pin'], 400);
        }

        $user = auth()->user();
        $wallet = Wallets::where('user_id', $user->id)->update([
            'pin' => $request->new_pin
        ]);


        return response()->json(['message' => 'Success'],200);
    }
}
