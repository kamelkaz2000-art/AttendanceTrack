<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| Seeder المواد الدراسية: يدخل 7 مواد تجريبية مرتبطة بالأقسام والمدرسين والفصول
| بعض المواد لها متطلب سابق (prerequisite) لتجربة علاقة المادة مع نفسها
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // جلب الأقسام الأكاديمية (من AcademicDepartmentSeeder)
        $it = Department::where('department_name', 'هندسة معلوماتية')->firstOrFail();
        $comm = Department::where('department_name', 'هندسة اتصالات')->firstOrFail();
        $cs = Department::where('department_name', 'علوم حاسوب')->firstOrFail();

        // جلب الفصول الدراسية (من SemesterSeeder)
        $first = Semester::where('semester_name', 'الفصل الأول')->firstOrFail();
        $second = Semester::where('semester_name', 'الفصل الثاني')->firstOrFail();
        $summer = Semester::where('semester_name', 'الفصل الصيفي')->firstOrFail();

        // جلب المدرسين (من CourseUserSeeder)
        $teacher1 = User::where('username', 'teacher1')->firstOrFail();
        $teacher2 = User::where('username', 'teacher2')->firstOrFail();
        $teacher3 = User::where('username', 'teacher3')->firstOrFail();

        // المواد مرتبة بحيث تُضاف المادة الأساسية قبل المادة التي تحتاجها كمتطلب سابق
        // الحقل 'prerequisite' هو رمز المادة السابقة، ونحوّله لاحقاً إلى prerequisite_id
        $courses = [
            [
                'course_name' => 'مدخل إلى البرمجة',
                'course_code' => 'CS101',
                'credit_hours' => 3,
                'department_id' => $cs->id,
                'instructor_id' => $teacher1->id,
                'semester_id' => $first->id,
                'academic_year' => '1',
                'description' => 'أساسيات البرمجة والخوارزميات باستخدام لغة C++.',
                'prerequisite' => null,
                'status' => 'active',
            ],
            [
                'course_name' => 'البرمجة المتقدمة',
                'course_code' => 'CS102',
                'credit_hours' => 3,
                'department_id' => $cs->id,
                'instructor_id' => $teacher1->id,
                'semester_id' => $second->id,
                'academic_year' => '1',
                'description' => 'البرمجة غرضية التوجه والمؤشرات وهياكل البيانات البسيطة.',
                'prerequisite' => 'CS101',
                'status' => 'active',
            ],
            [
                'course_name' => 'قواعد البيانات',
                'course_code' => 'DB201',
                'credit_hours' => 4,
                'department_id' => $it->id,
                'instructor_id' => $teacher2->id,
                'semester_id' => $first->id,
                'academic_year' => '2',
                'description' => 'تصميم قواعد البيانات العلائقية ولغة SQL والتطبيع.',
                'prerequisite' => 'CS101',
                'status' => 'active',
            ],
            [
                'course_name' => 'برمجة الويب 1',
                'course_code' => 'WP201',
                'credit_hours' => 3,
                'department_id' => $it->id,
                'instructor_id' => $teacher2->id,
                'semester_id' => $first->id,
                'academic_year' => '2',
                'description' => 'HTML و CSS و JavaScript وأساسيات PHP.',
                'prerequisite' => 'CS102',
                'status' => 'active',
            ],
            [
                'course_name' => 'برمجة الويب 2',
                'course_code' => 'WP202',
                'credit_hours' => 3,
                'department_id' => $it->id,
                'instructor_id' => $teacher3->id,
                'semester_id' => $second->id,
                'academic_year' => '3',
                'description' => 'بناء تطبيقات الويب باستخدام إطار العمل Laravel.',
                'prerequisite' => 'WP201',
                'status' => 'active',
            ],
            [
                'course_name' => 'شبكات الحاسوب',
                'course_code' => 'NT301',
                'credit_hours' => 3,
                'department_id' => $comm->id,
                'instructor_id' => $teacher3->id,
                'semester_id' => $first->id,
                'academic_year' => '3',
                'description' => 'نموذج OSI وبروتوكولات TCP/IP وتصميم الشبكات.',
                'prerequisite' => null,
                'status' => 'active',
            ],
            [
                'course_name' => 'مشروع تخرج تمهيدي',
                'course_code' => 'PR401',
                'credit_hours' => 2,
                'department_id' => $it->id,
                'instructor_id' => $teacher1->id,
                'semester_id' => $summer->id,
                'academic_year' => '4',
                'description' => 'مادة تجريبية معطلة لتجربة تغيير الحالة.',
                'prerequisite' => 'WP202',
                'status' => 'inactive',
            ],
        ];

        foreach ($courses as $course) {
            // تحويل رمز المتطلب السابق إلى رقم المادة (id)
            $prerequisiteCode = $course['prerequisite'];
            unset($course['prerequisite']);

            $course['prerequisite_id'] = $prerequisiteCode
                ? Course::where('course_code', $prerequisiteCode)->value('id')
                : null;

            // إضافة المادة إذا لم يكن رمزها موجوداً
            Course::firstOrCreate(
                ['course_code' => $course['course_code']],
                $course
            );
        }
    }
}
