<?php

namespace App\Http\Controllers\Api;

use App\Models\Komentar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class KomentarController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Buat data Komentar baru
            $komentar = Komentar::create([
                'id_buku' => $request->id_buku,
                'id_user' => $request->id_user,
                'komentar' => $request->komentar,
            ]);

            // Kirimkan respon berhasil
            return response()->json(['message' => 'Komentar created successfully', 'komentar' => $komentar], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json(['message' => 'Failed to process the request'], 500); // 500 Internal Server Error
        }
    }

}
