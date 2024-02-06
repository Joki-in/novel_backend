<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
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
