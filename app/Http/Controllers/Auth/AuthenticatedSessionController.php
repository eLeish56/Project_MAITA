<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $login_type => $request->input('username'),
            'password' => $request->input('password')
        ];

        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            } else {
                // Catat absensi untuk staff (admin, cashier, supervisor)
                Absence::create([
                    'user_id'    => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'login_at'   => date('H:i'),
                ]);
                
                // Redirect pegawai ke dashboard admin
                if (in_array($user->role, ['admin', 'cashier', 'supervisor'])) {
                    return redirect()->route('dashboard');
                }
                
                // Fallback ke dashboard default
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        if ($user && $user->role !== 'customer') {
            // Update absensi hanya untuk staff
            $absence = Absence::where('user_id', $user->id)->latest()->first();
            if ($absence) {
                $absence->update(['logout_at' => date('H:i')]);
            }
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
