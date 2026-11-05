<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    /*
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // بيانات المستخدمين الثابتة
        $users = [
            [
                'username' => 'manager',
                'password' => '123456',
                'role' => 'manager'
            ],
            [
                'username' => 'employee',
                'password' => '123456',
                'role' => 'employee'
            ]
        ];

        // التحقق من بيانات المستخدم
        foreach ($users as $user) {

            if (
                $request->username == $user['username'] &&
                $request->password == $user['password']
            ) {

                // حفظ بيانات المستخدم في Session
                session([
                    'username' => $user['username'],
                    'role' => $user['role']
                ]);

                if ($user['role'] == 'manager') {
                    return redirect('/users');
                } else {
                    return redirect('/login');
                }
            }
        }

        // في حال كانت البيانات خاطئة
        return back()->with('error', 'اسم المستخدم أو كلمة المرور غير صحيحة');
    }

    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }
        */
    public function showLogin()
    {
        return view('auth.login');
    }

    // تسجيل الدخول باستخدام قاعدة البيانات
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // التحقق من اسم المستخدم وكلمة المرور والحالة
        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status' => 'active',
        ])) {

            $request->session()->regenerate();

            $userRole = Auth::user()->role->role_name;

            // الأدوار التابعة لكتلة إدارة المستخدمين
            if (in_array($userRole, [
                'system_admin',
                'department_manager',
                'department_employee'
            ])) {
                return redirect()->route('dashboard');
            }

            // الأدوار الأخرى خارج نطاق هذه الكتلة حالياً
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'ليس لديك صلاحية الدخول إلى هذه الكتلة');
        }

        return back()
            ->with('error', 'اسم المستخدم أو كلمة المرور غير صحيحة أو الحساب غير نشط')
            ->onlyInput('username');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
