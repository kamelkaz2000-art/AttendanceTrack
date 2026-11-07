<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('lectures');
            $table->foreignId('student_id')->constrained('students');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->string('notes', 255)->nullable();
            $table->timestamp('recorded_at')->useCurrent();

            $table->unique(['lecture_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
