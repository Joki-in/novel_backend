<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateBukuPageController extends Controller
{
    public function createBukuPageShow(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'id_user' => 'required|numeric|exists:users,id',
            ]);

            $idUser = $request->input('id_user');

            // Mengambil semua data buku milik pengguna dengan ID yang diberikan
            $buku = Buku::where('id_user', $idUser)->get();

            // Memeriksa apakah ada data buku yang tersedia
            if ($buku->isEmpty()) {
                return response()->json([
                    'MESSAGE' => 'Tidak ada data buku yang tersedia untuk pengguna ini.',
                    'DATA' => [],
                ], 404);
            }

            // Mengembalikan data buku dalam format JSON
            return response()->json([
                'MESSAGE' => 'Berhasil mendapatkan data buku.',
                'DATA' => $buku,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ERROR' => 'Terjadi kesalahan saat memproses permintaan.',
                'MESSAGE' => $e->getMessage(),
            ], 500);
        }
    }
}
