<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Semester;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::first();
        $instructor = User::first();
        $semester = Semester::first();

        if ($department && $instructor && $semester) {
            Course::create([
                'course_name' => 'برمجة الويب 2',
                'course_code' => 'WP201',
                'credit_hours' => 3,
                'department_id' => $department->id,
                'instructor_id' => $instructor->id,
                'semester_id' => $semester->id,
                'academic_year' => '2026-2027',
                'description' => 'مقرر متقدم في برمجة الويب باستخدام إطار العمل Laravel',
                'prerequisite_id' => null,
                'status' => 'active',
            ]);

            Course::create([
                'course_name' => 'أمن الشبكات',
                'course_code' => 'NS302',
                'credit_hours' => 3,
                'department_id' => $department->id,
                'instructor_id' => $instructor->id,
                'semester_id' => $semester->id,
                'academic_year' => '2026-2027',
                'description' => 'دراسة منهجيات الثغرات الأمنية ونموذج Cyber Kill Chain',
                'prerequisite_id' => null,
                'status' => 'active',
            ]);
        }
    }
}