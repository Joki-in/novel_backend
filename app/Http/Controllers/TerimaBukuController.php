<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class TerimaBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buku = Buku::where('status', 'belum diterima')->with('penulis')->get();

        return view('page.admin.terimabuku', compact('buku'));
    }
    public function terimaBuku($id)
    {
        // Temukan buku berdasarkan ID
        $buku = Buku::find($id);

        // Pastikan buku ditemukan
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        // Update status menjadi "diterima"
        $buku->update(['status' => 'diterima']);

        // Berikan respons sukses
        $notification = [
            'title' => 'Selamat!',
            'text' => 'Buku berhasil diterbitkan',
            'type' => 'success',
        ];

        return redirect()->route('terima-buku.index')->with('notification', $notification);
    }
    public function tolakBuku(Request $request, $id)
    {
        // Temukan buku berdasarkan ID
        $buku = Buku::find($id);

        // Pastikan buku ditemukan
        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        // Validasi alasan penolakan
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        // Update status menjadi "ditolak" dan simpan alasan
        $buku->status = 'ditolak';
        $buku->alasan = $request->alasan;
        $buku->save();

        // Berikan respons sukses
        $notification = [
            'title' => 'Buku Ditolak',
            'text' => 'Buku berhasil ditolak dengan alasan: ' . $request->alasan,
            'type' => 'success',
        ];

        return redirect()->route('terima-buku.index')->with('notification', $notification);
    }
}
