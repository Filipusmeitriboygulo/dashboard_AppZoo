<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->foreignId('toefl_score_id')->constrained()->onDelete('cascade');
            $table->integer('cluster_id');
            $table->decimal('membership_degree', 10, 8);
            $table->string('cluster_label', 50);
            $table->json('algorithm_params')->nullable();
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('batch_id')->references('batch_id')->on('clustering_sessions')->onDelete('cascade');
            $table->index(['batch_id', 'cluster_id', 'toefl_score_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};
