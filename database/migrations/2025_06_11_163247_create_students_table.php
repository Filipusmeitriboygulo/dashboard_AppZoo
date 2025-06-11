<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique(); // NIM
            $table->string('name');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('study_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index(['student_id', 'class_id', 'study_program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
