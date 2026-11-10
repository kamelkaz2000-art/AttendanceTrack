{{-- الكتلة الخامسة: رصد وتعديل درجات الطلاب في امتحان --}}

@extends('layouts.app')

@section('title', 'رصد الدرجات - AttendanceTrack')

@section('content')

    @php $maxGrade = (float) $exam->max_grade; @endphp

    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
        <h2 class="text-3xl font-extrabold text-slate-800">رصد الدرجات</h2>
        <p class="text-slate-500 mt-2">
            امتحان {{ $exam->examType?->type_name }} - {{ $exam->course?->course_name }}
            ({{ $exam->section?->section_name }}) · الدرجة العظمى {{ $maxGrade }} · درجة النجاح {{ (float) $exam->min_grade }}
        </p>
    </div>

    @include('exams.partials.alerts')

    <form action="{{ route('grades.update', $exam->id) }}" method="POST" id="grades-form"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 p-6 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800">طلاب الشعبة ({{ $students->count() }})</h3>
                <p class="text-sm text-slate-500 mt-1">اترك الحقل فارغاً إذا لم تُرصد درجة الطالب بعد.</p>
            </div>

            <div>
                <label for="recorded_date" class="block text-sm font-bold text-slate-700 mb-2">
                    تاريخ الرصد <span class="text-red-500">*</span>
                </label>
                <input type="date" id="recorded_date" name="recorded_date"
                       value="{{ old('recorded_date', now()->toDateString()) }}"
                       max="{{ now()->toDateString() }}" required
                       class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="px-5 py-4 font-bold">#</th>
                        <th class="px-5 py-4 font-bold">الرقم الجامعي</th>
                        <th class="px-5 py-4 font-bold">اسم الطالب</th>
                        <th class="px-5 py-4 font-bold">الدرجة (من {{ $maxGrade }})</th>
                        <th class="px-5 py-4 font-bold">آخر رصد</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($students as $index => $student)
                        @php $current = $currentGrades->get($student->id); @endphp

                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $student->university_id }}</td>
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $student->full_name }}</td>
                            <td class="px-5 py-3">
                                <input type="number" name="grades[{{ $student->id }}]"
                                       value="{{ old('grades.' . $student->id, $current ? (float) $current->grade : '') }}"
                                       min="0" max="{{ $maxGrade }}" step="0.25"
                                       placeholder="—"
                                       class="grade-input w-28 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            </td>
                            <td class="px-5 py-4 text-slate-400 text-xs">
                                {{ $current?->recorded_date?->format('Y-m-d') ?? 'لم ترصد' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="text-4xl mb-3">👥</div>
                                <h3 class="text-lg font-bold text-slate-700 mb-2">لا يوجد طلاب مسجلون في هذه الشعبة</h3>
                                <p class="text-sm text-slate-400">يتم تسجيل الطلاب في الشعب من قسم الطلاب.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-3 bg-slate-50 border-t border-slate-100 p-6">
            <a href="{{ route('exams.show', $exam->id) }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                العودة إلى التفاصيل
            </a>

            @if ($students->isNotEmpty())
                <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-100 transition">
                    حفظ الدرجات
                </button>
            @endif
        </div>
    </form>

@endsection

@push('scripts')
<script>
    // التحقق على مستوى الواجهة: الدرجة بين 0 والدرجة العظمى
    (function () {
        const max = {{ $maxGrade }};

        document.querySelectorAll('.grade-input').forEach(function (input) {
            input.addEventListener('input', function () {
                const value = parseFloat(input.value);
                let message = '';

                if (input.value !== '' && (isNaN(value) || value < 0)) {
                    message = 'الدرجة لا يمكن أن تقل عن الصفر';
                } else if (value > max) {
                    message = 'الدرجة لا يمكن أن تتجاوز ' + max;
                }

                input.setCustomValidity(message);
                input.classList.toggle('border-red-400', message !== '');
                input.title = message;
            });
        });
    })();
</script>
@endpush
