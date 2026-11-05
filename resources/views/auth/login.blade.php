<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - AttendanceTrack</title>
</head>

<body>

    <h2>تسجيل الدخول</h2>

    @if (session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
    @endif
    
    <form action="{{ route('login') }}" method="POST">

        @csrf

        <div>
            <label>اسم المستخدم:</label>
            <input type="text" name="username" required>
        </div>

        <br>

        <div>
            <label>كلمة المرور:</label>
            <input type="password" name="password" required>
        </div>

        <br>

        <button type="submit">دخول</button>

    </form>

</body>

</html>
