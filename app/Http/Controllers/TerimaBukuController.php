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
        $buku = Buku::where('status', 'belum diterima')
            ->with('penulis')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('page.admin.terimabuku', compact('buku'));
    }


    public function terimaBuku($id)
    {

        $buku = Buku::find($id);


        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }


        $buku->update(['status' => 'diterima']);


        $notification = [
            'title' => 'Selamat!',
            'text' => 'Buku berhasil diterbitkan',
            'type' => 'success',
        ];

        return redirect()->route('terima-buku.index')->with('notification', $notification);
    }
    public function tolakBuku(Request $request, $id)
    {

        $buku = Buku::find($id);


        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan.'], 404);
        }


        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);


        $buku->status = 'ditolak';
        $buku->alasan = $request->alasan;
        $buku->save();


        $notification = [
            'title' => 'Buku Ditolak',
            'text' => 'Buku berhasil ditolak dengan alasan: ' . $request->alasan,
            'type' => 'success',
        ];

        return redirect()->route('terima-buku.index')->with('notification', $notification);
    }
}
