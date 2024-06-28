<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isi extends Model
{
    use HasFactory;
    protected $table = 'isi';

    protected $fillable = ['id_buku', 'chapter', 'isi', 'status'];


    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
