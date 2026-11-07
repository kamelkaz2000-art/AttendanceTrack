<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // عرض جميع المستخدمين من جميع الأقسام
    public function index()
    {
        $users = User::with(['role', 'department'])->get();

        return view('users.index', compact('users'));
    }

    // صفحة إضافة مستخدم
    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();

        return view('users.create', compact('roles', 'departments'));
    }

    // حفظ مستخدم جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|max:255',
            'phone' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|max:2048',
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $data['status'] = 'active';

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'تمت إضافة المستخدم بنجاح');
    }

    // عرض تفاصيل مستخدم
    public function show($id)
    {
        $user = User::with(['role', 'department'])->findOrFail($id);

        return view('users.show', compact('user'));
    }

    // صفحة تعديل مستخدم
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();

        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    // حفظ التعديلات
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $currentRole = auth()->user()->role?->role_name;

        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|max:255',
            'phone' => 'required|string|max:15',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|max:2048',
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],
        ]);

        // المدير فقط ومدير النظام يستطيعان تغيير حالة الحساب
        if (in_array($currentRole, ['system_admin', 'department_manager'], true)) {
            $statusData = $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $data['status'] = $statusData['status'];
        }

        // الموظف لا يستطيع تغيير حالة الحساب
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'تم تعديل المستخدم بنجاح');
    }

    // حذف مستخدم: مدير قسم المستخدمين ومدير النظام فقط
    public function destroy($id)
    {
        $currentRole = auth()->user()->role?->role_name;

        if (!in_array($currentRole, ['system_admin', 'department_manager'], true)) {
            abort(403, 'ليس لديك صلاحية حذف المستخدمين');
        }

        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    // تغيير حالة المستخدم: مدير قسم المستخدمين ومدير النظام فقط
    public function toggleStatus($id)
    {
        $currentRole = auth()->user()->role?->role_name;

        if (!in_array($currentRole, ['system_admin', 'department_manager'], true)) {
            abort(403, 'ليس لديك صلاحية تغيير حالة المستخدم');
        }

        $user = User::findOrFail($id);

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'تم تغيير حالة المستخدم');
    }
}