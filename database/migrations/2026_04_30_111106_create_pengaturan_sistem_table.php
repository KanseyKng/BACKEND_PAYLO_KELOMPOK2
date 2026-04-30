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
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id_pengaturan');
            $table->decimal('batas_transfer', 15, 2)->default(1000000);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->string('nama_aplikasi')->default('PAYLO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};
