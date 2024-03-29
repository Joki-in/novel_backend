<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use App\Models\Like;
use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CreateBukuPageController extends Controller
{
    public function createBukuPageShow(Request $request)
    {
        try {

            $request->validate([
                'id_user' => 'required|numeric|exists:users,id',
            ]);

            $idUser = $request->input('id_user');


            $buku = Buku::where('penulis_id', $idUser)->get();


            if ($buku->isEmpty()) {
                return response()->json([
                    'MESSAGE' => 'Tidak ada data buku yang tersedia untuk pengguna ini.',
                    'DATA' => [],
                ], 404);
            }


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

        $id = $request->input('id_buku');


        $buku = Buku::find($id);
        if (!$buku) {
            return response()->json(['error' => 'Data not found'], 404);
        }


        if ($buku->cover) {
            $coverPath = public_path('coverbuku/' . $buku->cover);
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }


        Isi::where('id_buku', $id)->delete();


        Komentar::where('id_buku', $id)->delete();


        Like::where('buku_id', $id)->delete();


        $buku->delete();

        return response()->json(['message' => 'Data has been deleted successfully']);
    }

}
