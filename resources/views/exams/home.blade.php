{{-- الكتلة الخامسة: الصفحة الرئيسية لقسم الامتحانات (الأزرار تتغير حسب الدور) --}}

@extends('layouts.app')

@section('title', 'قسم الامتحانات - AttendanceTrack')

@section('content')

    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">قسم الامتحانات والدرجات</p>
        <h2 class="text-3xl font-extrabold text-slate-800 mb-3">مرحباً، {{ Auth::user()->full_name }}</h2>
        <p class="text-slate-500">
            {{ $isManager
                ? 'بصفتك مدير القسم يمكنك إضافة الامتحانات وتعديلها وحذفها ورصد الدرجات وعرض التفاصيل.'
                : 'بصفتك موظف القسم يمكنك إضافة الامتحانات وتعديلها ورصد الدرجات وعرض التفاصيل.' }}
        </p>
    </div>

    <!-- الإحصائيات -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['📝', 'إجمالي الامتحانات', $stats['exams'], 'bg-blue-50 text-blue-700'],
            ['📅', 'امتحانات قادمة', $stats['upcoming'], 'bg-amber-50 text-amber-700'],
            ['✅', 'درجات مرصودة', $stats['grades'], 'bg-green-50 text-green-700'],
            ['🏷️', 'أنواع الامتحانات', $stats['types'], 'bg-indigo-50 text-indigo-700'],
        ] as [$icon, $label, $value, $color])
            <div class="rounded-2xl p-5 {{ $color }}">
                <div class="text-2xl mb-2">{{ $icon }}</div>
                <p class="text-xs font-bold opacity-80">{{ $label }}</p>
                <p class="text-3xl font-extrabold">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <!-- أزرار لوحة التحكم حسب الصلاحيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">
            <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center text-3xl mb-5">📝</div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-3">الامتحانات</h3>
            <p class="text-slate-500 text-sm leading-7 mb-6">إضافة امتحانات جديدة وعرضها وتعديلها وعرض تفاصيلها.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('exams.index') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-3 rounded-xl text-sm font-bold transition">عرض الامتحانات</a>
                <a href="{{ route('exams.create') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-xl text-sm font-bold transition">إضافة امتحان</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 hover:shadow-lg transition">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-3xl mb-5">✍️</div>
            <h3 class="text-xl font-extrabold text-slate-800 mb-3">رصد الدرجات</h3>
            <p class="text-slate-500 text-sm leading-7 mb-6">اختر امتحاناً من القائمة لإدخال درجات طلاب الشعبة أو تعديلها.</p>
            <a href="{{ route('exams.index') }}" class="inline-block bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold transition">اختيار امتحان</a>
        </div>

    </div>

    <!-- الامتحانات القادمة -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-800">الامتحانات القادمة</h3>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($upcomingExams as $exam)
                <a href="{{ route('exams.show', $exam->id) }}" class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 hover:bg-slate-50 transition">
                    <div>
                        <p class="font-bold text-slate-800">{{ $exam->examType?->type_name }} - {{ $exam->course?->course_name }}</p>
                        <p class="text-xs text-slate-500">{{ $exam->section?->section_name }} · {{ $exam->room }}</p>
                    </div>
                    <span class="bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg text-xs font-bold">
                        {{ $exam->exam_date?->format('Y-m-d') }} - {{ substr($exam->exam_time, 0, 5) }}
                    </span>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-sm text-slate-400">لا توجد امتحانات قادمة.</p>
            @endforelse
        </div>
    </div>

@endsection
