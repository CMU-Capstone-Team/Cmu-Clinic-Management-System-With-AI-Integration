<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'medical_profiles',
            function (Blueprint $table) {
                $table->id();

                $table->foreignId('student_id')
                    ->unique()
                    ->constrained('students')
                    ->cascadeOnDelete();

                $table->string('blood_type', 10)->nullable();

                $table->enum('allergy_status', [
                    'unknown',
                    'none_known',
                    'has_allergies',
                ])->default('unknown');

                $table->text('allergies')->nullable();
                $table->text('current_medications')->nullable();
                $table->text('existing_conditions')->nullable();
                $table->text('past_surgeries')->nullable();
                $table->text('family_medical_history')->nullable();
                $table->text('immunization_notes')->nullable();
                $table->text('additional_notes')->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_profiles');
    }
};