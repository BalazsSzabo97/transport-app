<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'register')
                ->withInput();
        }

        Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_confirmed' => false, // default to unconfirmed
            'token' => Str::random(40),
        ]);

        return redirect('/')->with('success', 'Sikeres regisztráció! Várjon az adminisztrátor megerősítésére.');
    }
}
