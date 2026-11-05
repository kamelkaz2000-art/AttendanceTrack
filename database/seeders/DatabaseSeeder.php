<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,    // الأقسام أولاً
            PermissionSeeder::class,
            SemesterSeeder::class,    // الفصول الدراسية
            UserSeeder::class,        // المستخدمون (يعتمدون على الأدوار والأقسام)
            CourseSeeder::class,      // المقررات (تعتمد على الأقسام، المدرسين، والفصول)
        ]);
    }
}