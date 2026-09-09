<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_visits', function (Blueprint $table) {
            $table->id();

            $table->string('visit_number', 40)->unique();

            $table->foreignId('student_id')
                ->constrained('students')
                ->restrictOnDelete();

            $table->foreignId('attended_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->dateTime('visited_at');

            $table->enum('status', [
                'in_progress',
                'completed',
                'referred',
                'cancelled',
            ])->default('in_progress');

            $table->string('chief_complaint');
            $table->json('symptoms')->nullable();
            $table->text('symptom_details')->nullable();

            $table->dateTime('symptom_started_at')->nullable();
            $table->string('symptom_duration')->nullable();

            $table->unsignedTinyInteger('pain_scale')->nullable();

            $table->text('medications_taken_before_visit')->nullable();
            $table->text('relevant_medical_history')->nullable();
            $table->text('nurse_notes')->nullable();

            $table->text('final_assessment')->nullable();
            $table->string('final_action')->nullable();
            $table->text('final_notes')->nullable();

            $table->boolean('guardian_contacted')->default(false);
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            $table->index(['student_id', 'visited_at']);
            $table->index(['status', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_visits');
    }
};