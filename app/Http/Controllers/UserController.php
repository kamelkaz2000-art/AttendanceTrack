<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;


class UserController extends Controller
{
/*
    // مصفوفة المستخدمين الوهمية
    private $users = [
        [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'role' => 'manager',
            'status' => 'active'
        ],
        [
            'id' => 2,
            'name' => 'Ahmad',
            'email' => 'ahmad@test.com',
            'role' => 'employee',
            'status' => 'active'
        ],
        [
            'id' => 3,
            'name' => 'Sara',
            'email' => 'sara@test.com',
            'role' => 'employee',
            'status' => 'active'
        ],
        [
            'id' => 4,
            'name' => 'Mohammad',
            'email' => 'mohammad@test.com',
            'role' => 'employee',
            'status' => 'active'
        ],
        [
            'id' => 5,
            'name' => 'Lina',
            'email' => 'lina@test.com',
            'role' => 'employee',
            'status' => 'active'
        ]
    ];

// عرض جميع المستخدمين
public function index()
{
    $users = session('users', $this->users);

    foreach ($users as $key => $user) {
        if (!isset($users[$key]['status'])) {
            $users[$key]['status'] = 'active';
        }
    }

    session(['users' => $users]);

    return view('users.index', [
        'users' => $users
    ]);
}

// عرض صفحة إضافة مستخدم
public function create()
{
    return view('users.create');
}

// حفظ المستخدم الجديد
public function store(Request $request)
{
    $users = session('users', $this->users);

    $user = [
        'id' => count($users) + 1,
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'status' => 'active'
    ];

    $users[] = $user;

    session(['users' => $users]);

    return redirect()->route('users.index');
}

// عرض تفاصيل مستخدم واحد
public function show($id)
{
    $users = session('users', $this->users);

    foreach ($users as $user) {
        if ($user['id'] == $id) {
            return view('users.show', [
                'user' => $user
            ]);
        }
    }

    abort(404);
}

// عرض صفحة تعديل المستخدم
public function edit($id)
{
    $users = session('users', $this->users);

    foreach ($users as $user) {
        if ($user['id'] == $id) {
            return view('users.edit', [
                'user' => $user
            ]);
        }
    }

    abort(404);
}
// حفظ التعديلات على المستخدم
public function update(Request $request, $id)
{
    $users = session('users', $this->users);

    foreach ($users as $key => $user) {
        if ($user['id'] == $id) {

            $users[$key]['name'] = $request->name;
            $users[$key]['email'] = $request->email;
            $users[$key]['role'] = $request->role;

            session(['users' => $users]);

            return redirect()->route('users.index');
        }
    }

    abort(404);
}

// حذف مستخدم
public function destroy($id)
{
    $users = session('users', $this->users);

    foreach ($users as $key => $user) {
        if ($user['id'] == $id) {

            unset($users[$key]);

            session(['users' => array_values($users)]);

            return redirect()->route('users.index');
        }
    }

    abort(404);
}

// تغيير حالة المستخدم (نشط / غير نشط)
public function toggleStatus($id)
{
    $users = session('users', $this->users);

    foreach ($users as $key => $user) {
        if ($user['id'] == $id) {

            if (!isset($users[$key]['status'])) {
                $users[$key]['status'] = 'active';
            }

            if ($users[$key]['status'] == 'active') {
                $users[$key]['status'] = 'inactive';
            } else {
                $users[$key]['status'] = 'active';
            }

            session(['users' => $users]);

            return redirect()->route('users.index');
        }
    }

    abort(404);
}
*/


    // عرض جميع المستخدمين
    public function index()
    {
        $query = User::with(['role', 'department']);

        // مدير النظام يستطيع مشاهدة جميع المستخدمين
        if (auth()->user()->role->role_name !== 'system_admin') {
            // مدير وموظف القسم يشاهدون مستخدمي قسمهم فقط
            $query->where(
                'department_id',
                auth()->user()->department_id
            );
        }

        $users = $query->get();

        return view('users.index', compact('users'));
    }


    // عرض صفحة إضافة مستخدم
    public function create()
    {
        $roles = Role::all();

        // مدير النظام يستطيع اختيار أي قسم
        if (auth()->user()->role->role_name === 'system_admin') {
            $departments = Department::all();
        } else {
            // مدير وموظف القسم يستطيعان اختيار قسمهما فقط
            $departments = Department::where(
                'id',
                auth()->user()->department_id
            )->get();
        }

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

        // غير مدير النظام يضيف المستخدمين ضمن قسمه فقط
        if (auth()->user()->role->role_name !== 'system_admin') {
            $data['department_id'] = auth()->user()->department_id;
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        $data['status'] = 'active';

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'تمت إضافة المستخدم بنجاح');
    }


    // عرض تفاصيل مستخدم
    public function show($id)
    {
        $user = $this->findUserForCurrentDepartment($id);

        $user->load(['role', 'department']);

        return view('users.show', compact('user'));
    }


    // عرض صفحة تعديل المستخدم
    public function edit($id)
    {
        $user = $this->findUserForCurrentDepartment($id);

        $roles = Role::all();

        // مدير النظام يستطيع اختيار أي قسم
        if (auth()->user()->role->role_name === 'system_admin') {
            $departments = Department::all();
        } else {
            // باقي الأدوار يظهر لهم قسمهم فقط
            $departments = Department::where(
                'id',
                auth()->user()->department_id
            )->get();
        }

        return view('users.edit', compact(
            'user',
            'roles',
            'departments'
        ));
    }


    // حفظ التعديلات
    public function update(Request $request, $id)
    {
        $user = $this->findUserForCurrentDepartment($id);

        $roleName = auth()->user()->role->role_name;

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

        // مدير النظام ومدير القسم يستطيعان تغيير الحالة
        if (
            $roleName === 'system_admin' ||
            $roleName === 'department_manager'
        ) {
            $statusData = $request->validate([
                'status' => 'required|in:active,inactive',
            ]);

            $data['status'] = $statusData['status'];
        }

        // الموظف لا يستطيع تغيير حالة المستخدم
        // لذلك لا نضيف status إلى بيانات التحديث الخاصة به

        // غير مدير النظام لا يستطيع نقل المستخدم لقسم آخر
        if ($roleName !== 'system_admin') {
            $data['department_id'] = auth()->user()->department_id;
        }

        // إذا لم يدخل المستخدم كلمة مرور جديدة نبقي القديمة
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                ->store('users', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'تم تعديل المستخدم بنجاح');
    }


    // حذف مستخدم
    public function destroy($id)
    {
        $user = $this->findUserForCurrentDepartment($id);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }


    // تغيير حالة المستخدم
    public function toggleStatus($id)
    {
        $user = $this->findUserForCurrentDepartment($id);

        $user->status = $user->status === 'active'
            ? 'inactive'
            : 'active';

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'تم تغيير حالة المستخدم');
    }


    // جلب المستخدم حسب قسم المستخدم الحالي
    private function findUserForCurrentDepartment($id)
    {
        $query = User::query();

        // مدير النظام يستطيع الوصول لجميع المستخدمين
        if (auth()->user()->role->role_name !== 'system_admin') {
            // باقي الأدوار يصلون لمستخدمي قسمهم فقط
            $query->where(
                'department_id',
                auth()->user()->department_id
            );
        }

        return $query->findOrFail($id);
    }

}