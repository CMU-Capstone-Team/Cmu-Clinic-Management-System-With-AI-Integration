<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')
                ->nullable()
                ->unique()
                ->after('id');

            $table->enum('role', ['admin', 'nurse', 'doctor'])
                ->default('nurse')
                ->after('password');

            $table->boolean('is_active')
                ->default(true)
                ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'role',
                'is_active',
            ]);
        });
    }
};