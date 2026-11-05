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
        Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('course_name', 100);
    $table->string('course_code', 20)->unique();
    $table->integer('credit_hours');
    
    // مفاتيح الربط الخارجية (Foreign Keys)
    $table->unsignedBigInteger('department_id');
    $table->unsignedBigInteger('instructor_id');
    $table->unsignedBigInteger('semester_id');
    
    $table->string('academic_year', 20);
    $table->text('description')->nullable();
    $table->unsignedBigInteger('prerequisite_id')->nullable(); // المادة السابقة
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();

    // القيود والعلاقات الفعليّة في قاعدة البيانات
    $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
    $table->foreign('prerequisite_id')->references('id')->on('courses')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};