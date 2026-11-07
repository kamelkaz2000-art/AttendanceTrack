<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| Seeder الفصول الدراسية: يدخل الفصول (أول، ثاني، صيفي) للعام 2026-2027
| نحتاجها لأن كل مادة يجب أن ترتبط بفصل دراسي (semester_id مفتاح خارجي إجباري)
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        // بيانات الفصول الدراسية مع تاريخ البداية والنهاية
        $semesters = [
            [
                'semester_name' => 'الفصل الأول',
                'start_date' => '2026-09-01',
                'end_date' => '2027-01-15',
            ],
            [
                'semester_name' => 'الفصل الثاني',
                'start_date' => '2027-02-01',
                'end_date' => '2027-06-15',
            ],
            [
                'semester_name' => 'الفصل الصيفي',
                'start_date' => '2027-07-01',
                'end_date' => '2027-08-31',
            ],
        ];

        // firstOrCreate: يضيف الفصل فقط إذا لم يكن موجوداً (لمنع التكرار عند إعادة التشغيل)
        foreach ($semesters as $semester) {
            Semester::firstOrCreate(
                ['semester_name' => $semester['semester_name']],
                $semester
            );
        }
    }
}
