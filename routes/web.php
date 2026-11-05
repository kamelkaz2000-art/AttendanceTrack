<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// عرض صفحة تسجيل الدخول
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

// معالجة بيانات تسجيل الدخول
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

// تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    // لوحة التحكم
Route::get('/dashboard', function () {
    return view('dashboard');})
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('dashboard');

// عرض قائمة المستخدمين
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.index');

// عرض صفحة إضافة مستخدم
Route::get('/users/create', [UserController::class, 'create'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.create');

// حفظ مستخدم جديد
Route::post('/users', [UserController::class, 'store'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.store');

// عرض تفاصيل مستخدم
Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.show');

// عرض صفحة تعديل المستخدم
Route::get('/users/{id}/edit', [UserController::class, 'edit'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.edit');

// حفظ التعديلات على المستخدم
Route::put('/users/{id}', [UserController::class, 'update'])
    ->middleware(['role:system_admin,department_manager,department_employee'])
    ->name('users.update');

// حذف مستخدم
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->middleware(['role:system_admin,department_manager'])
    ->name('users.destroy');

// تغيير حالة المستخدم
Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])
    ->middleware(['role:system_admin,department_manager'])
    ->name('users.toggleStatus');