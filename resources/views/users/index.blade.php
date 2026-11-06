
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
</head>

<body>

    <h2>إدارة المستخدمين</h2>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <br>

    <a href="{{ route('users.create') }}">إضافة مستخدم جديد</a>

    <br><br>

    @php
        $roleName = auth()->user()->role->role_name;
        $canDeleteAndChangeStatus = in_array($roleName, [
            'system_admin',
            'department_manager'
        ]);
    @endphp

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>الرقم</th>
                <th>الاسم الكامل</th>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>الدور</th>
                <th>القسم</th>
                <th>الحالة</th>
                <th>التفاصيل</th>
                <th>تعديل</th>

                @if ($canDeleteAndChangeStatus)
                    <th>حذف</th>
                    <th>تغيير الحالة</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->description }}</td>
                    <td>{{ $user->department->department_name }}</td>

                    <td>
                        {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                    </td>

                    <td>
                        <a href="{{ route('users.show', $user->id) }}">
                            عرض التفاصيل
                        </a>
                    </td>

                    <td>
                        <a href="{{ route('users.edit', $user->id) }}">
                            تعديل
                        </a>
                    </td>

                    @if ($canDeleteAndChangeStatus)
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                    حذف
                                </button>
                            </form>
                        </td>

                        <td>
                            <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit">
                                    {{ $user->status == 'active' ? 'تعطيل' : 'تفعيل' }}
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $canDeleteAndChangeStatus ? 11 : 9 }}">
                        لا يوجد مستخدمون حالياً
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">تسجيل الخروج</button>
    </form>

</body>
</html>
