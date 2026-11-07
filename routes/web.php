<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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