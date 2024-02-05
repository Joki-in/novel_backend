<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $table = 'like'; // Jika nama tabel tidak berupa bentuk jamak dari model, Anda perlu menentukan nama tabel secara eksplisit.

    protected $fillable = ['buku_id', 'user_id']; // Atau $guarded jika Anda lebih memilih pendekatan tersebut.

    // Relasi dengan model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
