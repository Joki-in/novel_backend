<?php

namespace App\Http\Controllers;

use App\Models\Isi;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TambahIsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id_buku)
    {
        $id_buku = Crypt::decrypt($id_buku);
        $bukus = Buku::where('id', $id_buku)->get();
        $isi = Isi::where('id_buku', $id_buku)->with('buku')->get();

        return view('page.user.tambahkan-isi', compact('isi', 'bukus'));
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
        try {
            // Validasi input
            $validatedData = $request->validate([
                'buku_id' => 'required|exists:buku,id',
                'chapter' => 'required|string|max:255',
                'isi' => 'required|string',
            ]);

            // Simpan data
            Isi::create([
                'id_buku' => $validatedData['buku_id'],
                'chapter' => $validatedData['chapter'],
                'isi' => $validatedData['isi'],
            ]);

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (ModelNotFoundException $e) {
            // Menangani jika buku tidak ditemukan
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        } catch (QueryException $e) {
            // Menangani kesalahan query database
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Menangani kesalahan umum
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga: ' . $e->getMessage());
        }
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
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'buku_id' => 'required|exists:buku,id',
                'chapter' => 'required|string|max:255',
                'isi' => 'required|string',
            ]);

            // Temukan data berdasarkan ID
            $isi = Isi::findOrFail($id);

            // Update data
            $isi->update([
                'id_buku' => $validatedData['buku_id'],
                'chapter' => $validatedData['chapter'],
                'isi' => $validatedData['isi'],
            ]);

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (ModelNotFoundException $e) {
            // Menangani jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (QueryException $e) {
            // Menangani kesalahan query database
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Menangani kesalahan umum
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Temukan data berdasarkan ID
            $isi = Isi::findOrFail($id);

            // Hapus data
            $isi->delete();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            // Menangani jika data tidak ditemukan
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        } catch (QueryException $e) {
            // Menangani kesalahan query database
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        } catch (\Exception $e) {
            // Menangani kesalahan umum
            return redirect()->back()->with('error', 'Terjadi kesalahan yang tidak terduga: ' . $e->getMessage());
        }
    }

}
