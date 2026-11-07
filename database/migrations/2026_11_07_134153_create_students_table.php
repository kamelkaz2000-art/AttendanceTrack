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
            $table->string('full_name', 100);
            $table->string('university_id', 20)->unique();
            $table->string('national_id', 20)->unique();
            $table->date('birth_date');
            $table->string('phone', 15);
            $table->string('email', 100)->nullable()->unique();
            $table->foreignId('department_id')->constrained('departments');
            $table->enum('academic_year', ['1', '2', '3', '4']);
            $table->string('photo', 255)->nullable();
            $table->enum('status', ['active', 'suspended', 'graduated'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
