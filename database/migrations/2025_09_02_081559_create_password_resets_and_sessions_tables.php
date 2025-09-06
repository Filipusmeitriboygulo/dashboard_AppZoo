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
        // Kode untuk membuat tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Kode untuk membuat tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // 1. Buat kolom user_id dengan tipe UNSIGNED INTEGER agar sama dengan `id` di tabel users
            $table->unsignedInteger('user_id')->nullable();

            // 2. Definisikan relasinya secara manual
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        // Kode untuk menghapus kedua tabel jika migrasi di-rollback
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
