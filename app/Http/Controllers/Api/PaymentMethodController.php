<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $banks = PaymentMethod::where('status', 'active')
            ->where('code', "!=", 'walletapp')
            ->select('id','name','code','thumbnail')
            ->get()
            ->map(function ($item) {
                $item->thumbnail =   $item->thumbnail ? url($item->thumbnail) : '';
                return $item;
            });

        return response()->json($banks);
    }
}
