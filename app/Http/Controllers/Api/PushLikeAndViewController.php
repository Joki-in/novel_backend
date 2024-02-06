<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
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
}
