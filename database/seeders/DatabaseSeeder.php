<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,

            // ===== إضافة الكتلة الثانية (إدارة المواد) - عمر =====
            // الترتيب مهم: الفصول والأقسام والمستخدمين قبل المواد لأن المواد مرتبطة بها
            SemesterSeeder::class,           // الفصول الدراسية (أول، ثاني، صيفي)
            AcademicDepartmentSeeder::class, // الأقسام الأكاديمية التي تتبع لها المواد
            CourseUserSeeder::class,         // مدير وموظف قسم المواد + المدرسين
            CourseSeeder::class,             // المواد الدراسية التجريبية
        ]);
    }
}