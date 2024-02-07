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
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|exists:buku,id',
            'id_user' => 'required|exists:users,id',
            'komentar' => 'required|string',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Buat data Komentar baru
        $komentar = Komentar::create([
            'id_buku' => $request->id_buku,
            'id_user' => $request->id_user,
            'komentar' => $request->komentar,
        ]);

        // Cek jika komentar berhasil dibuat
        if (!$komentar) {
            return response()->json(['message' => 'Failed to create comment'], 500); // 500 Internal Server Error
        }

        // Kirimkan respon berhasil
        return response()->json(['message' => 'Komentar created successfully', 'komentar' => $komentar], 201); // 201 Created
    }

}
