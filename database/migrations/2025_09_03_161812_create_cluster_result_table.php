<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_result', function (Blueprint $table) {
            $table->increments('cluster_id');
            $table->unsignedInteger('upload_id');
            
            // Kolom ini sekarang hanya integer biasa, tanpa foreign key
            $table->unsignedInteger('toefl_score_entry_id');
            
            $table->integer('cluster');
            $table->longText('membership')->nullable();
            $table->text('insight')->nullable();
            $table->string('status_lulus', 25);
            $table->timestamps();

            // Hanya satu foreign key yang didefinisikan
            $table->foreign('upload_id')->references('id')->on('upload_log')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_result');
    }
};