<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('course_name', 100);
            $table->string('course_code', 20)->unique();
            $table->integer('credit_hours');

            $table->foreignId('department_id')
                ->constrained('departments');

            $table->foreignId('instructor_id')
                ->constrained('users');

            $table->foreignId('semester_id')
                ->constrained('semesters');

            $table->string('academic_year', 20);
            $table->text('description')->nullable();

            $table->foreignId('prerequisite_id')
                ->nullable()
                ->constrained('courses');

            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
