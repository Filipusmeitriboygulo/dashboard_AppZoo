<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->unsignedBigInteger('head_id')->nullable();
            $table->timestamps();

            $table->foreign('head_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['department_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};
