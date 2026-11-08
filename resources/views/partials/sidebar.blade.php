
<aside class="w-64 shrink-0 bg-slate-900 text-slate-200 min-h-full">

    <div class="px-6 py-6 border-b border-slate-700">
        <p class="text-xs text-slate-400 mb-2">القائمة الرئيسية</p>
        <h2 class="text-lg font-bold text-white">نظام الإدارة</h2>
    </div>

    <nav class="p-4 space-y-2">

        @php
            $user = Auth::user();
            $roleName = $user->role?->role_name;
            $departmentName = $user->department?->department_name;
        @endphp

        <!-- لوحة التحكم: مدير النظام فقط -->
        @if ($roleName === 'system_admin')
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white' : 'hover:bg-slate-800' }}">
                <span>🏠</span>
                <span>لوحة التحكم</span>
            </a>
        @endif

        <!-- قسم إدارة المستخدمين -->
        @if ($departmentName === 'قسم المستخدمين' || $roleName === 'system_admin')

            <div class="pt-5 pb-2 px-4">
                <p class="text-xs font-bold text-slate-500">إدارة المستخدمين</p>
            </div>

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.edit') ? 'bg-blue-700 text-white' : 'hover:bg-slate-800' }}">
                <span>👥</span>
                <span>عرض المستخدمين</span>
            </a>

            <a href="{{ route('users.create') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition
               {{ request()->routeIs('users.create') ? 'bg-blue-700 text-white' : 'hover:bg-slate-800' }}">
                <span>➕</span>
                <span>إضافة مستخدم</span>
            </a>

        @endif

    </nav>

    <div class="px-6 py-5 mt-6 border-t border-slate-700">
        <p class="text-xs text-slate-500">
            AttendanceTrack
        </p>
        <p class="text-xs text-slate-500 mt-1">
            نظام إدارة الحضور الأكاديمي
        </p>
    </div>

</aside>
