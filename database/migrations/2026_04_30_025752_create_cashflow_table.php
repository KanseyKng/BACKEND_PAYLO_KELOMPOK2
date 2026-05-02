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
        Schema::create('cashflow', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('id_cashflow');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->enum('jenis', ['Pemasukan', 'Pengeluaran']);
            $table->enum('kategori', ['TopUp', 'Transfer Masuk', 'Transfer Keluar'  , 'QRIS']);
            $table->decimal('jumlah', 15, 2);
            $table->timestamp('tanggal_dibuat')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow');
    }
};
