
<header class="bg-gradient-to-l from-blue-950 to-blue-800 text-white shadow-lg">
    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">

        <!-- شعار النظام -->
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 text-2xl">
                📚
            </div>

            <div>
                <h1 class="text-xl font-extrabold">AttendanceTrack</h1>
                <p class="text-xs text-blue-200">
                    نظام إدارة الحضور الأكاديمي
                </p>
            </div>
        </div>

        <!-- معلومات المستخدم -->
        <div class="flex items-center gap-4">

            <div class="hidden text-left sm:block">
                <p class="font-bold">
                    {{ Auth::user()->full_name }}
                </p>

                <p class="text-xs text-blue-200">
                    {{ Auth::user()->role?->role_name }}
                </p>
            </div>

            <!-- تسجيل الخروج -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit"
                    class="rounded-xl bg-white/10 px-4 py-2 text-sm font-bold transition hover:bg-red-500">
                    تسجيل الخروج
                </button>
            </form>

        </div>

    </div>
</header>
