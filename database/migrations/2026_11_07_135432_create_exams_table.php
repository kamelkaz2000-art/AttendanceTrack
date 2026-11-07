<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_type_id')->constrained('exam_types');
            $table->date('exam_date');
            $table->time('exam_time');
            $table->integer('duration');
            $table->decimal('max_grade', 5, 2);
            $table->decimal('min_grade', 5, 2);
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('course_id')->constrained('courses');
            $table->string('room', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
