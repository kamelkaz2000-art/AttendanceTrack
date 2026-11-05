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
        $users = User::with(['role', 'department'])->get();

        return view('users.index', compact('users'));
    }

    // عرض صفحة إضافة مستخدم
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
        $user = User::with(['role', 'department'])->findOrFail($id);

        return view('users.show', compact('user'));
    }

    // عرض صفحة تعديل المستخدم
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = Role::all();
        $departments = Department::all();

        return view('users.edit', compact(
            'user',
            'roles',
            'departments'
        ));
    }

    // حفظ التعديلات
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

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
            'status' => 'required|in:active,inactive',
        ]);

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
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    // تغيير حالة المستخدم
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status == 'active'
            ? 'inactive'
            : 'active';

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'تم تغيير حالة المستخدم');
    }


}
