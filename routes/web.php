<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController; // إضافة الكتلة الثانية: متحكم المواد

// تسجيل الدخول والخروج
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// لوحة التحكم
Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('dashboard');

// إدارة المستخدمين
Route::middleware([
    'role:system_admin,department_manager,department_employee',
    'department:قسم المستخدمين',
])->group(function () {

    // عرض جميع المستخدمين
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    // إضافة مستخدم
    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    // عرض تفاصيل مستخدم
    Route::get('/users/{id}', [UserController::class, 'show'])
        ->name('users.show');

    // تعديل مستخدم
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->name('users.update');

    // الحذف وتغيير الحالة: مدير القسم ومدير النظام فقط
    Route::middleware(['role:system_admin,department_manager'])->group(function () {

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggleStatus');
    });
});

/*
|==========================================================================
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|==========================================================================
*/

/*
|--------------------------------------------------------------------------
| الوظيفة الأولى (Assignment 1) - مسارات المواد ببيانات وهمية (موضوعة كتعليق)
|--------------------------------------------------------------------------
| الأدوار في الوظيفة الأولى كانت ثابتة: manager (مدير) و employee (موظف)
| الحذف وتغيير الحالة محميان بـ role:manager

Route::middleware(['role:manager,employee'])->group(function () {

    // عرض قائمة المواد
    Route::get('/courses', [CourseController::class, 'index'])
        ->name('courses.index');

    // عرض نموذج إضافة مادة
    Route::get('/courses/create', [CourseController::class, 'create'])
        ->name('courses.create');

    // حفظ مادة جديدة
    Route::post('/courses', [CourseController::class, 'store'])
        ->name('courses.store');

    // عرض تفاصيل مادة
    Route::get('/courses/{id}', [CourseController::class, 'show'])
        ->name('courses.show');

    // عرض نموذج تعديل مادة
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])
        ->name('courses.edit');

    // تحديث بيانات مادة
    Route::put('/courses/{id}', [CourseController::class, 'update'])
        ->name('courses.update');

    // حذف مادة (للمدير فقط)
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])
        ->name('courses.destroy')
        ->middleware('role:manager');

    // تغيير حالة المادة (للمدير فقط)
    Route::patch('/courses/{id}/toggle-status', [CourseController::class, 'toggleStatus'])
        ->name('courses.toggleStatus')
        ->middleware('role:manager');
});
--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| الوظيفة الثانية (Assignment 2) - مسارات المواد الفعّالة
|--------------------------------------------------------------------------
| role       : يسمح فقط لمدير النظام ومدير القسم وموظف القسم
| department : يسمح فقط لمستخدمي "قسم المواد" (ومدير النظام لديه وصول لكل الأقسام)
*/
Route::middleware([
    'role:system_admin,department_manager,department_employee',
    'department:قسم المواد',
])->group(function () {

    // الصفحة الرئيسية للكتلة مع لوحة التحكم
    // (معرّفة قبل /courses/{id} حتى لا يعتبر Laravel كلمة home رقم مادة)
    Route::get('/courses/home', [CourseController::class, 'home'])
        ->name('courses.home');

    // عرض جميع المواد
    Route::get('/courses', [CourseController::class, 'index'])
        ->name('courses.index');

    // إضافة مادة: عرض النموذج ثم الحفظ
    Route::get('/courses/create', [CourseController::class, 'create'])
        ->name('courses.create');

    Route::post('/courses', [CourseController::class, 'store'])
        ->name('courses.store');

    // عرض تفاصيل مادة (id أرقام فقط)
    Route::get('/courses/{id}', [CourseController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('courses.show');

    // تعديل مادة: عرض النموذج ثم حفظ التعديلات
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])
        ->where('id', '[0-9]+')
        ->name('courses.edit');

    Route::put('/courses/{id}', [CourseController::class, 'update'])
        ->where('id', '[0-9]+')
        ->name('courses.update');

    // الحذف وتغيير الحالة: مدير القسم ومدير النظام فقط (الموظف يحصل على خطأ 403)
    Route::middleware(['role:system_admin,department_manager'])->group(function () {

        Route::delete('/courses/{id}', [CourseController::class, 'destroy'])
            ->where('id', '[0-9]+')
            ->name('courses.destroy');

        Route::patch('/courses/{id}/toggle-status', [CourseController::class, 'toggleStatus'])
            ->where('id', '[0-9]+')
            ->name('courses.toggleStatus');
    });
});
