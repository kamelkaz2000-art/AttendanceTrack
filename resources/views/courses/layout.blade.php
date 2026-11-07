{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| القالب الرئيسي (Layout) لكل واجهات الكتلة الثانية
| يحتوي على: شريط التنقل، رسائل النجاح والخطأ، مكان المحتوى @yield('content')، والتذييل
| يستخدم Bootstrap 5 (نسخة RTL للعربي) وأيقونات Bootstrap Icons
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'إدارة المواد') - AttendanceTrack</title>

    {{-- Bootstrap بنسخة RTL لدعم اتجاه الكتابة العربية --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    {{-- أيقونات Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-weight: bold;
        }

        /* بطاقات الإحصائيات في لوحة التحكم */
        .stat-card .stat-icon {
            font-size: 2.2rem;
            opacity: 0.85;
        }

        footer {
            margin-top: 40px;
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }

        /* تصغير الخط والأزرار على الشاشات الصغيرة (الجوال) */
        @media (max-width: 576px) {
            h1, .h2 {
                font-size: 1.4rem;
            }

            .table {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

    {{-- المتغيرات المشتركة: المستخدم الحالي ودوره --}}
    @php
        $currentUser = auth()->user();
        $currentRole = $currentUser->role?->role_name;
    @endphp

    {{-- شريط التنقل --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('courses.home') }}">
                <i class="bi bi-journal-bookmark-fill"></i> إدارة المواد
            </a>

            {{-- زر القائمة على الشاشات الصغيرة --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.home') ? 'active' : '' }}" href="{{ route('courses.home') }}">
                            <i class="bi bi-speedometer2"></i> الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="bi bi-list-ul"></i> المواد
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.create') ? 'active' : '' }}" href="{{ route('courses.create') }}">
                            <i class="bi bi-plus-circle"></i> إضافة مادة
                        </a>
                    </li>

                    {{-- مدير النظام فقط: رابط العودة للوحة التحكم العامة --}}
                    @if ($currentRole === 'system_admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid"></i> لوحة التحكم العامة
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- اسم المستخدم ودوره وزر تسجيل الخروج --}}
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="navbar-text text-light small">
                        <i class="bi bi-person-circle"></i>
                        {{ $currentUser->full_name }}
                        ({{ $currentUser->role?->description }})
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- رسائل التنبيه (نجاح / خطأ) القادمة من الـ Controller عبر with() --}}
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- المحتوى الرئيسي لكل صفحة --}}
    <main>
        @yield('content')
    </main>

    {{-- التذييل --}}
    <footer>
        <div class="container text-center">
            <p class="mb-0">{{ date('Y') }} - AttendanceTrack - الكتلة الثانية: إدارة المواد والمقررات</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- مكان لإضافة كود JavaScript خاص بكل صفحة (يُضاف من الصفحات بـ @push('scripts')) --}}
    @stack('scripts')
</body>
</html>
