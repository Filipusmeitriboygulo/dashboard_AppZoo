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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); 
            $table->timestamps();
            $table->date('tanggal_pesanan');
            $table->bigInteger('harga');
            $table->integer('jumlah_tiket');
            // $table->unsignedBigInteger('id_pembeli');
            // $table->unsignedBigInteger('id_tiket');

            // // Foreign key constraints
            // $table->foreign('id_pembeli')->references('id')->on('pembelis')->onUpdate('cascade')->onDelete('restrict');
            // $table->foreign('id_tiket')->references('id')->on('tikets')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
