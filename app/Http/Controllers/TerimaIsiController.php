<?php

namespace App\Http\Controllers;

use App\Models\Isi;
use Illuminate\Http\Request;

class TerimaIsiController extends Controller
{
    public function index()
    {
        $isi = Isi::where('status', 'belum diterima')->get();

        return view('page.admin.terimaisi', compact('isi'));
    }
    public function terimaIsi($id)
    {
        // Temukan buku berdasarkan ID
        $isi = Isi::find($id);

        // Pastikan buku ditemukan
        if (!$isi) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        // Update status menjadi "diterima"
        $isi->update(['status' => 'diterima']);

        // Berikan respons sukses
        $notification = [
            'title' => 'Selamat!',
            'text' => 'Buku berhasil diterbitkan',
            'type' => 'success',
        ];

        return redirect()->route('terima-isi.index')->with('notification', $notification);
    }
    public function tolakIsi(Request $request, $id)
    {
        // Temukan buku berdasarkan ID
        $isi = Isi::find($id);

        // Pastikan buku ditemukan
        if (!$isi) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }

        // Validasi alasan penolakan
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        // Update status menjadi "ditolak" dan simpan alasan
        $isi->status = 'ditolak';
        $isi->alasan = $request->alasan;
        $isi->save();

        // Berikan respons sukses
        $notification = [
            'title' => 'Buku Ditolak',
            'text' => 'Buku berhasil ditolak dengan alasan: ' . $request->alasan,
            'type' => 'success',
        ];

        return redirect()->route('terima-isi.index')->with('notification', $notification);
    }
}
