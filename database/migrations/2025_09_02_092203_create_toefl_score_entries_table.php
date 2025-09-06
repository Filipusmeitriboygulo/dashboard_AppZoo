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
            $table->increments('id');
            $table->string('nim', 12);
            $table->string('nama', 100);
            $table->string('kelas', 2);
            $table->string('prodi', 100);
            $table->string('jurusan', 100);

            $table->double('listening');
            $table->double('structure');
            $table->double('reading');
            $table->double('total_score');

            $table->unsignedInteger('upload_id');
            $table->unsignedInteger('study_program_id');
            $table->unsignedInteger('department_id');

            $table->timestamps();

            // Definisi Foreign Key Constraints dengan nama kolom yang sesuai
            $table->foreign('upload_id')->references('id')->on('upload_log')->onDelete('cascade');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
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
