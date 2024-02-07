<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IsiController extends Controller
{
    public function isiByIdBuku(Request $request)
    {
        try {
            // Mengambil ID buku dari body request
            $id = $request->input('id');

            // Mengambil data isi berdasarkan ID buku
            $isi = Isi::where('id', $id)->get();

            // Mengembalikan respons JSON dengan data isi
            return response()->json([
                'success' => true,
                'data' => $isi
            ], 200);
        } catch (\Exception $e) {
            // Jika isi tidak ditemukan
            return response()->json([
                'success' => false,
                'data' => null
            ], 404);
        }
    }
}
