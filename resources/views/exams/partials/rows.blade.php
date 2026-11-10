{{-- الكتلة الخامسة: صفوف جدول الامتحانات --}}

@forelse ($exams as $exam)

    <tr class="hover:bg-slate-50 transition">

        <td class="px-5 py-4 text-slate-500 font-semibold">{{ $exam->id }}</td>

        <td class="px-5 py-4">
            <span class="bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-full text-xs font-bold">
                {{ $exam->examType?->type_name ?? 'غير محدد' }}
            </span>
        </td>

        <td class="px-5 py-4 font-bold text-slate-800">{{ $exam->course?->course_name ?? 'غير محدد' }}</td>

        <td class="px-5 py-4 text-slate-600">{{ $exam->section?->section_name ?? 'غير محدد' }}</td>

        <td class="px-5 py-4 text-slate-600">
            {{ $exam->exam_date?->format('Y-m-d') }}
            <span class="text-slate-400 text-xs">{{ substr($exam->exam_time, 0, 5) }}</span>
        </td>

        <td class="px-5 py-4 text-slate-600">{{ $exam->room }}</td>

        <td class="px-5 py-4 text-slate-600">{{ (float) $exam->min_grade }} / {{ (float) $exam->max_grade }}</td>

        <td class="px-5 py-4">
            @if ($exam->grades_count > 0)
                <span class="bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold">
                    {{ $exam->grades_count }} درجة
                </span>
            @else
                <span class="bg-slate-100 text-slate-500 px-3 py-1.5 rounded-full text-xs font-bold">
                    لم ترصد
                </span>
            @endif
        </td>

        <!-- الإجراءات حسب الصلاحية -->
        <td class="px-5 py-4">
            <div class="flex items-center justify-center gap-2">

                <a href="{{ route('exams.show', $exam->id) }}"
                   class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                    التفاصيل
                </a>

                <a href="{{ route('grades.edit', $exam->id) }}"
                   class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                    رصد الدرجات
                </a>

                <a href="{{ route('exams.edit', $exam->id) }}"
                   class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                    تعديل
                </a>

                @if ($isManager)
                    @if ($exam->grades_count == 0)
                        <form action="{{ route('exams.destroy', $exam->id) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الامتحان؟')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                حذف
                            </button>
                        </form>
                    @else
                        <span title="لا يمكن حذف امتحان له درجات مسجلة"
                              class="bg-slate-100 text-slate-400 px-3 py-2 rounded-lg text-xs font-bold cursor-not-allowed">
                            حذف
                        </span>
                    @endif
                @endif

            </div>
        </td>

    </tr>

@empty

    <tr>
        <td colspan="9" class="px-5 py-16 text-center">
            <div class="text-4xl mb-3">📝</div>
            <h3 class="text-lg font-bold text-slate-700 mb-2">لا توجد امتحانات حالياً</h3>
            <p class="text-sm text-slate-400">يمكنك إضافة امتحان جديد من خلال الزر الموجود أعلى الصفحة.</p>
        </td>
    </tr>

@endforelse
