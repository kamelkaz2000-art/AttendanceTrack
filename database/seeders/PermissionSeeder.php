<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allModules = [
            'المستخدمين',
            'المواد',
            'الطلاب',
            'الحضور',
            'الامتحانات والدرجات',
        ];

        $actions = [
            'عرض',
            'إضافة',
            'تعديل',
            'حذف',
        ];

        // صلاحيات مدير النظام: جميع العمليات على جميع الكتل
        $permissions = [
            'system_admin' => [],
            'department_manager' => [
                'إضافة مستخدم',
                'عرض المستخدمين',
                'تعديل مستخدم',
                'حذف مستخدم',
                'تغيير حالة مستخدم',
                'البحث عن المستخدمين',
                'عرض تفاصيل المستخدم',
            ],
            'department_employee' => [
                'إضافة مستخدم',
                'عرض المستخدمين',
                'تعديل مستخدم',
                'حذف مستخدم',
                'تغيير حالة مستخدم',
                'البحث عن المستخدمين',
                'عرض تفاصيل المستخدم',
            ],
        ];

        foreach ($allModules as $module) {
            foreach ($actions as $action) {
                $permissions['system_admin'][] = $action . ' ' . $module;
            }
        }

        // صلاحيات إضافية لمدير النظام
        $permissions['system_admin'][] = 'إدارة الأدوار';
        $permissions['system_admin'][] = 'إدارة الصلاحيات';

        foreach ($permissions as $roleName => $rolePermissions) {
            $role = Role::where('role_name', $roleName)->first();

            if (!$role) {
                continue;
            }

            foreach ($rolePermissions as $permissionName) {

                // ===== إضافة (عمر) لمنع خطأ عند تشغيل migrate:fresh --seed =====
                // الحقل permission_name فريد (UNIQUE) في جدول permissions،
                // وبعض الأسماء مكررة بين الأدوار (مثل "عرض المستخدمين" لمدير النظام ومدير القسم)
                // فإضافة نفس الاسم مرة ثانية كانت تسبب خطأ UniqueConstraintViolation وتوقف باقي الـ Seeders
                // الحل: إذا كان اسم الصلاحية موجوداً مسبقاً نتخطاه (بدون تعديل الكود الأصلي تحت)
                if (Permission::where('permission_name', $permissionName)->exists()) {
                    continue;
                }

                Permission::firstOrCreate([
                    'permission_name' => $permissionName,
                    'role_id' => $role->id,
                ]);
            }
        }
    }
}