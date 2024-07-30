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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('status_pembayaran');
            $table->bigInteger('total_harga');
            $table->date('tanggal_pembayaran');
            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')->references('id')->on('pesanans')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
