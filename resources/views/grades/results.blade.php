{{-- الكتلة الخامسة: إصدار النتائج - كشف الدرجات النهائي لطلاب شعبة (مدير القسم) --}}

@extends('layouts.app')

@section('title', 'إصدار النتائج - AttendanceTrack')

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
            <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
            <h2 class="text-3xl font-extrabold text-slate-800">إصدار النتائج</h2>
            <p class="text-slate-500 mt-2">اختر الشعبة لإصدار كشف الدرجات النهائي لطلابها.</p>
        </div>

        @if ($section)
            <button type="button" onclick="window.print()"
                class="print:hidden inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-5 py-3 rounded-xl font-bold text-sm transition">
                🖨️ طباعة الكشف
            </button>
        @endif
    </div>

    @include('exams.partials.alerts')

    <!-- اختيار الشعبة -->
    <form action="{{ route('grades.results') }}" method="GET"
          class="print:hidden bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col sm:flex-row gap-4 sm:items-end">
        <div class="flex-1">
            <label for="section_id" class="block text-sm font-bold text-slate-700 mb-2">الشعبة</label>
            <select id="section_id" name="section_id" required onchange="this.form.submit()"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">اختر الشعبة</option>
                @foreach ($sections as $item)
                    <option value="{{ $item->id }}" {{ $section?->id == $item->id ? 'selected' : '' }}>
                        {{ $item->course?->course_name }} - {{ $item->section_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
            عرض الكشف
        </button>
    </form>

    @if ($section)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-6 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">
                        كشف درجات {{ $section->course?->course_name }} - {{ $section->section_name }}
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">
                        عدد الامتحانات: {{ $exams->count() }} · النتيجة "ناجح" إذا كان المجموع ≥ مجموع درجات النجاح.
                    </p>
                </div>

                <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold">
                    عدد الطلاب: {{ $rows->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-600 text-sm">
                        <tr>
                            <th class="px-5 py-4 font-bold">الرقم الجامعي</th>
                            <th class="px-5 py-4 font-bold">اسم الطالب</th>
                            @foreach ($exams as $exam)
                                <th class="px-5 py-4 font-bold">
                                    {{ $exam->examType?->type_name }}
                                    <span class="block text-xs text-slate-400 font-normal">من {{ (float) $exam->max_grade }}</span>
                                </th>
                            @endforeach
                            <th class="px-5 py-4 font-bold">المجموع</th>
                            <th class="px-5 py-4 font-bold">النسبة</th>
                            <th class="px-5 py-4 font-bold">النتيجة</th>
                            <th class="px-5 py-4 font-bold print:hidden">الكشف الفردي</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($rows as $row)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-slate-500">{{ $row['student']->university_id }}</td>
                                <td class="px-5 py-4 font-bold text-slate-800">{{ $row['student']->full_name }}</td>

                                @foreach ($exams as $exam)
                                    @php $grade = $row['grades']->get($exam->id); @endphp
                                    <td class="px-5 py-4 {{ $grade && (float) $grade->grade < (float) $exam->min_grade ? 'text-red-600 font-bold' : 'text-slate-700' }}">
                                        {{ $grade ? (float) $grade->grade : '—' }}
                                    </td>
                                @endforeach

                                <td class="px-5 py-4 font-bold">{{ $row['total'] }} / {{ $row['max_total'] }}</td>
                                <td class="px-5 py-4">{{ $row['percentage'] !== null ? $row['percentage'] . '%' : '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusLabels[$row['status']][1] }}">
                                        {{ $statusLabels[$row['status']][0] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 print:hidden">
                                    <a href="{{ route('grades.studentReport', $row['student']->id) }}"
                                       class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                        عرض الكشف
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $exams->count() + 6 }}" class="px-5 py-16 text-center text-slate-400">
                                    لا يوجد طلاب مسجلون في هذه الشعبة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
