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
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
            'user_type' => ['required', 'in:admin,photographer'],
        ]);

        $credentials = $request->only('email', 'password');
        $guard = $request->user_type === 'photographer' ? 'photographer' : 'web';

        if (Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return match ($guard) {
                'photographer' => redirect()->intended(route('photographer.dashboard')),
                'web' => redirect()->intended(route('admin.dashboard')),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $guard = Auth::guard('photographer')->check() ? 'photographer' : 'web';

        Auth::guard($guard)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
