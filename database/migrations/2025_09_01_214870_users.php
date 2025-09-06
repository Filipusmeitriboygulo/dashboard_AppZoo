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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 254)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['admin', 'kepala_upa', 'ketua_jurusan', 'ketua_prodi', 'wakil_direktur'])
                ->default('admin');
            $table->string('password');
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('study_program_id')->nullable();

            // --- PENAMBAHAN BARU ---
            // Menambahkan kolom status aktif pengguna
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();

            // Definisi Foreign Key Constraints
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');

            // --- PENAMBAHAN INDEX ---
            // Menambahkan index untuk mempercepat query berdasarkan role dan status aktif
            $table->index(['role', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
