<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->string('name');
            // $table->string('academic_year', 10);
            $table->integer('semester');
            $table->timestamps();

            // $table->index(['study_program_id', 'academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
