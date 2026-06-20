<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            ActivityLogger::log(
                'login_success',
                'Login berhasil untuk ' . Auth::user()->email . '.',
                Auth::user(),
            );

            return redirect()->intended('/dashboard');
        }

        $attemptedUser = User::where('email', $credentials['email'])->first();

        ActivityLogger::log(
            'login_failed',
            'Login gagal untuk ' . $credentials['email'] . '.',
            $attemptedUser,
            ['email' => $credentials['email']],
            $attemptedUser?->id,
            $attemptedUser?->name,
            $credentials['email'],
        );

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLogger::log(
            'logout',
            'Logout berhasil untuk ' . (Auth::user()->email ?? 'unknown') . '.',
            Auth::user(),
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
