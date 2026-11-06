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
            // المادة الأولى
            Course::firstOrCreate(
                ['course_code' => 'WP201'],
                [
                    'course_name' => 'برمجة الويب 2',
                    'credit_hours' => 3,
                    'department_id' => $department->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'description' => 'مقرر متقدم في برمجة الويب باستخدام إطار العمل Laravel',
                    'prerequisite_id' => null,
                    'status' => 'active',
                ]
            );

            // المادة الثانية
            Course::firstOrCreate(
                ['course_code' => 'NS302'],
                [
                    'course_name' => 'أمن الشبكات',
                    'credit_hours' => 3,
                    'department_id' => $department->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'description' => 'دراسة منهجيات الثغرات الأمنية ونموذج Cyber Kill Chain',
                    'prerequisite_id' => null,
                    'status' => 'active',
                ]
            );

            // المادة الثالثة
            Course::firstOrCreate(
                ['course_code' => 'DS101'],
                [
                    'course_name' => 'بنى المعطيات والخوارزميات',
                    'credit_hours' => 4,
                    'department_id' => $department->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'description' => 'دراسة هياكل البيانات الأساسية وطرق تحليل الخوارزميات',
                    'prerequisite_id' => null,
                    'status' => 'active',
                ]
            );

            // المادة الرابعة
            Course::firstOrCreate(
                ['course_code' => 'DB202'],
                [
                    'course_name' => 'أنظمة قواعد البيانات',
                    'credit_hours' => 3,
                    'department_id' => $department->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'description' => 'تصميم قواعد البيانات العلائقية وصياغة الاستعلامات المتقدمة',
                    'prerequisite_id' => null,
                    'status' => 'active',
                ]
            );

            // المادة الخامسة
            Course::firstOrCreate(
                ['course_code' => 'SE401'],
                [
                    'course_name' => 'هندسة البرمجيات',
                    'credit_hours' => 3,
                    'department_id' => $department->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'description' => 'دورة حياة تطوير البرمجيات ونماذج الهندسة البرمجية الحديثة',
                    'prerequisite_id' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}