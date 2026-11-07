<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->date('lecture_date');
            $table->time('lecture_time');
            $table->integer('duration');
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('course_id')->constrained('courses');
            $table->foreignId('instructor_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
