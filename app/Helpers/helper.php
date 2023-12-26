<?php

use App\Models\User;
use App\Models\Wallets;

function getUser($param)
{
    $user = User::where('id', $param)
        ->orWhere('email', $param)
        ->orWhere('username', $param)
        ->first();

    $wallet = Wallets::where('user_id', $user->id)->first();

    $user->profile_picture = $user->profile_picture ? url('storage/' . $user->profile_picture) : "";
    $user->ktp = $user->ktp ? url('storage/' . $user->ktp) : "";
    $user->balance = $wallet->balance;
    $user->card_number = $wallet->card_number;
    $user->pin = $wallet->pin;

    return $user;
}

function pinChecker($pin)
{
    $userID = auth()->user()->id;
    $wallet = Wallets::where('user_id', $userID)->first();

    if (!$wallet) return false;

    if ($wallet->pin == $pin) return true;

    return false;
}
