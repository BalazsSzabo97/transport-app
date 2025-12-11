<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Driver;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // Try admin first
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('admin.dashboard');
        }

        // Try driver
        if (Auth::guard('driver')->attempt($request->only('email', 'password'))) {
            return redirect()->route('driver.dashboard');
        }

        return back()->withErrors(['Hibás e-mail cím vagy jelszó']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('driver')->logout();

        return redirect()->route('index');
    }
}
