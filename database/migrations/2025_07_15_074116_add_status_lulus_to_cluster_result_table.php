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
        // Schema::table('cluster_result', function (Blueprint $table) {
        //     $table->string('status_lulus')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('cluster_result', function (Blueprint $table) {
        //     $table->dropColumn('status_lulus');
        // });
    }
};
