<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('students', 'email_verified_at')) {
            Schema::table('students', fn (Blueprint $t) => $t->timestamp('email_verified_at')->nullable());
        }
        Schema::create('student_email_otps', function (Blueprint $t) {
            $t->string('email')->primary();
            $t->string('token', 64);
            $t->text('payload');
            $t->string('code_hash');
            $t->unsignedInteger('attempts')->default(0);
            $t->unsignedInteger('sends')->default(0);
            $t->timestamp('expires_at');
            $t->timestamp('locked_until');
            $t->timestamp('sent_at')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('student_email_otps'); }
};
