<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name', 50);
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('instructor_id')->constrained('users');
            $table->foreignId('semester_id')->constrained('semesters');
            $table->integer('capacity');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
