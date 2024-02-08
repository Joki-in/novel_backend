<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            $data = Buku::select(
                'buku.id',
                'buku.judul',
                'buku.sinopsis',
                'buku.view',
                'buku.genre',
                'buku.cover',
                'buku.penulis_id',
                DB::raw('COUNT(like.id) as like_count')
            )
                ->leftJoin('like', 'buku.id', '=', 'like.buku_id')
                ->groupBy('buku.id', 'buku.judul', 'buku.sinopsis', 'buku.view', 'buku.genre', 'buku.cover', 'buku.penulis_id')
                ->orderByDesc('like_count')
                ->where('buku.penulis_id', $idUser)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
