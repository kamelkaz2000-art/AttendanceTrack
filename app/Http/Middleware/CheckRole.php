<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /*
    public function handle(Request $request, Closure $next, $role)
    {
        if (!session()->has('username')) {
            return redirect('/login');
        }

        if (session('role') != $role) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
    */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // جلب دور المستخدم
        $userRole = auth()->user()->role?->role_name;

        // التحقق من صلاحية الدور
        if (!$userRole || !in_array($userRole, $roles, true)) {
            abort(403, 'ليس لديك صلاحية الدخول');
        }

        return $next($request);
    }
}
