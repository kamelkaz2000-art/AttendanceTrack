
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تفاصيل المستخدم</title>
</head>

<body>

    <h2>تفاصيل المستخدم</h2>

    <p>رقم المستخدم: {{ $user->id }}</p>

    <p>الاسم الكامل: {{ $user->full_name }}</p>

    <p>اسم المستخدم: {{ $user->username }}</p>

    <p>البريد الإلكتروني: {{ $user->email }}</p>

    <p>رقم الهاتف: {{ $user->phone }}</p>

    <p>الدور: {{ $user->role->description }}</p>

    <p>القسم: {{ $user->department->department_name }}</p>

    <p>تاريخ الميلاد: {{ $user->birth_date->format('Y-m-d') }}</p>

    <p>
        الحالة:
        {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
    </p>

    @if ($user->photo)
        <div>
            <p>الصورة الشخصية:</p>
            <img src="{{ asset('storage/' . $user->photo) }}"
                 alt="الصورة الشخصية"
                 width="150">
        </div>
    @endif

    <br>

    <a href="{{ route('users.index') }}">العودة إلى قائمة المستخدمين</a>

</body>
</html>
