<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamController;      // الكتلة الخامسة: الامتحانات
use App\Http\Controllers\GradeController;     // الكتلة الخامسة: الدرجات

/*
    // Assignment 1 - User Management Routes

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{id}', [UserController::class, 'show'])
        ->name('users.show');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->name('users.destroy');
*/

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
    ->middleware(['role:system_admin'])
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
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
*/

/*
    // الوظيفة الأولى (Assignment 1) - مسارات الامتحانات ببيانات وهمية
    // الحذف محمي بصلاحية المدير فقط

    Route::middleware(['role:manager,employee'])->group(function () {

        Route::get('/exams', [ExamController::class, 'index'])
            ->name('exams.index');

        Route::get('/exams/create', [ExamController::class, 'create'])
            ->name('exams.create');

        Route::post('/exams', [ExamController::class, 'store'])
            ->name('exams.store');

        Route::get('/exams/{id}', [ExamController::class, 'show'])
            ->name('exams.show');

        Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])
            ->name('exams.edit');

        Route::put('/exams/{id}', [ExamController::class, 'update'])
            ->name('exams.update');
    });

    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])
        ->middleware(['role:manager'])
        ->name('exams.destroy');
*/

// الوظيفة الثانية (Assignment 2) - مسارات الامتحانات والدرجات مع قاعدة البيانات
Route::middleware([
    'role:system_admin,department_manager,department_employee',
    'department:قسم الامتحانات',
])->group(function () {

    // الصفحة الرئيسية للكتلة
    Route::get('/exams/home', [ExamController::class, 'home'])
        ->name('exams.home');

    // عرض جميع الامتحانات
    Route::get('/exams', [ExamController::class, 'index'])
        ->name('exams.index');

    // إضافة امتحان
    Route::get('/exams/create', [ExamController::class, 'create'])
        ->name('exams.create');

    Route::post('/exams', [ExamController::class, 'store'])
        ->name('exams.store');

    // عرض تفاصيل الامتحان مع الدرجات
    Route::get('/exams/{id}', [ExamController::class, 'show'])
        ->whereNumber('id')
        ->name('exams.show');

    // تعديل الامتحان
    Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])
        ->whereNumber('id')
        ->name('exams.edit');

    Route::put('/exams/{id}', [ExamController::class, 'update'])
        ->whereNumber('id')
        ->name('exams.update');

    // رصد الدرجات وتعديلها
    Route::get('/exams/{id}/grades', [GradeController::class, 'edit'])
        ->whereNumber('id')
        ->name('grades.edit');

    Route::put('/exams/{id}/grades', [GradeController::class, 'update'])
        ->whereNumber('id')
        ->name('grades.update');

    // الحذف: مدير القسم ومدير النظام فقط
    Route::middleware(['role:system_admin,department_manager'])->group(function () {

        Route::delete('/exams/{id}', [ExamController::class, 'destroy'])
            ->whereNumber('id')
            ->name('exams.destroy');
    });
});