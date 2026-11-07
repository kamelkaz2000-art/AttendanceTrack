<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
{
    $roles = [
        ['role_name' => 'system_admin', 'description' => 'مدير النظام'],
        ['role_name' => 'department_manager', 'description' => 'مدير قسم'],
        ['role_name' => 'department_employee', 'description' => 'موظف قسم'],
        ['role_name' => 'student', 'description' => 'طالب'],
    ];

    foreach ($roles as $role) {
        Role::updateOrCreate(
            ['role_name' => $role['role_name']],
            ['description' => $role['description']]
        );
    }
}
}
