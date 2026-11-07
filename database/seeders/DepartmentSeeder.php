<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'قسم المستخدمين',
            'قسم المواد',
            'قسم الطلاب',
            'قسم الحضور',
            'قسم الامتحانات',
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate([
                'department_name' => $department,
            ]);
        }
    }
}