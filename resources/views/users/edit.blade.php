
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تعديل المستخدم</title>
</head>

<body>

    <h2>تعديل بيانات المستخدم</h2>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div>
            <label>الاسم الكامل:</label>
            <input type="text"
                   name="full_name"
                   value="{{ old('full_name', $user->full_name) }}"
                   required>
        </div>

        <br>

        <div>
            <label>اسم المستخدم:</label>
            <input type="text"
                   name="username"
                   value="{{ old('username', $user->username) }}"
                   required>
        </div>

        <br>

        <div>
            <label>البريد الإلكتروني:</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>
        </div>

        <br>

        <div>
            <label>كلمة المرور الجديدة (اختياري):</label>
            <input type="password" name="password">
        </div>

        <br>

        <div>
            <label>رقم الهاتف:</label>
            <input type="text"
                   name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   required>
        </div>

        <br>

        <div>
            <label>الدور:</label>
            <select name="role_id" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->description }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>القسم:</label>
            <select name="department_id" required>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label>تاريخ الميلاد:</label>
            <input type="date"
                   name="birth_date"
                   value="{{ old('birth_date', $user->birth_date->format('Y-m-d')) }}"
                   required>
        </div>

        <br>

        <div>
            <label>الصورة الشخصية:</label>
            <input type="file" name="photo" accept="image/*">
        </div>

        <br>

        <div>
            <label>الحالة:</label>
            <select name="status" required>
                <option value="active"
                    {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                    نشط
                </option>

                <option value="inactive"
                    {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                    غير نشط
                </option>
            </select>
        </div>

        <br>

        <button type="submit">حفظ التعديلات</button>

    </form>

    <br>

    <a href="{{ route('users.index') }}">العودة إلى قائمة المستخدمين</a>

</body>
</html>
