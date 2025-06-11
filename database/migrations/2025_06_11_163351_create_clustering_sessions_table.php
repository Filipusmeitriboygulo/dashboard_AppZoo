<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clustering_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->string('session_name');
            $table->enum('data_scope', ['class', 'study_program', 'department', 'campus']);
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->integer('total_data');
            $table->integer('num_clusters');
            $table->json('algorithm_params')->nullable();
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['batch_id', 'status', 'data_scope']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clustering_sessions');
    }
};
