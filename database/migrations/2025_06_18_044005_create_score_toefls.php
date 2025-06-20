<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('score', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toefl_file_id')->constrained('toefl_files')->onDelete('cascade');
            $table->string('nama');
            $table->string('nim_mahasiswa');
            $table->string('jurusan')->nullable();
            $table->string('prodi')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('listening_score')->nullable();
            $table->integer('structure_score')->nullable();
            $table->integer('reading_score')->nullable();
            $table->integer('listening')->nullable();
            $table->integer('structure')->nullable();
            $table->integer('reading')->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('score');
    }
};
