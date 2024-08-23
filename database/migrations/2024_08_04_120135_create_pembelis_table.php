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
        Schema::create('pembelis', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nama');
            $table->enum('status', ['unpaid', 'paid']);
            $table->string('nohp');
            $table->foreignId('id_pesanan')->constrained('pesanans')->onDelete('cascade');
            $table->timestamps();
            // $table->unsignedBigInteger('id_pesanan');
            // $table->foreign('id_pesanan')->references('id')->on('pesanans')->onUpdate('cascade')->onDelete('restrict');
            // $table->foreign('id_pesanan')->references('id')->on('pesanans')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelis');
    }
};
