<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_check_ins', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('student_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('student_number');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->date('birth_date');
            $table->string('sex', 20);

            $table->string('course');
            $table->unsignedTinyInteger('year_level');
            $table->string('section')->nullable();

            $table->text('address');
            $table->string('contact_number', 30);

            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_number', 30);

            $table->date('queue_date')->nullable();
            $table->unsignedInteger('queue_number')->nullable();

            $table->string('status', 20)->default('pending');

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['queue_date', 'queue_number'],
                'student_check_ins_daily_queue_unique'
            );

            $table->index(
                ['status', 'submitted_at'],
                'student_check_ins_status_submitted_index'
            );

            $table->index(
                ['student_number', 'status'],
                'student_check_ins_student_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_check_ins');
    }
};