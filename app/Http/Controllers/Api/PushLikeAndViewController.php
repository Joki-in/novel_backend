<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

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
            'id_buku' => 'required|exists:buku,id',
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
    public function store(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $request->validate([
                'id_buku' => 'required|exists:buku,id',
                'id_user' => 'required|exists:users,id',
            ]);

            // Buat data Like baru
            $like = Like::create([
                'buku_id' => $request->id_buku,
                'user_id' => $request->id_user,
            ]);

            // Kirimkan respon berhasil
            return response()->json(['message' => 'Like created successfully', 'like' => $like], 201);
        } catch (QueryException $e) {
            // Tangani kesalahan database
            return response()->json(['message' => 'Failed to create like', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            return response()->json(['message' => 'Failed to create like', 'error' => $e->getMessage()], 500);
        }
    }
    public function destroy(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $request->validate([
                'id_buku' => 'required|exists:buku,id',
                'id_user' => 'required|exists:users,id',
            ]);

            // Temukan dan hapus like berdasarkan buku_id dan user_id
            $like = Like::where('buku_id', $request->id_buku)
                ->where('user_id', $request->id_user)
                ->first();

            if ($like) {
                $like->delete();
                // Kirimkan respon berhasil
                return response()->json(['message' => 'Like deleted successfully'], 200);
            } else {
                // Jika tidak ditemukan like yang sesuai
                return response()->json(['message' => 'Like not found'], 404);
            }
        } catch (QueryException $e) {
            // Tangani kesalahan database
            return response()->json(['message' => 'Failed to delete like', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            return response()->json(['message' => 'Failed to delete like', 'error' => $e->getMessage()], 500);
        }
    }

}
