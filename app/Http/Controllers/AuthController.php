<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexLogin()
    {
        return view('auth.login');
    }

    public function storeLogin(Request $request)
    {
        // Validasi data masukan dari formulir login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba untuk melakukan otentikasi pengguna
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->intended('/dashboardAdmin');
            } elseif ($user->role == 'user') {
                return redirect()->intended('/dashboardUser');
            }
        }

        // Jika otentikasi gagal, kembalikan ke halaman login dengan pesan kesalahan
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the user's session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect the user to the login page or wherever you want
        return redirect('/')->with('message', 'You have been successfully logged out!');
    }
}
