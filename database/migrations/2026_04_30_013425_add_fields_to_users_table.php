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
        Schema::table('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('no_hp', 15)->unique()->after('email')->nullable();
            $table->text('alamat')->after('no_hp')->nullable();
            $table->enum('role', ['pelanggan/turis', 'super_admin'])->default('pelanggan/turis')->after('alamat');
            $table->string('pin')->after('role');
            $table->timestamp('tanggal_daftar')->useCurrent()->after('pin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
