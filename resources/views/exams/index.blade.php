{{-- الكتلة الخامسة: عرض جميع الامتحانات --}}

@extends('layouts.app')

@section('title', 'الامتحانات - AttendanceTrack')

@section('content')

    <!-- عنوان الصفحة -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
            <h2 class="text-3xl font-extrabold text-slate-800">إدارة الامتحانات</h2>
            <p class="text-slate-500 mt-2">عرض جميع الامتحانات ورصد درجات الطلاب وعرض التفاصيل.</p>
        </div>

        <a href="{{ route('exams.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-5 py-3 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-100">
            <span>＋</span>
            إضافة امتحان جديد
        </a>
    </div>

    @include('exams.partials.alerts')

    <!-- جدول الامتحانات -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-6 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800">قائمة الامتحانات</h3>
                <p class="text-sm text-slate-500 mt-1">جميع الامتحانات المسجلة في القسم.</p>
            </div>

            <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold">
                عدد السجلات: {{ $exams->count() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="px-5 py-4 font-bold">الرقم</th>
                        <th class="px-5 py-4 font-bold">النوع</th>
                        <th class="px-5 py-4 font-bold">المادة</th>
                        <th class="px-5 py-4 font-bold">الشعبة</th>
                        <th class="px-5 py-4 font-bold">التاريخ والوقت</th>
                        <th class="px-5 py-4 font-bold">القاعة</th>
                        <th class="px-5 py-4 font-bold">الدنيا / العظمى</th>
                        <th class="px-5 py-4 font-bold">الدرجات</th>
                        <th class="px-5 py-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">
                    @include('exams.partials.rows')
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                {{ $isManager ? 'بصفتك مدير القسم يمكنك حذف الامتحانات.' : 'يتم عرض الإجراءات حسب صلاحيات موظف القسم.' }}
            </p>
        </div>
    </div>

@endsection
