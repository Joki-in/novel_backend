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
        'like',
        'view',
        'cover',
        'penulis_id',
    ];

    // Relasi dengan model User (Penulis buku)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }
}
