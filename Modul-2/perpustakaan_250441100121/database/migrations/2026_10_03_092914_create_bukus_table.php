<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')
                ->constrained('kategoris');
            $table->string('judul');
            $table->string('penulis');
            $table->integer('tahun_terbit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};