<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_excuses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_visit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique('clinic_visit_id');

            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('excuse_number')->unique();
            $table->uuid('verification_code')->unique();

            $table->date('excused_from');
            $table->date('excused_until');

            $table->text('reason');
            $table->text('activity_restrictions')->nullable();
            $table->text('remarks')->nullable();

            $table->string('status', 20)->default('issued');
            $table->timestamp('issued_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_excuses');
    }
};