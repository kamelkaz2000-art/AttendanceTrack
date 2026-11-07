<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| Seeder مستخدمي الكتلة الثانية:
| 1. مدير قسم المواد (صلاحيات كاملة: إضافة، تعديل، حذف، تغيير حالة، عرض التفاصيل)
| 2. موظف قسم المواد (إضافة، تعديل، عرض التفاصيل فقط - بدون حذف أو تغيير حالة)
| 3. مدرسين في قسم الحضور (نحتاجهم لأن كل مادة لها مدرس مسؤول instructor_id)
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;

class CourseUserSeeder extends Seeder
{
    public function run(): void
    {
        // جلب الأدوار الموجودة مسبقاً (من RoleSeeder)
        $managerRole = Role::where('role_name', 'department_manager')->firstOrFail();
        $employeeRole = Role::where('role_name', 'department_employee')->firstOrFail();

        // جلب قسم المواد (القسم الخاص بالكتلة الثانية) وقسم الحضور (قسم المدرسين)
        $coursesDepartment = Department::where('department_name', 'قسم المواد')->firstOrFail();
        $attendanceDepartment = Department::where('department_name', 'قسم الحضور')->firstOrFail();

        // المستخدمين المطلوب إضافتهم
        // كلمة المرور تُشفّر تلقائياً لأن نموذج User يحتوي على 'password' => 'hashed'
        $users = [
            // مدير قسم المواد
            [
                'username' => 'course_manager',
                'full_name' => 'مدير قسم المواد',
                'email' => 'course.manager@example.com',
                'password' => '123456',
                'phone' => '0933333333',
                'role_id' => $managerRole->id,
                'department_id' => $coursesDepartment->id,
                'birth_date' => '1988-05-10',
                'status' => 'active',
            ],
            // موظف قسم المواد
            [
                'username' => 'course_employee',
                'full_name' => 'موظف قسم المواد',
                'email' => 'course.employee@example.com',
                'password' => '123456',
                'phone' => '0944444444',
                'role_id' => $employeeRole->id,
                'department_id' => $coursesDepartment->id,
                'birth_date' => '1996-03-20',
                'status' => 'active',
            ],
            // مدرسين (يظهرون بقائمة "المدرس المسؤول" عند إضافة مادة)
            [
                'username' => 'teacher1',
                'full_name' => 'د. أحمد الخطيب',
                'email' => 'teacher1@example.com',
                'password' => '123456',
                'phone' => '0955555551',
                'role_id' => $employeeRole->id,
                'department_id' => $attendanceDepartment->id,
                'birth_date' => '1980-01-15',
                'status' => 'active',
            ],
            [
                'username' => 'teacher2',
                'full_name' => 'د. سارة العلي',
                'email' => 'teacher2@example.com',
                'password' => '123456',
                'phone' => '0955555552',
                'role_id' => $employeeRole->id,
                'department_id' => $attendanceDepartment->id,
                'birth_date' => '1985-07-22',
                'status' => 'active',
            ],
            [
                'username' => 'teacher3',
                'full_name' => 'م. خالد حسن',
                'email' => 'teacher3@example.com',
                'password' => '123456',
                'phone' => '0955555553',
                'role_id' => $employeeRole->id,
                'department_id' => $attendanceDepartment->id,
                'birth_date' => '1990-11-05',
                'status' => 'active',
            ],
        ];

        // إضافة المستخدم فقط إذا لم يكن اسم المستخدم موجوداً
        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
