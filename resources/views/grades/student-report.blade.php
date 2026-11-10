{{-- الكتلة الخامسة: كشف الدرجات النهائي لطالب واحد (مدير القسم) --}}

@extends('layouts.app')

@section('title', 'كشف درجات الطالب - AttendanceTrack')

@section('content')

    @php
        $statusLabels = [
            'passed' => ['ناجح', 'bg-green-50 text-green-700'],
            'failed' => ['راسب', 'bg-red-50 text-red-700'],
            'incomplete' => ['غير مكتمل', 'bg-slate-100 text-slate-500'],
        ];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-blue-600 font-bold text-sm mb-2">إصدار النتائج</p>
            <h2 class="text-3xl font-extrabold text-slate-800">كشف الدرجات النهائي</h2>
            <p class="text-slate-500 mt-2">
                {{ $student->full_name }} · الرقم الجامعي {{ $student->university_id }} · السنة {{ $student->academic_year }}
            </p>
        </div>

        <div class="flex gap-2 print:hidden">
            <button type="button" onclick="window.print()"
                class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-xl font-bold text-sm transition">
                🖨️ طباعة
            </button>
            <a href="{{ url()->previous() }}"
               class="px-5 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                رجوع
            </a>
        </div>
    </div>

    @forelse ($courses as $item)
        @php $result = $item['result']; @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="flex flex-wrap items-center justify-between gap-2 p-6 border-b border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-800">
                    {{ $item['section']->course?->course_name }}
                    <span class="text-sm text-slate-400 font-normal">({{ $item['section']->course?->course_code }} - {{ $item['section']->section_name }})</span>
                </h3>

                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusLabels[$result['status']][1] }}">
                    {{ $statusLabels[$result['status']][0] }}
                    @if ($result['percentage'] !== null) · {{ $result['percentage'] }}% @endif
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right whitespace-nowrap text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-bold">الامتحان</th>
                            <th class="px-5 py-3 font-bold">التاريخ</th>
                            <th class="px-5 py-3 font-bold">الدرجة</th>
                            <th class="px-5 py-3 font-bold">درجة النجاح</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($item['exams'] as $exam)
                            @php $grade = $item['grades']->get($exam->id); @endphp
                            <tr>
                                <td class="px-5 py-3 font-bold text-slate-800">{{ $exam->examType?->type_name }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $exam->exam_date?->format('Y-m-d') }}</td>
                                <td class="px-5 py-3">{{ $grade ? (float) $grade->grade : '—' }} / {{ (float) $exam->max_grade }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ (float) $exam->min_grade }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">لا توجد امتحانات لهذه الشعبة بعد.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($item['exams']->isNotEmpty())
                        <tfoot class="bg-slate-50 font-bold">
                            <tr>
                                <td class="px-5 py-3" colspan="2">المجموع</td>
                                <td class="px-5 py-3" colspan="2">{{ $result['total'] }} / {{ $result['max_total'] }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center text-slate-400">
            الطالب غير مسجل في أي شعبة.
        </div>
    @endforelse

@endsection
