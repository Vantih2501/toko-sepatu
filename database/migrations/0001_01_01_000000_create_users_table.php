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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->enum('role', [0, 1, 2])->default(2); // 0 = admin, 1 = super admin, 2 = customer
            $table->boolean('status')->default(1); // 0 = belum aktif, 1 = aktif
            $table->string('password')->nullable();
            $table->string('hp', 20)->nullable();
            $table->string('foto')->nullable();
            $table->string('provinsi_id')->nullable();
            $table->string('kota_id')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
