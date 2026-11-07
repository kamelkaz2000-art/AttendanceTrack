<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('section_id')->constrained('sections');
            $table->date('registration_date');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['student_id', 'section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_section');
    }
};
