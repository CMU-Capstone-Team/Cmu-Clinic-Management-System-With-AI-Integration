<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triage_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_visit_id')
                ->unique()
                ->constrained('clinic_visits')
                ->cascadeOnDelete();

            $table->longText('ai_summary')->nullable();

            $table->enum('ai_triage_level', [
                'green',
                'yellow',
                'red',
            ])->nullable();

            $table->longText('ai_recommendations')->nullable();
            $table->json('red_flags')->nullable();
            $table->json('missing_questions')->nullable();

            $table->string('ai_model')->nullable();
            $table->dateTime('ai_generated_at')->nullable();

            $table->enum('review_status', [
                'pending',
                'approved',
                'modified',
                'rejected',
            ])->default('pending');

            $table->enum('final_triage_level', [
                'green',
                'yellow',
                'red',
            ])->nullable();

            $table->longText('final_recommendation')->nullable();
            $table->text('reviewer_notes')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triage_results');
    }
};