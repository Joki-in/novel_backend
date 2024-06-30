<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search2(Request $request)
    {
        // Mengambil nilai batasan usia dari request
        $umur = $request->input('umur');

        // Menginisialisasi query builder untuk Buku dengan status diterima
        $query = Buku::where('status', 'diterima');

        // Menambahkan kondisi berdasarkan batasan usia
        if ($umur >= 18) {
            // Jika umur >= 18, tidak perlu tambahan kondisi
            $buku = $query->get();
        } else {
            // Jika umur < 18, tambahkan kondisi untuk adult_content
            $buku = $query->where('18+', 0)->get();
        }

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
