<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_visits', function (Blueprint $table): void {
            $table->foreignId('student_check_in_id')
                ->nullable()
                ->after('student_id')
                ->unique()
                ->constrained('student_check_ins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clinic_visits', function (Blueprint $table): void {
            $table->dropForeign(['student_check_in_id']);
            $table->dropUnique(['student_check_in_id']);
            $table->dropColumn('student_check_in_id');
        });
    }
};