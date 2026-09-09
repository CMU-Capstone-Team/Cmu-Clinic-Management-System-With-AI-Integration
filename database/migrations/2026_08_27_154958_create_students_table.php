<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('student_number', 50)->unique();

            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 20)->nullable();

            $table->date('birth_date')->nullable();
            $table->string('sex', 30)->nullable();

            $table->string('course', 100);
            $table->unsignedTinyInteger('year_level');
            $table->string('section', 50)->nullable();

            $table->string('contact_number', 30)->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('address')->nullable();

            $table->string('emergency_contact_name')->nullable();

            $table->string(
                'emergency_contact_relationship',
                100
            )->nullable();

            $table->string(
                'emergency_contact_number',
                30
            )->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index([
                'last_name',
                'first_name',
            ]);

            $table->index([
                'course',
                'year_level',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};