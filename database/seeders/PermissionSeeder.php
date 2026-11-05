<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'system_admin' => [
                'إضافة مستخدم - مدير النظام',
                'عرض المستخدمين - مدير النظام',
                'تعديل مستخدم - مدير النظام',
                'حذف مستخدم - مدير النظام',
                'تغيير حالة مستخدم - مدير النظام',
                'إدارة الأدوار - مدير النظام',
                'إدارة الصلاحيات - مدير النظام',
            ],

            'department_manager' => [
                'إضافة مستخدم - مدير القسم',
                'عرض المستخدمين - مدير القسم',
                'تعديل مستخدم - مدير القسم',
                'حذف مستخدم - مدير القسم',
                'تغيير حالة مستخدم - مدير القسم',
                'إدارة الأدوار - مدير القسم',
            ],

            'department_employee' => [
                'إضافة مستخدم - موظف القسم',
                'عرض المستخدمين - موظف القسم',
                'تعديل مستخدم - موظف القسم',
                'البحث عن المستخدمين - موظف القسم',
                'عرض تفاصيل المستخدم - موظف القسم',
            ],
        ];

        foreach ($permissions as $roleName => $rolePermissions) {
            $role = Role::where('role_name', $roleName)->first();

            foreach ($rolePermissions as $permissionName) {
                if ($role) {
                Permission::firstOrCreate([
                    'permission_name' => $permissionName,
                    'role_id' => $role->id,
                ]);
                }
            }
        }
    }
}
