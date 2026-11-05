<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
{
    $departments = [
        'الإدارة',
        'الموارد البشرية',
        'تقنية المعلومات',
    ];

    foreach ($departments as $department) {
        Department::firstOrCreate([
            'department_name' => $department,
        ]);
    }
}
}