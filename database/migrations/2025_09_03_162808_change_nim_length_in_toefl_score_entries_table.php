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
        // Mengubah tabel toefl_score_entries
        Schema::table('toefl_score_entries', function (Blueprint $table) {
            // Mengubah kolom 'nim' menjadi string dengan panjang 14
            $table->string('nim', 14)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom 'nim' ke panjang semula (12) jika di-rollback
        Schema::table('toefl_score_entries', function (Blueprint $table) {
            $table->string('nim', 12)->change();
        });
    }
};
