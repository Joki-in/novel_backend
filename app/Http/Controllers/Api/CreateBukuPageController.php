<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use App\Models\Like;
use App\Models\Komentar;
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
            $buku = Buku::where('penulis_id', $idUser)->get();

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
    public function deleteDataByBukuId(Request $request)
    {
        // Mengekstrak id_buku dari body permintaan
        $id = $request->input('id_buku');

        // Cek apakah data buku tersedia
        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Menghapus data dari model Isi berdasarkan id_buku
        Isi::where('id_buku', $id)->delete();

        // Menghapus data dari model Komentar berdasarkan id_buku
        Komentar::where('id_buku', $id)->delete();

        // Menghapus data dari model Like berdasarkan buku_id
        Like::where('buku_id', $id)->delete();

        // Menghapus data dari model Buku berdasarkan id_buku
        $buku->delete();

        return response()->json(['message' => 'Data has been deleted successfully']);
    }
}
