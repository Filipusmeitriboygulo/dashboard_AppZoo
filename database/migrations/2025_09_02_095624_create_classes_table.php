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
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100); // Kolom untuk nama kelas
            $table->integer('semester');
            $table->unsignedInteger('study_program_id');
            $table->timestamps();

            // Membuat relasi ke tabel study_programs
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
