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
        Schema::create('toefl_score_entries', function (Blueprint $table) {
            $table->id('score_id');
            $table->string('nim');
            $table->string('nama');
            $table->string('kelas');
            $table->string('prodi');
            $table->string('jurusan');
            $table->float('listening');
            $table->float('structure');
            $table->float('reading');
            $table->float('total_score');
            $table->foreignId('upload_id')->constrained('upload_log')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toefl_score_entries');
    }
};
