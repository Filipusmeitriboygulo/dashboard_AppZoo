<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cluster_result', function (Blueprint $table) {
            $schema = Schema::getColumnListing('cluster_result');

            $columnsToDrop = ['nama', 'nim', 'prodi', 'kelas', 'listening', 'structure', 'reading', 'total', 'jurusan'];
            $columnsExist = array_intersect($columnsToDrop, $schema);

            foreach ($columnsExist as $column) {
                $table->dropColumn($column);
            }

            if (!in_array('toefl_score_entry_id', $schema)) {
                $table->foreignId('toefl_score_entry_id')
                    ->constrained('toefl_score_entries')
                    ->onDelete('cascade');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cluster_result', function (Blueprint $table) {
            // Tambahkan kembali kolom yang dihapus

            $table->string('nama')->nullable();
            $table->string('nim')->nullable();
            $table->string('prodi')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('listening')->nullable();
            $table->integer('structure')->nullable();
            $table->integer('reading')->nullable();
            $table->integer('total')->nullable();

            // Hapus foreign key
            $table->dropConstrainedForeignId('toefl_score_entry_id');
        });
    }
};
