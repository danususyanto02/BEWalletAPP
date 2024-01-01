<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credential  = $request->only('email', 'password');


        if (Auth::guard('web')->attempt($credential)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid Credentials')->withInput();
    }
    public function logout(Request $request)
    {

        Auth::guard('web')->logout();

        return redirect()->route('admin.login.index');
    }
}
