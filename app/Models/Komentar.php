<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;
    protected $table = 'komentar'; // Nama tabel

    protected $fillable = ['id_buku', 'id_user', 'komentar']; // Kolom yang dapat diisi

    // Relasi dengan model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
