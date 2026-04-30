<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id_produk');
            $table->unsignedBigInteger('id_umkm');
            $table->foreign('id_umkm')->references('id_umkm')->on('umkm')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('gambar')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('id_kategori')->constrained('kategori_produk', 'id_kategori')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
