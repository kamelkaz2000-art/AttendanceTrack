{{--
    الكتلة الخامسة: حقول نموذج الامتحان (مشتركة بين الإضافة والتعديل - DRY)
    المتغيرات: $exam (اختياري)، $examTypes، $courses، $sections
--}}

@php
    $exam = $exam ?? null;
    $inputClass = 'w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    $selectedCourse = old('course_id', $exam?->course_id);
    $selectedSection = old('section_id', $exam?->section_id);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- نوع الامتحان -->
    <div>
        <label for="exam_type_id" class="block text-sm font-bold text-slate-700 mb-2">
            نوع الامتحان <span class="text-red-500">*</span>
        </label>

        <select id="exam_type_id" name="exam_type_id" required class="{{ $inputClass }}">
            <option value="">اختر نوع الامتحان</option>

            @foreach ($examTypes as $type)
                <option value="{{ $type->id }}"
                    {{ old('exam_type_id', $exam?->exam_type_id) == $type->id ? 'selected' : '' }}>
                    {{ $type->type_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- القاعة -->
    <div>
        <label for="room" class="block text-sm font-bold text-slate-700 mb-2">
            القاعة <span class="text-red-500">*</span>
        </label>

        <input type="text" id="room" name="room"
               value="{{ old('room', $exam?->room) }}"
               required minlength="2" maxlength="50"
               placeholder="مثال: قاعة 101"
               class="{{ $inputClass }}">
    </div>

    <!-- المادة -->
    <div>
        <label for="course_id" class="block text-sm font-bold text-slate-700 mb-2">
            المادة <span class="text-red-500">*</span>
        </label>

        <select id="course_id" name="course_id" required class="{{ $inputClass }}">
            <option value="">اختر المادة</option>

            @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ $selectedCourse == $course->id ? 'selected' : '' }}>
                    {{ $course->course_name }} ({{ $course->course_code }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- الشعبة (تتم تصفيتها حسب المادة بالجافاسكربت) -->
    <div>
        <label for="section_id" class="block text-sm font-bold text-slate-700 mb-2">
            الشعبة <span class="text-red-500">*</span>
        </label>

        <select id="section_id" name="section_id" required class="{{ $inputClass }}">
            <option value="">اختر الشعبة</option>

            @foreach ($sections as $section)
                <option value="{{ $section->id }}"
                        data-course="{{ $section->course_id }}"
                        {{ $selectedSection == $section->id ? 'selected' : '' }}>
                    {{ $section->section_name }} - {{ $section->course?->course_name }}
                </option>
            @endforeach
        </select>

        <p class="text-xs text-slate-400 mt-2">تظهر فقط شعب المادة المختارة.</p>
    </div>

    <!-- تاريخ الامتحان -->
    <div>
        <label for="exam_date" class="block text-sm font-bold text-slate-700 mb-2">
            تاريخ الامتحان <span class="text-red-500">*</span>
        </label>

        <input type="date" id="exam_date" name="exam_date"
               value="{{ old('exam_date', $exam?->exam_date?->format('Y-m-d')) }}"
               required min="2000-01-01" max="2100-12-31"
               class="{{ $inputClass }}">
    </div>

    <!-- وقت الامتحان -->
    <div>
        <label for="exam_time" class="block text-sm font-bold text-slate-700 mb-2">
            وقت الامتحان <span class="text-red-500">*</span>
        </label>

        <input type="time" id="exam_time" name="exam_time"
               value="{{ old('exam_time', $exam ? substr($exam->exam_time, 0, 5) : '') }}"
               required
               class="{{ $inputClass }}">
    </div>

    <!-- المدة -->
    <div>
        <label for="duration" class="block text-sm font-bold text-slate-700 mb-2">
            المدة (بالدقائق) <span class="text-red-500">*</span>
        </label>

        <input type="number" id="duration" name="duration"
               value="{{ old('duration', $exam?->duration) }}"
               required min="1" max="600" step="1"
               placeholder="مثال: 90"
               class="{{ $inputClass }}">
    </div>

    <!-- الدرجة العظمى -->
    <div>
        <label for="max_grade" class="block text-sm font-bold text-slate-700 mb-2">
            الدرجة العظمى <span class="text-red-500">*</span>
        </label>

        <input type="number" id="max_grade" name="max_grade"
               value="{{ old('max_grade', $exam ? (float) $exam->max_grade : '') }}"
               required min="10" max="100" step="0.25"
               placeholder="بين 10 و 100"
               class="{{ $inputClass }}">
    </div>

    <!-- الدرجة الدنيا -->
    <div>
        <label for="min_grade" class="block text-sm font-bold text-slate-700 mb-2">
            الدرجة الدنيا (درجة النجاح) <span class="text-red-500">*</span>
        </label>

        <input type="number" id="min_grade" name="min_grade"
               value="{{ old('min_grade', $exam ? (float) $exam->min_grade : '') }}"
               required min="0" max="100" step="0.25"
               placeholder="أقل من الدرجة العظمى"
               class="{{ $inputClass }}">

        <p id="min_grade_error" class="hidden text-xs text-red-600 mt-2">
            الدرجة الدنيا يجب أن تكون أقل من الدرجة العظمى.
        </p>
    </div>

</div>

@push('scripts')
<script>
    // التحقق على مستوى الواجهة (JavaScript) قبل الإرسال للخادم
    (function () {
        const course = document.getElementById('course_id');
        const section = document.getElementById('section_id');
        const maxGrade = document.getElementById('max_grade');
        const minGrade = document.getElementById('min_grade');
        const minError = document.getElementById('min_grade_error');

        // إظهار شعب المادة المختارة فقط
        function filterSections() {
            const courseId = course.value;

            Array.from(section.options).forEach(function (option) {
                if (!option.value) return;

                const match = !courseId || option.dataset.course === courseId;
                option.hidden = !match;
                option.disabled = !match;

                if (!match && option.selected) {
                    section.value = '';
                }
            });
        }

        // الدرجة الدنيا أقل من العظمى
        function checkGrades() {
            const max = parseFloat(maxGrade.value);
            const min = parseFloat(minGrade.value);
            const invalid = !isNaN(max) && !isNaN(min) && min >= max;

            minGrade.setCustomValidity(invalid ? 'الدرجة الدنيا يجب أن تكون أقل من الدرجة العظمى' : '');
            minError.classList.toggle('hidden', !invalid);
        }

        course.addEventListener('change', filterSections);
        maxGrade.addEventListener('input', checkGrades);
        minGrade.addEventListener('input', checkGrades);

        filterSections();
        checkGrades();
    })();
</script>
@endpush
