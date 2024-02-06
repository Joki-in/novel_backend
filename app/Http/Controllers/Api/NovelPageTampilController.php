<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NovelPageTampilController extends Controller
{
    public function BukuDanPenulis(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|integer', // Menyatakan bahwa id_buku harus ada dan harus berupa angka bulat
        ]);

        // Jika validasi gagal, kembalikan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $id_buku = $request->id_buku;

            // Mendapatkan daftar buku dengan nama pengguna (user) yang terkait
            $bukus = Buku::with('penulis')
                ->where('id', $id_buku)
                ->get();

            return response()->json([
                'status' => 'success',
                'bukus' => $bukus,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data buku.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function IsiBerdasarkanBuku(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|integer', // Menyatakan bahwa id_buku harus ada dan harus berupa angka bulat
        ]);

        // Jika validasi gagal, kembalikan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $id_buku = $request->id_buku;

            // Mendapatkan isi berdasarkan id_buku
            $isi = Isi::whereHas('buku', function ($query) use ($id_buku) {
                $query->where('id', $id_buku);
            })->get();

            return response()->json([
                'status' => 'success',
                'isi' => $isi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data isi buku.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getKomentarByBukuId(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_buku' => 'required|exists:buku,id',
            ]);

            // Mendapatkan daftar komentar berdasarkan id_buku dan menggabungkannya dengan data pengguna (user)
            $komentar = Komentar::with('user')
                ->where('id_buku', $request->id_buku)
                ->get();

            return response()->json([
                'status' => 'success',
                'komentar' => $komentar,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data komentar.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
