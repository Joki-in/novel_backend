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

            return redirect()->intended('/dashboard');
        }

        // Jika otentikasi gagal, kembalikan ke halaman login dengan pesan kesalahan
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function show(string $id)
    {

    }
    public function registerShow()
    {
        return view('auth.register');
    }
    public function storeRegister(Request $request)
    {

    }
}
