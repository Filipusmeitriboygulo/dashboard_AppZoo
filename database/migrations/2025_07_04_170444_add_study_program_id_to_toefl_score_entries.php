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
            $table->unsignedBigInteger('study_program_id')->nullable()->after('upload_id');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('toefl_score_entries', function (Blueprint $table) {
            //
        });
    }
};
