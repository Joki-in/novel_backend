<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku'; // Nama tabel yang sesuai

    protected $fillable = [
        'judul',
        'sinopsis',
        'view',
        'genre',
        '18+',
        'cover',
        'penulis_id',
    ];

    // Relasi dengan model User (Penulis buku)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }
}
