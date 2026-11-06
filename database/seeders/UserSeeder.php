<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // نجلب معرّف الدور من اسمه بدل كتابة رقم ثابت (لأن أرقام الأدوار تتغير حسب ترتيب الإدخال)
        $roleId = fn (string $name) => Role::where('role_name', $name)->value('id');

        // كلمة المرور تُشفَّر تلقائياً عبر الـ cast (hashed) في موديل User
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => '123456',
                'phone' => '0999999999',
                'role_id' => $roleId('system_admin'),
                'department_id' => 1,
                'birth_date' => '1990-01-01',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['username' => 'manager'],
            [
                'full_name' => 'مدير القسم',
                'email' => 'manager@example.com',
                'password' => '123456',
                'phone' => '0922222222',
                'role_id' => $roleId('department_manager'),
                'department_id' => 1,
                'birth_date' => '1985-01-01',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['username' => 'employee'],
            [
                'full_name' => 'موظف القسم',
                'email' => 'employee@example.com',
                'password' => '123456',
                'phone' => '0911111111',
                'role_id' => $roleId('department_employee'),
                'department_id' => 1,
                'birth_date' => '1995-01-01',
                'status' => 'active',
            ]
        );
    }
}
