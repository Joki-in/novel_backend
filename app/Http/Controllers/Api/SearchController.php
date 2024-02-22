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
        // Mengambil semua data buku
        if ($umur >= 18) {
            $buku = Buku::all();
        } else if ($umur < 18) {
            $buku = Buku::where('18+', 0)->get();
        } else {
            $buku = "tidak ada data buku";
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
