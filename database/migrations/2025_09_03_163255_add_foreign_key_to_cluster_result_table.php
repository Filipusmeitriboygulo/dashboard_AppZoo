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
        Schema::table('cluster_result', function (Blueprint $table) {
            // Menambahkan kembali foreign key ke tabel toefl_score_entries
            $table->foreign('toefl_score_entry_id')
                ->references('id')
                ->on('toefl_score_entries')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cluster_result', function (Blueprint $table) {
            // Menghapus foreign key jika migrasi di-rollback
            $table->dropForeign(['toefl_score_entry_id']);
        });
    }
};
