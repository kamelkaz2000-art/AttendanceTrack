
@extends('layouts.app')

@section('title', 'تفاصيل المستخدم - AttendanceTrack')

@section('content')

    <!-- عنوان الصفحة -->
    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">إدارة المستخدمين</p>

        <h2 class="text-3xl font-extrabold text-slate-800">
            تفاصيل المستخدم
        </h2>

        <p class="text-slate-500 mt-2">
            عرض المعلومات الشخصية ومعلومات الحساب للمستخدم.
        </p>
    </div>

    <!-- بطاقة التفاصيل -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <!-- رأس البطاقة -->
        <div class="p-6 md:p-8 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row items-center gap-5">

            @if ($user->photo)
                <img
                    src="{{ asset('storage/' . $user->photo) }}"
                    alt="الصورة الشخصية"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow"
                >
            @else
                <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-3xl font-extrabold border-4 border-white shadow">
                    {{ mb_substr($user->full_name, 0, 1) }}
                </div>
            @endif

            <div class="text-center sm:text-right">
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ $user->full_name }}
                </h3>

                <p class="text-slate-500 mt-1">
                    {{ '@' . $user->username }}
                </p>

                <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-bold
                    {{ $user->status == 'active'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ $user->status == 'active' ? 'نشط' : 'غير نشط' }}
                </span>
            </div>

        </div>

        <!-- معلومات المستخدم -->
        <div class="p-6 md:p-8">

            <h4 class="text-lg font-extrabold text-slate-800 mb-6">
                المعلومات الشخصية والحساب
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- رقم المستخدم -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">رقم المستخدم</p>
                    <p class="font-bold text-slate-800">{{ $user->id }}</p>
                </div>

                <!-- الاسم الكامل -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">الاسم الكامل</p>
                    <p class="font-bold text-slate-800">{{ $user->full_name }}</p>
                </div>

                <!-- اسم المستخدم -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">اسم المستخدم</p>
                    <p class="font-bold text-slate-800">{{ $user->username }}</p>
                </div>

                <!-- البريد الإلكتروني -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">البريد الإلكتروني</p>
                    <p class="font-bold text-slate-800 break-all">{{ $user->email }}</p>
                </div>

                <!-- رقم الهاتف -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">رقم الهاتف</p>
                    <p class="font-bold text-slate-800">{{ $user->phone }}</p>
                </div>

                <!-- الدور -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">الدور</p>
                    <p class="font-bold text-slate-800">
                        {{ $user->role?->description ?? 'غير محدد' }}
                    </p>
                </div>

                <!-- القسم -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">القسم</p>
                    <p class="font-bold text-slate-800">
                        {{ $user->department?->department_name ?? 'غير محدد' }}
                    </p>
                </div>

                <!-- تاريخ الميلاد -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-sm text-slate-500 mb-2">تاريخ الميلاد</p>
                    <p class="font-bold text-slate-800">
                        {{ $user->birth_date
                            ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d')
                            : 'غير محدد' }}
                    </p>
                </div>

            </div>

        </div>

        <!-- أزرار أسفل البطاقة -->
        <div class="flex flex-col sm:flex-row justify-between gap-3 bg-slate-50 border-t border-slate-100 p-6">

            <a href="{{ route('users.index') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                العودة إلى المستخدمين
            </a>

            <a href="{{ route('users.edit', $user->id) }}"
               class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm shadow-lg shadow-blue-100 transition">
                تعديل بيانات المستخدم
            </a>

        </div>

    </div>

@endsection
