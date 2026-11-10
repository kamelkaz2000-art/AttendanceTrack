{{-- الكتلة الخامسة: عرض كامل بيانات الامتحان مع درجات الطلاب --}}

@extends('layouts.app')

@section('title', 'تفاصيل الامتحان - AttendanceTrack')

@section('content')

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
            <h2 class="text-3xl font-extrabold text-slate-800">
                امتحان {{ $exam->examType?->type_name }} - {{ $exam->course?->course_name }}
            </h2>
            <p class="text-slate-500 mt-2">عرض بيانات الامتحان ودرجات طلاب الشعبة.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('grades.edit', $exam->id) }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-xl font-bold text-sm transition">
                رصد / تعديل الدرجات
            </a>

            <a href="{{ route('exams.edit', $exam->id) }}"
               class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-5 py-3 rounded-xl font-bold text-sm transition">
                تعديل الامتحان
            </a>

            @if ($isManager && $stats['recorded'] === 0)
                <form action="{{ route('exams.destroy', $exam->id) }}" method="POST"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الامتحان؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-700 px-5 py-3 rounded-xl font-bold text-sm transition">
                        حذف الامتحان
                    </button>
                </form>
            @endif
        </div>
    </div>

    @include('exams.partials.alerts')

    <!-- بيانات الامتحان -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 mb-6">
        <h4 class="text-lg font-extrabold text-slate-800 mb-6">بيانات الامتحان</h4>

        @php
            $details = [
                'رقم الامتحان' => $exam->id,
                'نوع الامتحان' => $exam->examType?->type_name,
                'المادة' => $exam->course ? $exam->course->course_name . ' (' . $exam->course->course_code . ')' : null,
                'الشعبة' => $exam->section?->section_name,
                'التاريخ' => $exam->exam_date?->format('Y-m-d'),
                'الوقت' => substr($exam->exam_time, 0, 5),
                'المدة' => $exam->duration . ' دقيقة',
                'القاعة' => $exam->room,
                'الدرجة العظمى' => (float) $exam->max_grade,
                'الدرجة الدنيا' => (float) $exam->min_grade,
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">
            @foreach ($details as $label => $value)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">{{ $label }}</p>
                    <p class="font-bold text-slate-800">{{ $value ?? 'غير محدد' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @foreach ([
            ['طلاب الشعبة', $stats['students'], 'bg-blue-50 text-blue-700'],
            ['درجات مرصودة', $stats['recorded'], 'bg-indigo-50 text-indigo-700'],
            ['المتوسط', $stats['average'] ?? '—', 'bg-amber-50 text-amber-700'],
            ['أعلى / أدنى', $stats['recorded'] ? (float) $stats['highest'] . ' / ' . (float) $stats['lowest'] : '—', 'bg-slate-100 text-slate-700'],
            ['الناجحون', $stats['recorded'] ? $stats['passed'] . ' من ' . $stats['recorded'] : '—', 'bg-green-50 text-green-700'],
        ] as [$label, $value, $color])
            <div class="rounded-2xl p-5 {{ $color }}">
                <p class="text-xs font-bold opacity-80 mb-1">{{ $label }}</p>
                <p class="text-2xl font-extrabold">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <!-- درجات الطلاب -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-800">درجات الطلاب</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="px-5 py-4 font-bold">الرقم الجامعي</th>
                        <th class="px-5 py-4 font-bold">اسم الطالب</th>
                        <th class="px-5 py-4 font-bold">الدرجة</th>
                        <th class="px-5 py-4 font-bold">النتيجة</th>
                        <th class="px-5 py-4 font-bold">تاريخ الرصد</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($grades as $grade)
                        @php $passed = (float) $grade->grade >= (float) $exam->min_grade; @endphp

                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 text-slate-500">{{ $grade->student?->university_id }}</td>
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $grade->student?->full_name }}</td>
                            <td class="px-5 py-4 font-bold">{{ (float) $grade->grade }} / {{ (float) $exam->max_grade }}</td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $passed ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $passed ? 'ناجح' : 'راسب' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-500">{{ $grade->recorded_date?->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="text-4xl mb-3">📋</div>
                                <h3 class="text-lg font-bold text-slate-700 mb-2">لم يتم رصد أي درجة بعد</h3>
                                <a href="{{ route('grades.edit', $exam->id) }}" class="text-sm text-blue-700 font-bold">رصد الدرجات الآن ←</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('exams.index') }}"
           class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
            العودة إلى الامتحانات
        </a>
    </div>

@endsection
