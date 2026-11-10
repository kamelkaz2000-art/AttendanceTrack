{{-- الكتلة الخامسة: إضافة امتحان جديد --}}

@extends('layouts.app')

@section('title', 'إضافة امتحان - AttendanceTrack')

@section('content')

    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
        <h2 class="text-3xl font-extrabold text-slate-800">إضافة امتحان جديد</h2>
        <p class="text-slate-500 mt-2">أدخل بيانات الامتحان، ثم يمكنك رصد درجات الطلاب من صفحة التفاصيل.</p>
    </div>

    @include('exams.partials.alerts')

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-800">بيانات الامتحان</h3>
            <p class="text-sm text-slate-500 mt-1">جميع الحقول المطلوبة يجب تعبئتها قبل الحفظ.</p>
        </div>

        <form action="{{ route('exams.store') }}" method="POST">
            @csrf

            <div class="p-6 md:p-8">
                @include('exams.partials.form')
            </div>

            <div class="flex flex-col sm:flex-row justify-between gap-3 bg-slate-50 border-t border-slate-100 p-6">
                <a href="{{ route('exams.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                    العودة إلى الامتحانات
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm shadow-lg shadow-blue-100 transition">
                    حفظ الامتحان
                </button>
            </div>
        </form>
    </div>

@endsection
