<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kepala_upa', 'ketua_jurusan', 'ketua_prodi', 'wakil_direktur'])
                ->default('admin')
                ->after('email_verified_at');
            $table->unsignedBigInteger('department_id')->nullable()->after('role');
            $table->unsignedBigInteger('study_program_id')->nullable()->after('department_id');
            $table->boolean('is_active')->default(true)->after('study_program_id');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');

            $table->index(['role', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropColumn(['role', 'department_id', 'study_program_id', 'is_active']);
        });
    }
};
