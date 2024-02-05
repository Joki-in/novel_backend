<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BukuController extends Controller
{
    public function TopLikedBooks()
    {
        $topLikedBooks = Buku::select('buku.*', DB::raw('COUNT(like.id) as `like`'))
            ->leftJoin('like', 'buku.id', '=', 'like.buku_id')
            ->groupBy('buku.id')
            ->orderByDesc('like')
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $topLikedBooks,
        ]);
    }



    public function topView()
    {
        // Mengambil 3 buku dengan jumlah view terbanyak, diurutkan secara descending
        $bukuTerbanyakView = Buku::orderByDesc('view')->limit(4)->get();

        return response()->json(['status' => 'success', 'data' => $bukuTerbanyakView], 200);
    }
}
