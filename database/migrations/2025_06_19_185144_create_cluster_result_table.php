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
        Schema::create('cluster_result', function (Blueprint $table) {
            $table->id('cluster_id');
            $table->foreignId('upload_id')->constrained('upload_log')->onDelete('cascade');
            $table->integer('no');
            $table->string('nama');
            $table->string('nim');
            $table->string('prodi');
            $table->string('kelas');
            $table->integer('listening');
            $table->integer('structure');
            $table->integer('reading');
            $table->integer('total');
            $table->integer('cluster');
            $table->float('membership_cluster1');
            $table->float('membership_cluster2');
            $table->float('membership_cluster3');
            $table->text('insight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster_result');
    }
};
