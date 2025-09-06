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
        Schema::create('upload_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('file_name', 255);

            // No. 4: cakupan (enum) - Sesuai permintaan
            $table->enum('cakupan', ['kampus', 'jurusan', 'prodi', 'kelas']);

            $table->string('unit_nama', 100);

            // No. 6: status_klasterisasi (enum) - Sesuai permintaan
            $table->enum('status_klasterisasi', ['sudah', 'belum'])->default('belum');

            $table->dateTime('waktu_upload')->useCurrent();
            $table->longText('cluster_insights')->nullable();

            // Definisi Foreign Key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_log');
    }
};
