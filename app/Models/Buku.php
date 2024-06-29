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
        'status',
        'alasan',
        'penulis_id',
    ];

    // Relasi dengan model User (Penulis buku)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }
    public function isi()
    {
        return $this->hasMany(Isi::class, 'id_buku');
    }
    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_buku');
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($buku) {
            // Hapus semua isi yang terkait dengan buku ini
            $buku->isi()->delete();

            // Hapus semua komentar yang terkait dengan buku ini
            $buku->komentar()->delete();
        });
    }
}
