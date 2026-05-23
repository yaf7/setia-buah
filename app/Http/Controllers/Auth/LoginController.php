<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            $request->session()->regenerate();
            $role = $user->role;

            if ($role === 'admin') {
                Auth::guard('web')->login($user);
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'petani') {
                Auth::guard('petani')->login($user);
                return redirect()->route('petani.dashboard');
            }

            return back()->withErrors([
                'email' => 'Login ini khusus Admin/Petani. Silakan gunakan login pembeli.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('petani')->logout();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
