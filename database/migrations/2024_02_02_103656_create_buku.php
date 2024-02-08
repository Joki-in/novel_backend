<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('sinopsis');
            $table->integer('view')->default(0);
            $table->string('genre');
            $table->string('cover')->nullable();
            $table->integer('18+')->default(0);
            $table->foreignId('penulis_id')->constrained('users');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
        Schema::dropIfExists('isi');
        Schema::dropIfExists('komentar');
    }
};
