<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Mendapatkan kata kunci pencarian
        $keyword = $request->judul;

        // Memisahkan kata kunci pencarian menjadi array karakter
        $characters = str_split($keyword);

        // Membuat pola pencarian dengan wildcard untuk setiap karakter
        $searchPattern = '%' . implode('%', $characters) . '%';

        // Melakukan pencarian buku berdasarkan judul dengan menggunakan pola pencarian
        $buku = Buku::where('judul', 'like', $searchPattern)->get();

        // Jika tidak ada data yang ditemukan, ambil semua data buku
        if ($buku->isEmpty()) {
            $buku = Buku::all();
        }

        // Mengembalikan hasil pencarian dalam format JSON
        return response()->json([
            'MESSAGE' => $buku->isEmpty() ? 'Tidak ada data buku yang ditemukan untuk pencarian ini.' : 'Berhasil mendapatkan data buku',
            'DATA' => $buku,
        ]);
    }
    public function search2()
    {
        // Mengambil semua data buku
        $buku = Buku::all();

        // Memeriksa apakah ada data buku yang tersedia
        if ($buku->isEmpty()) {
            return response()->json([
                'MESSAGE' => 'Tidak ada data buku yang tersedia.',
                'DATA' => [],
            ]);
        }

        // Mengembalikan data buku dalam format JSON
        return response()->json([
            'MESSAGE' => 'Berhasil mendapatkan data buku.',
            'DATA' => $buku,
        ]);
    }


}
