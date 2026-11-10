<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamController;      // الكتلة الخامسة: الامتحانات
use App\Http\Controllers\GradeController;     // الكتلة الخامسة: الدرجات والنتائج
use App\Http\Controllers\ExamTypeController;  // الكتلة الخامسة: أنواع الامتحانات

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
Route::middleware([
    'role:system_admin,department_manager,department_employee',
    'department:قسم الامتحانات',
])->group(function () {

    // الصفحة الرئيسية للكتلة
    Route::get('/exams/home', [ExamController::class, 'home'])
        ->name('exams.home');

    // عرض الامتحانات والبحث المتقدم (Ajax)
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

    // مدير القسم ومدير النظام فقط: الحذف، إصدار النتائج، إدارة أنواع الامتحانات
    Route::middleware(['role:system_admin,department_manager'])->group(function () {

        Route::delete('/exams/{id}', [ExamController::class, 'destroy'])
            ->whereNumber('id')
            ->name('exams.destroy');

        // إصدار النتائج: كشف حسب الشعبة وكشف لكل طالب
        Route::get('/exams/results', [GradeController::class, 'results'])
            ->name('grades.results');

        Route::get('/exams/results/students/{studentId}', [GradeController::class, 'studentReport'])
            ->whereNumber('studentId')
            ->name('grades.studentReport');

        // إدارة أنواع الامتحانات
        Route::get('/exams/types', [ExamTypeController::class, 'index'])
            ->name('exam-types.index');

        Route::post('/exams/types', [ExamTypeController::class, 'store'])
            ->name('exam-types.store');

        Route::put('/exams/types/{id}', [ExamTypeController::class, 'update'])
            ->whereNumber('id')
            ->name('exam-types.update');

        Route::delete('/exams/types/{id}', [ExamTypeController::class, 'destroy'])
            ->whereNumber('id')
            ->name('exam-types.destroy');
    });
});