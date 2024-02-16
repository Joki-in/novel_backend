<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteBukuController extends Controller
{
    public function favoriteBook(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|exists:users,id',
        ]);

        $userId = $request->input('id_user');

        // Mengambil semua data like berdasarkan id_user
        $likes = Like::where('user_id', $userId)->get();

        // Jika tidak ada data like yang sesuai
        if ($likes->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data buku yang sesuai dengan pengguna ini.'], 404);
        }

        // Mengumpulkan id buku dari hasil query
        $bookIds = $likes->pluck('buku_id')->toArray(); // Ubah ke array

        // Mengambil data buku berdasarkan id yang terkumpul
        $books = Buku::whereIn('id', $bookIds)->get();

        // Jika tidak ada data buku yang sesuai
        if ($books->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data buku yang sesuai dengan pengguna ini.'], 404);
        }

        return response()->json($books);
    }

}
