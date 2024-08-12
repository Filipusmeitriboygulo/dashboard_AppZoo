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
        Schema::table('pembayarans', function (Blueprint $table) {
            // Menambahkan foreign key id_pesanan
            $table->foreignId('id_pesanan')->constrained('pesanans')->onDelete('cascade');

            // Mengganti field tanggal_pembayaran dengan metode_pembayaran
            $table->dropColumn('tanggal_pembayaran');
            $table->string('metode_pembayaran')->after('total_harga');

            // Menambahkan field transaction_id
            $table->string('transaction_id')->after('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Menghapus foreign key id_pesanan
            $table->dropForeign(['id_pesanan']);
            $table->dropColumn('id_pesanan');

            // Mengembalikan tanggal_pembayaran dan menghapus metode_pembayaran
            $table->dropColumn('metode_pembayaran');
            $table->date('tanggal_pembayaran')->after('total_harga');

            // Menghapus field transaction_id
            $table->dropColumn('transaction_id');
        });
    }
};
