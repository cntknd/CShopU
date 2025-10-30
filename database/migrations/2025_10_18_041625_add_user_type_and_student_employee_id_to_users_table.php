<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('user_type', ['student', 'employee', 'visitor'])->default('student')->after('name');
        $table->string('student_employee_id')->nullable()->unique()->after('user_type');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['user_type', 'student_employee_id']);
    });
}

};