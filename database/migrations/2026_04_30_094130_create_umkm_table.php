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
        Schema::create('umkm', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id_umkm');
            $table->string('nama_umkm');
            $table->text('alamat');
            $table->string('no_hp');
            $table->text('deskripsi')->nullable();
            $table->string('link_lokasi_umkm')->nullable();
            $table->enum('rating', ['1','2','3','4','5'])->nullable();
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
