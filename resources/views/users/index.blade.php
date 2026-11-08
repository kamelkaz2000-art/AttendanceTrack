
@extends('layouts.app')

@section('title', 'إدارة المستخدمين - AttendanceTrack')

@section('content')

    <!-- عنوان الصفحة -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

        <div>
            <p class="text-blue-600 font-bold text-sm mb-2">
                إدارة النظام
            </p>

            <h2 class="text-3xl font-extrabold text-slate-800">
                إدارة المستخدمين
            </h2>

            <p class="text-slate-500 mt-2">
                عرض وإدارة حسابات المستخدمين وبياناتهم وصلاحياتهم.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-5 py-3 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-100">
            <span>＋</span>
            إضافة مستخدم جديد
        </a>

    </div>

    <!-- رسائل النجاح -->
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4">
            <span class="text-xl">✓</span>
            <p class="font-semibold text-sm">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- رسائل الأخطاء -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
            <p class="font-bold mb-2">يرجى تصحيح الأخطاء التالية:</p>

            @foreach ($errors->all() as $error)
                <p class="text-sm mb-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @php
        $roleName = auth()->user()->role?->role_name;

        $canDeleteAndChangeStatus = in_array($roleName, [
            'system_admin',
            'department_manager'
        ], true);
    @endphp

    <!-- جدول المستخدمين -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <!-- رأس الجدول -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-6 border-b border-slate-100">

            <div>
                <h3 class="text-lg font-extrabold text-slate-800">
                    قائمة المستخدمين
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    جميع المستخدمين المسجلين في القسم.
                </p>
            </div>

            <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold">
                عدد السجلات: {{ $users->count() }}
            </span>

        </div>

        <!-- الجدول -->
        <div class="overflow-x-auto">

            <table class="w-full text-right whitespace-nowrap">

                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="px-5 py-4 font-bold">الرقم</th>
                        <th class="px-5 py-4 font-bold">الاسم الكامل</th>
                        <th class="px-5 py-4 font-bold">اسم المستخدم</th>
                        <th class="px-5 py-4 font-bold">البريد الإلكتروني</th>
                        <th class="px-5 py-4 font-bold">الدور</th>
                        <th class="px-5 py-4 font-bold">القسم</th>
                        <th class="px-5 py-4 font-bold">الحالة</th>
                        <th class="px-5 py-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">

                    @forelse ($users as $user)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-4 text-slate-500 font-semibold">
                                {{ $user->id }}
                            </td>

                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $user->full_name }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $user->username }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $user->role?->description ?? 'غير محدد' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $user->department?->department_name ?? 'غير محدد' }}
                            </td>

                            <!-- حالة الحساب -->
                            <td class="px-5 py-4">
                                @if ($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold">
                                        <span>●</span>
                                        نشط
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold">
                                        <span>●</span>
                                        غير نشط
                                    </span>
                                @endif
                            </td>

                            <!-- الإجراءات -->
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    <!-- التفاصيل -->
                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                        التفاصيل
                                    </a>

                                    <!-- تعديل -->
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                        تعديل
                                    </a>

                                    @if ($canDeleteAndChangeStatus)

                                        <!-- حذف -->
                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                                حذف
                                            </button>

                                        </form>

                                        <!-- تغيير الحالة -->
                                        <form action="{{ route('users.toggleStatus', $user->id) }}"
                                              method="POST">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="{{ $user->status === 'active' ? 'bg-orange-50 hover:bg-orange-100 text-orange-700' : 'bg-green-50 hover:bg-green-100 text-green-700' }} px-3 py-2 rounded-lg text-xs font-bold transition">

                                                {{ $user->status === 'active' ? 'تعطيل' : 'تفعيل' }}

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">

                                <div class="text-4xl mb-3">👥</div>

                                <h3 class="text-lg font-bold text-slate-700 mb-2">
                                    لا يوجد مستخدمون حالياً
                                </h3>

                                <p class="text-sm text-slate-400">
                                    يمكنك إضافة مستخدم جديد من خلال الزر الموجود أعلى الصفحة.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- أسفل الجدول -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                يتم عرض بيانات المستخدمين حسب صلاحيات القسم الحالي.
            </p>
        </div>

    </div>

@endsection
