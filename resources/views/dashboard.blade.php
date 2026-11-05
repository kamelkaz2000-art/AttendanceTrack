<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - AttendanceTrack</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 25px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px #ddd;
        }

        a, button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>لوحة التحكم</h1>
    <p>مرحباً بك في نظام AttendanceTrack</p>

    <div class="card">
        <h2>إدارة المستخدمين</h2>
        <p>إضافة وعرض وتعديل بيانات المستخدمين.</p>
        <a href="{{ route('users.index') }}">عرض المستخدمين</a>
        <a href="{{ route('users.create') }}">إضافة مستخدم</a>
    </div>

    <div class="card">
        <h2>تسجيل الخروج</h2>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">تسجيل الخروج</button>
        </form>
    </div>
</div>

</body>
</html>
