<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditBukuController extends Controller
{
    public function updateCover(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:buku,id',
                'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $buku = Buku::findOrFail($request->id);

            if ($buku->cover) {
                $oldCoverPath = public_path('coverbuku/' . $buku->cover);
                if (file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }
            }

            $cover = $request->file('cover');
            $coverName = $cover->hashName();

            $cover->move(public_path('coverbuku'), $coverName);

            $buku->cover = $coverName;
            $buku->save();

            return response()->json(['message' => 'Foto cover berhasil diupdate', 'id' => $buku->id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function deleteIsiByIdFromBody(Request $request)
    {
        // Validasi input
        $request->validate([
            'isi_id' => 'required|exists:isi,id' // Memastikan bahwa isi_id ada dalam tabel isi
        ]);

        $isiId = $request->isi_id;
        $isi = Isi::find($isiId);
        if ($isi) {
            $isi->delete();
            return response()->json(['message' => 'Data Isi berhasil dihapus'], 200);
        }
        return response()->json(['message' => 'Data Isi tidak ditemukan'], 404);
    }
}
