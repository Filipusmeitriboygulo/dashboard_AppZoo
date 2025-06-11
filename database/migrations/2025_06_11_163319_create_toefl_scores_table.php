<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toefl_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('test_date');
            $table->integer('listening_score');
            $table->integer('structure_score');
            $table->integer('reading_score');
            $table->integer('total_score');
            $table->string('dataset_batch')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['student_id', 'test_date', 'dataset_batch']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toefl_scores');
    }
};
