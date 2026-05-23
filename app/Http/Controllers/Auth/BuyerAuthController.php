<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BuyerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.buyer_login');
    }

    public function showRegisterForm()
    {
        return view('auth.buyer_register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('buyer')->attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'role' => 'buyer'])) {
            $request->session()->regenerate();
            
            // Opsional: Pindahkan keranjang dari session ke user_id
            \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())
                ->whereNull('user_id')
                ->update(['user_id' => Auth::guard('buyer')->id()]);

            return redirect()->route('shop.index');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah, atau Anda bukan terdaftar sebagai pembeli.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'buyer',
        ]);

        Auth::guard('buyer')->login($user);

        // Pindahkan keranjang
        \App\Models\Cart::where('session_id', \Illuminate\Support\Facades\Session::getId())
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return redirect()->route('shop.index');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('buyer')->logout();
        $request->session()->regenerateToken();
        
        return redirect()->route('shop.index');
    }
}
