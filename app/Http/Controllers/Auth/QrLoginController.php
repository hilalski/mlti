<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrLoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'qr_string' => 'required|string',
        ]);

        $qrString = trim($request->input('qr_string'));

        // Match against nip_lama
        $user = User::where('nip_lama', $qrString)->first();

        if ($user) {
            Auth::login($user, true); // login with remember
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'NIP Lama tidak terdaftar atau QR Code tidak cocok!'
        ], 404);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
