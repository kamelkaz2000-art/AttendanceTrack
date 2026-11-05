
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
</head>

<body>

    <h2>إضافة مستخدم جديد</h2>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div>
            <label>الاسم الكامل:</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required>
        </div>

        <br>

        <div>
            <label>اسم المستخدم:</label>
            <input type="text" name="username" value="{{ old('username') }}" required>
        </div>

        <br>

        <div>
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <br>

        <div>
            <label>كلمة المرور:</label>
            <input type="password" name="password" required>
        </div>

        <br>

        <div>
            <label>رقم الهاتف:</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required>
        </div>

        <br>

        <div>
            <label>الدور:</label>
            <select name="role_id" required>
                <option value="">اختر الدور</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->description }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>القسم:</label>
            <select name="department_id" required>
                <option value="">اختر القسم</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>تاريخ الميلاد:</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required>
        </div>

        <br>

        <div>
            <label>الصورة الشخصية:</label>
            <input type="file" name="photo" accept="image/*">
        </div>

        <br>

        <button type="submit">حفظ المستخدم</button>

    </form>

    <br>

    <a href="{{ route('users.index') }}">العودة إلى المستخدمين</a>

</body>

</html>
