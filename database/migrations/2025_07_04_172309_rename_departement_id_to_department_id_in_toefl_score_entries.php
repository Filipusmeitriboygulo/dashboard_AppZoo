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
        Schema::table('toefl_score_entries', function (Blueprint $table) {
            $table->renameColumn('departement_id', 'department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toefl_score_entries', function (Blueprint $table) {
            $table->renameColumn('department_id', 'departement_id');
        });
    }
};
