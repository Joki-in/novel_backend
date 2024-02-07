<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PushLikeAndViewController extends Controller
{
    public function tambahView(Request $request)
    {
        try {
            // Validasi data yang diterima dari body
            $request->validate([
                'id_buku' => 'required|exists:buku,id',
            ]);

            // Ambil ID dari body permintaan
            $id = $request->input('id_buku');

            // Temukan buku berdasarkan ID
            $buku = Buku::findOrFail($id);

            // Tambahkan 1 view
            $buku->increment('view');

            return response()->json(['message' => 'View added successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function checkLike(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_buku' => 'required|exists:bukus,id',
            'id_user' => 'required|exists:users,id',
        ]);

        // Mencari data Like berdasarkan id_buku dan id_user
        $like = Like::where([
            'buku_id' => $request->id_buku,
            'user_id' => $request->id_user
        ])->first();

        // Mengecek apakah data Like ditemukan
        if ($like) {
            // Jika ditemukan, kirimkan respon 'like'
            return response()->json(['status' => 'like'], 200);
        } else {
            // Jika tidak ditemukan, kirimkan respon 'none'
            return response()->json(['status' => 'none'], 200);
        }
    }
}
