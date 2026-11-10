{{-- الكتلة الخامسة: تعديل بيانات الامتحان --}}

@extends('layouts.app')

@section('title', 'تعديل امتحان - AttendanceTrack')

@section('content')

    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
        <h2 class="text-3xl font-extrabold text-slate-800">تعديل الامتحان #{{ $exam->id }}</h2>
        <p class="text-slate-500 mt-2">تحديث بيانات الامتحان. لتعديل الدرجات استخدم صفحة رصد الدرجات.</p>
    </div>

    @include('exams.partials.alerts')

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-800">بيانات الامتحان</h3>
            <p class="text-sm text-slate-500 mt-1">لا يمكن جعل الدرجة العظمى أقل من أعلى درجة مرصودة.</p>
        </div>

        <form action="{{ route('exams.update', $exam->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8">
                @include('exams.partials.form', ['exam' => $exam])
            </div>

            <div class="flex flex-col sm:flex-row justify-between gap-3 bg-slate-50 border-t border-slate-100 p-6">
                <a href="{{ route('exams.show', $exam->id) }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                    العودة إلى التفاصيل
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm shadow-lg shadow-blue-100 transition">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

@endsection
