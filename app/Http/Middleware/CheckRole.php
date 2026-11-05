<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /*
    // الكود القديم المعتمد على Session

    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!session()->has('username')) {
            return redirect('/login');
        }

        if (session('role') != $role) {
            abort(403, 'ليس لديك صلاحية الدخول');
        }

        return $next($request);
    }
    */

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // التحقق من تسجيل الدخول باستخدام النظام الجديد
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // جلب دور المستخدم من قاعدة البيانات
        $userRole = auth()->user()->role->role_name;

        // التحقق من أن الدور مسموح له بالدخول
        if (!in_array($userRole, $roles)) {
            abort(403, 'ليس لديك صلاحية الدخول');
        }

        return $next($request);
    }
}