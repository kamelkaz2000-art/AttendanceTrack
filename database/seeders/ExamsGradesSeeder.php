<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
| بيانات تجريبية خاصة بالكتلة الخامسة:
| - أنواع الامتحانات
| - حسابات مدير وموظف قسم الامتحانات
| - بيانات تجريبية بسيطة (فصل، مواد، شعب، طلاب) لتجربة رصد الدرجات
|   باستخدام firstOrCreate حتى لا تتعارض مع بيانات الكتل الأخرى
*/

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Role;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamsGradesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. أنواع الامتحانات
        foreach (['نصفي', 'نهائي', 'عملي', 'شفهي'] as $typeName) {
            ExamType::firstOrCreate(['type_name' => $typeName]);
        }

        // 2. حسابات قسم الامتحانات
        $examsDepartment = Department::where('department_name', 'قسم الامتحانات')->firstOrFail();
        $managerRole = Role::where('role_name', 'department_manager')->firstOrFail();
        $employeeRole = Role::where('role_name', 'department_employee')->firstOrFail();

        User::firstOrCreate(
            ['username' => 'exam_manager'],
            [
                'full_name' => 'مدير قسم الامتحانات',
                'email' => 'exam_manager@example.com',
                'password' => '123456',
                'phone' => '0933333333',
                'role_id' => $managerRole->id,
                'department_id' => $examsDepartment->id,
                'birth_date' => '1988-01-01',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['username' => 'exam_employee'],
            [
                'full_name' => 'موظف قسم الامتحانات',
                'email' => 'exam_employee@example.com',
                'password' => '123456',
                'phone' => '0944444444',
                'role_id' => $employeeRole->id,
                'department_id' => $examsDepartment->id,
                'birth_date' => '1996-01-01',
                'status' => 'active',
            ]
        );

        // 3. بيانات تجريبية لازمة لرصد الدرجات
        $this->seedDemoData();
    }

    // بيانات تجريبية: فصل دراسي، مواد، شعب، طلاب، امتحانات ودرجات
    private function seedDemoData(): void
    {
        $instructor = User::where('username', 'admin')->first();
        $coursesDepartment = Department::where('department_name', 'قسم المواد')->first();
        $studentsDepartment = Department::where('department_name', 'قسم الطلاب')->first();

        if (!$instructor || !$coursesDepartment || !$studentsDepartment) {
            return;
        }

        $semester = Semester::firstOrCreate(
            ['semester_name' => 'الفصل الأول 2026-2027'],
            ['start_date' => '2026-09-15', 'end_date' => '2027-01-31']
        );

        // المواد التجريبية
        $coursesData = [
            ['course_code' => 'WP201', 'course_name' => 'برمجة الويب 2', 'credit_hours' => 3],
            ['course_code' => 'DB201', 'course_name' => 'قواعد المعطيات', 'credit_hours' => 3],
        ];

        $courses = [];

        foreach ($coursesData as $data) {
            $courses[$data['course_code']] = Course::firstOrCreate(
                ['course_code' => $data['course_code']],
                [
                    'course_name' => $data['course_name'],
                    'credit_hours' => $data['credit_hours'],
                    'department_id' => $coursesDepartment->id,
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'academic_year' => '2026-2027',
                    'status' => 'active',
                ]
            );
        }

        // الشعب التجريبية
        $sections = [];

        foreach ([['WP201', 'شعبة 1'], ['WP201', 'شعبة 2'], ['DB201', 'شعبة 1']] as [$code, $name]) {
            $sections[] = Section::firstOrCreate(
                ['course_id' => $courses[$code]->id, 'section_name' => $name],
                [
                    'instructor_id' => $instructor->id,
                    'semester_id' => $semester->id,
                    'capacity' => 30,
                ]
            );
        }

        // الطلاب التجريبيون
        $studentNames = [
            'أحمد العلي', 'سارة الحسن', 'محمد الخطيب', 'لينا الشامي',
            'عمر النجار', 'ريم السيد', 'يوسف الأحمد', 'نور الدين حداد',
        ];

        $students = [];

        foreach ($studentNames as $index => $name) {
            $number = $index + 1;

            $students[] = Student::firstOrCreate(
                ['university_id' => '2026' . str_pad($number, 4, '0', STR_PAD_LEFT)],
                [
                    'full_name' => $name,
                    'national_id' => '0101000' . str_pad($number, 4, '0', STR_PAD_LEFT),
                    'birth_date' => '2004-0' . (($number % 9) + 1) . '-15',
                    'phone' => '09' . str_pad($number, 8, '5', STR_PAD_LEFT),
                    'email' => 'student' . $number . '@example.com',
                    'department_id' => $studentsDepartment->id,
                    'academic_year' => '3',
                    'status' => 'active',
                ]
            );
        }

        // تسجيل الطلاب في الشعب: نصف الطلاب في كل شعبة من مادة الويب، والجميع في قواعد المعطيات
        foreach ($students as $index => $student) {
            $wpSection = $index < 4 ? $sections[0] : $sections[1];

            $student->sections()->syncWithoutDetaching([
                $wpSection->id => ['registration_date' => '2026-09-20'],
                $sections[2]->id => ['registration_date' => '2026-09-20'],
            ]);
        }

        // امتحان تجريبي مع درجات (فقط إذا لم توجد امتحانات مسبقاً)
        if (Exam::count() > 0) {
            return;
        }

        $midterm = ExamType::where('type_name', 'نصفي')->first();
        $final = ExamType::where('type_name', 'نهائي')->first();

        $exam = Exam::create([
            'exam_type_id' => $midterm->id,
            'exam_date' => '2026-11-05',
            'exam_time' => '10:00',
            'duration' => 90,
            'max_grade' => 30,
            'min_grade' => 15,
            'section_id' => $sections[0]->id,
            'course_id' => $courses['WP201']->id,
            'room' => 'قاعة 101',
        ]);

        $sampleGrades = [27, 18.5, 12, 24];

        foreach ($sections[0]->students as $index => $student) {
            Grade::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'grade' => $sampleGrades[$index] ?? 20,
                'recorded_date' => '2026-11-08',
            ]);
        }

        // امتحان قادم بدون درجات
        Exam::create([
            'exam_type_id' => $final->id,
            'exam_date' => '2027-01-15',
            'exam_time' => '09:00',
            'duration' => 120,
            'max_grade' => 70,
            'min_grade' => 35,
            'section_id' => $sections[2]->id,
            'course_id' => $courses['DB201']->id,
            'room' => 'المدرج الكبير',
        ]);
    }
}
