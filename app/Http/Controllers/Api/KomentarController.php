<?php

namespace App\Http\Controllers\Api;

use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KomentarController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_buku' => 'required|exists:buku,id',
            'id_user' => 'required|exists:users,id',
            'komentar' => 'required|string',
        ]);

        // Buat data Komentar baru
        $komentar = Komentar::create([
            'id_buku' => $request->id_buku,
            'id_user' => $request->id_user,
            'komentar' => $request->komentar,
        ]);

        // Kirimkan respon berhasil
        return response()->json(['message' => 'Komentar created successfully', 'komentar' => $komentar], 201);
    }
}
