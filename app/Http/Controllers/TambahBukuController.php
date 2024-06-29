<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TambahBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $buku = Buku::where('penulis_id', Auth::id())->get();
        return view('page.user.tambahkan-buku', compact('buku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'genre' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  // Maksimal 2MB untuk gambar
            'adult_content' => 'required|boolean',
        ]);

        $coverPath = null;

        // Cek apakah ada file cover yang diupload
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverName = time() . '.' . $cover->getClientOriginalExtension();
            $coverPath = 'coverbuku/' . $coverName;

            // Pindahkan file ke direktori yang diinginkan
            $cover->move(public_path('coverbuku'), $coverName);
        }

        Buku::create([
            'judul' => $request->judul,
            'sinopsis' => $request->sinopsis,
            'genre' => $request->genre,
            'cover' => $coverName,  // Simpan path foto yang telah dipindahkan
            '18+' => $request->adult_content,
            'status' => 'belum diterima', // Status default
            'penulis_id' => Auth::id(),  // Ambil ID penulis dari user yang sedang login
        ]);

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        // Cek apakah pengguna yang mencoba menghapus buku adalah penulis buku tersebut
        if ($buku->penulis_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk menghapus buku ini.');
        }

        // Hapus file cover jika ada
        if ($buku->cover) {
            $coverPath = public_path('coverbuku/' . $buku->cover);
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        // Hapus semua isi yang terkait dengan buku ini
        $buku->isi()->delete();

        // Hapus semua komentar yang terkait dengan buku ini
        $buku->komentar()->delete();

        // Hapus data buku dari database
        $buku->delete();

        return redirect()->back()->with('success', 'Buku dan data terkait berhasil dihapus!');
    }

}
