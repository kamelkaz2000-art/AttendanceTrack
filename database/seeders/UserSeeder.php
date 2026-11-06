<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('role_name', 'system_admin')->firstOrFail();

        $employeeRole = Role::where('role_name', 'department_employee')->firstOrFail();

        $department = Department::where('department_name', 'الإدارة')->firstOrFail();

        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => '123456',
                'phone' => '0999999999',
                'role_id' => $adminRole->id,
                'department_id' => $department->id,
                'birth_date' => '1990-01-01',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['username' => 'employee'],
            [
                'full_name' => 'موظف تجريبي',
                'email' => 'employee@example.com',
                'password' => '123456',
                'phone' => '0911111111',
                'role_id' => $employeeRole->id,
                'department_id' => $department->id,
                'birth_date' => '1995-01-01',
                'status' => 'active',
            ]
        );
    }
}