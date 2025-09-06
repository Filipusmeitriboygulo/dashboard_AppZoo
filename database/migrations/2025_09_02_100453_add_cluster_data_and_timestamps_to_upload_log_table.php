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
        Schema::table('upload_log', function (Blueprint $table) {
            // No. 8: Menambahkan kolom cluster_data (longtext)
            $table->longText('cluster_data')->nullable()->after('status_klasterisasi');

            // No. 9 & 10: Menambahkan kolom created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upload_log', function (Blueprint $table) {
            $table->dropColumn('cluster_data');
            $table->dropTimestamps();
        });
    }
};
