<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name' => 'مدير النظام',
                'email' => 'admin@example.com',
                'password' => '123456',
                'phone' => '0999999999',
                'role_id' => 3,
                'department_id' => 1,
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
                'role_id' => 5,
                'department_id' => 1,
                'birth_date' => '1995-01-01',
                'status' => 'active',
            ]
        );
    }
}
