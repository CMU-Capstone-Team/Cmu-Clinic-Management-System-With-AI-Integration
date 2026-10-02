<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('course')->nullable()->change();
            $table->integer('year_level')->nullable()->change();

            $table->string('password')->nullable();
            $table->string('status')->nullable();
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'status',
                'remember_token',
            ]);
        });

        // Keep profile fields nullable because pending
        // students may not have completed them yet.
    }
};