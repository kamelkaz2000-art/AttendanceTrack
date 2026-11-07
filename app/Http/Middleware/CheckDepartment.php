<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepartment
{
    public function handle(Request $request, Closure $next, ...$departments): Response
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // مدير النظام لديه صلاحية الوصول إلى جميع الأقسام
        if ($user->role?->role_name === 'system_admin') {
            return $next($request);
        }

        // جلب اسم قسم المستخدم
        $departmentName = $user->department?->department_name;

        // التحقق من أن المستخدم ينتمي إلى القسم المطلوب
        if (!$departmentName || !in_array($departmentName, $departments, true)) {
            abort(403, 'ليس لديك صلاحية الدخول إلى هذا القسم');
        }

        return $next($request);
    }
}