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
            $table->json('membership')->nullable()->after('cluster');
        });

        Schema::table('cluster_result', function (Blueprint $table) {
            $table->dropColumn(['membership_cluster1', 'membership_cluster2', 'membership_cluster3']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('json', function (Blueprint $table) {
            //
            $table->float('membership_cluster1')->nullable();
            $table->float('membership_cluster2')->nullable();
            $table->float('membership_cluster3')->nullable();

            $table->dropColumn('membership');
        });
    }
};
