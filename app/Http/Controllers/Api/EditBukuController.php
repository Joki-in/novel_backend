<?php

namespace App\Http\Controllers\Api;

use App\Models\Isi;
use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

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

        $request->validate([
            'isi_id' => 'required|exists:isi,id'
        ]);

        $isiId = $request->isi_id;
        $isi = Isi::find($isiId);
        if ($isi) {
            $isi->delete();
            return response()->json(['message' => 'Data Isi berhasil dihapus'], 200);
        }
        return response()->json(['message' => 'Data Isi tidak ditemukan'], 404);
    }
    public function createIsi(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'id_buku' => 'required|exists:buku,id',
                'chapter' => 'required',
                'isi' => 'required',
            ]);

            // Membuat data baru
            $isi = Isi::create($validatedData);

            return response()->json(['message' => 'Data isi berhasil dibuat', 'data' => $isi], 201);
        } catch (\Exception $e) {
            // Tangani jika terjadi error
            return response()->json(['message' => 'Gagal membuat data isi', 'error' => $e->getMessage()], 500);
        }
    }
    public function editIsi(Request $request)
    {
        try {
            // Validasi input
            $validator = $request->validate([
                'id' => 'string',
                'chapter' => 'required',
                'isi' => 'required',
            ]);

            // Cari data isi berdasarkan ID
            $isi = Isi::findOrFail($request->id);

            // Update data isi
            $isi->chapter = $request->chapter;
            $isi->isi = $request->isi;
            $isi->save();

            return response()->json(['message' => 'Data isi berhasil diperbarui'], 200);
        } catch (ValidationException $e) {
            // Tangani jika validasi gagal
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            // Tangani jika terjadi error lainnya
            return response()->json(['message' => 'Gagal memperbarui data isi'], 500);
        }
    }
    public function updateBuku(Request $request)
    {
        try {
            // Validasi request
            $validatedData = $request->validate([
                'id' => 'string',
                'judul' => 'required|string',
                'sinopsis' => 'required|string',
                'genre' => 'required|string',
                '18+' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada validasi data.',
                'errors' => $e->errors()
            ], 422);
        }

        // Cari data buku berdasarkan ID
        $buku = Buku::findOrFail($validatedData['id']);

        // Update data kecuali kolom 'view', 'penulis_id', dan 'cover'
        $updateData = collect($validatedData)->except(['id', 'view', 'penulis_id', 'cover'])->toArray();
        $buku->update($updateData);

        // Response
        return response()->json([
            'message' => 'Data buku berhasil diupdate.',
            'data' => $buku
        ], 200);
    }

}
