<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Tampilkan halaman permintaan reset password.
     */
    public function create()
    {
        // Pastikan view yang digunakan sesuai dengan desain Anda
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email yang diminta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah user dengan email tersebut ada
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Kami tidak menemukan pengguna dengan email tersebut.',
            ]);
        }

        // Mengirimkan link reset password
        $status = Password::sendResetLink(
            ['email' => $request->email]
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
