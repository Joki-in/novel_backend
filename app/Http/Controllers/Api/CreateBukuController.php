<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateBukuController extends Controller
{
    public function storeBuku(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string',
                'sinopsis' => 'required|string',
                'penulis_id' => 'required|exists:users,id',
                '18+' => 'required|integer',
                'genre' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $data = $request->only(['judul', 'sinopsis', 'penulis_id', 'genre', '18+']);

        try {
            $buku = Buku::create($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Data buku berhasil disimpan'], 201);
    }

    public function updateCover(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|exists:buku,id',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 untuk batasan ukuran file
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $idBuku = $request->input('id_buku');
        $buku = Buku::findOrFail($idBuku);

        // Menghapus cover buku sebelumnya jika ada
        if ($buku->cover) {
            // Hapus file cover buku dari direktori public/coverbuku
            $path = public_path('coverbuku/' . $buku->cover);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Menyimpan file cover yang diunggah
        $file = $request->file('cover');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('coverbuku'), $fileName);

        // Update informasi cover buku di database
        $buku->cover = $fileName;
        $buku->save();

        return response()->json(['message' => 'Cover buku berhasil diperbarui'], 200);
    }
    public function createIsi(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'id_buku' => 'required|exists:buku,id',
            'chapter' => 'required',
            'isi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Membuat data baru untuk model Isi
        $isi = new Isi();
        $isi->id_buku = $request->input('id_buku');
        $isi->chapter = $request->input('chapter');
        $isi->isi = $request->input('isi');
        $isi->save();

        return response()->json(['message' => 'Data isi buku berhasil dibuat'], 201);
    }
}
