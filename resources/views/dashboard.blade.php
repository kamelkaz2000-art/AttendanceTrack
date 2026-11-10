
@extends('layouts.app')

@section('title', 'لوحة التحكم - AttendanceTrack')

@section('content')

    <!-- عنوان الصفحة -->
    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">
            لوحة الإدارة الرئيسية
        </p>

        <h2 class="text-3xl font-extrabold text-slate-800 mb-3">
            مرحباً، {{ Auth::user()->full_name }}
        </h2>

        <p class="text-slate-500">
            من هنا يمكنك الوصول إلى أقسام النظام وإدارة المستخدمين.
        </p>
    </div>

    <!-- بطاقات لوحة التحكم -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        <!-- إدارة المستخدمين -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">

            <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center text-3xl mb-5">
                👥
            </div>

            <h3 class="text-xl font-extrabold text-slate-800 mb-3">
                إدارة المستخدمين
            </h3>

            <p class="text-slate-500 text-sm leading-7 mb-6">
                عرض المستخدمين وإضافة حسابات جديدة وتعديل بياناتهم وإدارة حالات الحسابات.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('users.index') }}"
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-3 rounded-xl text-sm font-bold transition">
                    عرض المستخدمين
                </a>

                <a href="{{ route('users.create') }}"
                   class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-xl text-sm font-bold transition">
                    إضافة مستخدم
                </a>
            </div>
        </div>

        <!-- الكتلة الخامسة: الامتحانات والدرجات -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">

            <div class="w-14 h-14 bg-amber-100 text-amber-700 rounded-2xl flex items-center justify-center text-3xl mb-5">
                📝
            </div>

            <h3 class="text-xl font-extrabold text-slate-800 mb-3">
                الامتحانات والدرجات
            </h3>

            <p class="text-slate-500 text-sm leading-7 mb-6">
                إدارة الامتحانات ورصد درجات الطلاب.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('exams.home') }}"
                   class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-3 rounded-xl text-sm font-bold transition">
                    قسم الامتحانات
                </a>

                <a href="{{ route('exams.create') }}"
                   class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-4 py-3 rounded-xl text-sm font-bold transition">
                    إضافة امتحان
                </a>
            </div>
        </div>

        <!-- إدارة النظام -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">

            <div class="w-14 h-14 bg-indigo-100 text-indigo-700 rounded-2xl flex items-center justify-center text-3xl mb-5">
                ⚙️
            </div>

            <h3 class="text-xl font-extrabold text-slate-800 mb-3">
                إدارة النظام
            </h3>

            <p class="text-slate-500 text-sm leading-7 mb-6">
                مساحة مخصصة لمدير النظام لمتابعة الأقسام وإدارة صلاحيات المستخدمين.
            </p>

            <span class="inline-block bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-bold">
                صلاحيات مدير النظام
            </span>
        </div>

        <!-- معلومات الحساب -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">

            <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-3xl mb-5">
                🛡️
            </div>

            <h3 class="text-xl font-extrabold text-slate-800 mb-3">
                الحساب والصلاحيات
            </h3>

            <p class="text-slate-500 text-sm leading-7 mb-6">
                أنت مسجل الدخول بصفتك مدير النظام وتملك صلاحية الوصول إلى لوحة الإدارة.
            </p>

            <span class="inline-block bg-emerald-50 text-emerald-700 px-4 py-2 rounded-lg text-sm font-bold">
                الحساب نشط
            </span>
        </div>

    </div>

@endsection
