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
            
        if (!Schema::hasColumn('users', 'otp')) {
            $table->string('otp')->nullable()->after('status');
        }
        if (!Schema::hasColumn('users', 'otp_expiry')) {
            $table->timestamp('otp_expiry')->nullable()->after('otp');
        }
        if (!Schema::hasColumn('users', 'status')) {
            $table->enum('status', ['active', 'banned'])->default('active')->after('pin');
        }
        if (!Schema::hasColumn('users', 'pin')) {
            $table->string('pin')->nullable()->after('role');
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn(['otp', 'otp_expiry', 'status', 'pin']);
        });
    }
};
