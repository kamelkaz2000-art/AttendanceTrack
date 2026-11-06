<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AttendanceTrack')</title>
    <!-- Bootstrap RTL للتصميم المتجاوب -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">AttendanceTrack</a>
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">المستخدمون</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('courses.index') }}">المقررات</a></li>
        </ul>
        @auth
            <form action="{{ route('logout') }}" method="POST" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">تسجيل الخروج</button>
            </form>
        @endauth
    </div>
</nav>

<main class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

</body>
</html>
