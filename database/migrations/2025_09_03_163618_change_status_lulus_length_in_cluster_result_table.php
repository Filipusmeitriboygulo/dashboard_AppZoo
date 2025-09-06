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
        // Mengubah tabel 'cluster_result'
        Schema::table('cluster_result', function (Blueprint $table) {
            // Mengubah kolom 'status_lulus' menjadi string dengan panjang 20
            $table->string('status_lulus', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom 'status_lulus' ke panjang semula (25) jika di-rollback
        Schema::table('cluster_result', function (Blueprint $table) {
            $table->string('status_lulus', 25)->change();
        });
    }
};
