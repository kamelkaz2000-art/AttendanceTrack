
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - AttendanceTrack</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- الجانب التعريفي -->
        <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white p-12">

            <div>
                <div class="flex items-center gap-3 mb-16">
                    <div class="w-12 h-12 bg-white/15 rounded-2xl flex items-center justify-center text-2xl">
                        📚
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold">AttendanceTrack</h1>
                        <p class="text-blue-200 text-sm">نظام إدارة الحضور الأكاديمي</p>
                    </div>
                </div>

                <h2 class="text-4xl font-extrabold leading-relaxed mb-6">
                    أهلاً بك في<br>
                    نظام إدارة الجامعة
                </h2>

                <p class="text-blue-100 leading-8 text-lg">
                    منصة موحدة لإدارة المستخدمين والأقسام والعمليات الأكاديمية
                    بسهولة وتنظيم وأمان.
                </p>
            </div>

            <div class="border-t border-white/20 pt-6 text-blue-200 text-sm">
                © {{ date('Y') }} AttendanceTrack - جميع الحقوق محفوظة
            </div>
        </div>

        <!-- نموذج تسجيل الدخول -->
        <div class="p-8 sm:p-12 lg:p-14 flex flex-col justify-center">

            <div class="mb-10">
                <div class="md:hidden flex items-center gap-3 mb-10">
                    <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center text-2xl">
                        📚
                    </div>
                    <h1 class="text-xl font-extrabold text-blue-900">AttendanceTrack</h1>
                </div>

                <span class="text-blue-600 font-bold text-sm">مرحباً بعودتك</span>

                <h2 class="text-3xl font-extrabold text-slate-800 mt-3 mb-3">
                    تسجيل الدخول
                </h2>

                <p class="text-slate-500">
                    أدخل بيانات حسابك للمتابعة إلى النظام.
                </p>
            </div>

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">

                @csrf

                <div>
                    <label for="username" class="block text-sm font-bold text-slate-700 mb-2">
                        اسم المستخدم
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="أدخل اسم المستخدم"
                        required
                        autofocus
                        class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                        كلمة المرور
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="أدخل كلمة المرور"
                        required
                        class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition duration-200 hover:-translate-y-0.5"
                >
                    تسجيل الدخول
                </button>

            </form>

            <p class="text-center text-xs text-slate-400 mt-10">
                AttendanceTrack | نظام إدارة الحضور الأكاديمي
            </p>

        </div>
    </div>

</body>
</html>
