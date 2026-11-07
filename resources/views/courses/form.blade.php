{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| حقول نموذج المادة (مشتركة بين صفحة الإضافة وصفحة التعديل لتجنب تكرار الكود - DRY)
| - في صفحة الإضافة: $course غير موجود، فتكون الحقول فارغة
| - في صفحة التعديل: $course موجود، فتظهر قيم المادة الحالية
| old('field', default): يعيد القيمة التي كتبها المستخدم إذا فشل التحقق، وإلا القيمة الافتراضية
| @error / is-invalid: لإظهار رسالة الخطأ تحت الحقل باللون الأحمر
| التحقق على مستوى الواجهة: required / minlength / maxlength / pattern / min / max (HTML5)
--}}
@php
    $course = $course ?? null;
@endphp

<div class="row">
    {{-- اسم المادة --}}
    <div class="col-md-8 mb-3">
        <label for="course_name" class="form-label">اسم المادة <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('course_name') is-invalid @enderror"
               id="course_name" name="course_name"
               value="{{ old('course_name', $course?->course_name) }}"
               minlength="3" maxlength="100" required>
        @error('course_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- رمز المادة --}}
    <div class="col-md-4 mb-3">
        <label for="course_code" class="form-label">رمز المادة <span class="text-danger">*</span></label>
        <input type="text" class="form-control text-uppercase @error('course_code') is-invalid @enderror"
               id="course_code" name="course_code"
               value="{{ old('course_code', $course?->course_code) }}"
               placeholder="مثال: CS101" maxlength="20"
               pattern="[A-Za-z]{2,4}[0-9]{3}" title="2 إلى 4 أحرف إنكليزية ثم 3 أرقام مثل CS101" required>
        @error('course_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    {{-- عدد الساعات --}}
    <div class="col-md-4 mb-3">
        <label for="credit_hours" class="form-label">عدد الساعات <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('credit_hours') is-invalid @enderror"
               id="credit_hours" name="credit_hours"
               value="{{ old('credit_hours', $course?->credit_hours) }}"
               min="1" max="6" step="1" required>
        @error('credit_hours')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- القسم (قائمة منسدلة) --}}
    <div class="col-md-4 mb-3">
        <label for="department_id" class="form-label">القسم <span class="text-danger">*</span></label>
        <select class="form-select @error('department_id') is-invalid @enderror"
                id="department_id" name="department_id" required>
            <option value="">اختر القسم</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ old('department_id', $course?->department_id) == $dept->id ? 'selected' : '' }}>
                    {{ $dept->department_name }}
                </option>
            @endforeach
        </select>
        @error('department_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- السنة الدراسية --}}
    <div class="col-md-4 mb-3">
        <label for="academic_year" class="form-label">السنة الدراسية <span class="text-danger">*</span></label>
        <select class="form-select @error('academic_year') is-invalid @enderror"
                id="academic_year" name="academic_year" required>
            <option value="">اختر السنة</option>
            @foreach (['1' => 'الأولى', '2' => 'الثانية', '3' => 'الثالثة', '4' => 'الرابعة'] as $value => $label)
                <option value="{{ $value }}" {{ old('academic_year', $course?->academic_year) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('academic_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    {{-- المدرس المسؤول --}}
    <div class="col-md-6 mb-3">
        <label for="instructor_id" class="form-label">المدرس المسؤول <span class="text-danger">*</span></label>
        <select class="form-select @error('instructor_id') is-invalid @enderror"
                id="instructor_id" name="instructor_id" required>
            <option value="">اختر المدرس</option>
            @foreach ($instructors as $instructor)
                <option value="{{ $instructor->id }}" {{ old('instructor_id', $course?->instructor_id) == $instructor->id ? 'selected' : '' }}>
                    {{ $instructor->full_name }}
                </option>
            @endforeach
        </select>
        @error('instructor_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- الفصل الدراسي --}}
    <div class="col-md-6 mb-3">
        <label for="semester_id" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
        <select class="form-select @error('semester_id') is-invalid @enderror"
                id="semester_id" name="semester_id" required>
            <option value="">اختر الفصل</option>
            @foreach ($semesters as $semester)
                <option value="{{ $semester->id }}" {{ old('semester_id', $course?->semester_id) == $semester->id ? 'selected' : '' }}>
                    {{ $semester->semester_name }}
                </option>
            @endforeach
        </select>
        @error('semester_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- المتطلب السابق (اختياري) --}}
<div class="mb-3">
    <label for="prerequisite_id" class="form-label">المتطلب السابق <small class="text-muted">(اختياري)</small></label>
    <select class="form-select @error('prerequisite_id') is-invalid @enderror"
            id="prerequisite_id" name="prerequisite_id">
        <option value="">بدون متطلب سابق</option>
        @foreach ($prerequisites as $prerequisite)
            <option value="{{ $prerequisite->id }}" {{ old('prerequisite_id', $course?->prerequisite_id) == $prerequisite->id ? 'selected' : '' }}>
                {{ $prerequisite->course_code }} - {{ $prerequisite->course_name }}
            </option>
        @endforeach
    </select>
    @error('prerequisite_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- الوصف (اختياري) --}}
<div class="mb-3">
    <label for="description" class="form-label">الوصف <small class="text-muted">(اختياري - حتى 500 حرف)</small></label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description" name="description" rows="3" maxlength="500">{{ old('description', $course?->description) }}</textarea>
    <div class="form-text"><span id="description-count">0</span> / 500</div>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- تحقق إضافي بالـ JavaScript على مستوى الواجهة --}}
@push('scripts')
<script>
    // 1. تحويل رمز المادة إلى أحرف كبيرة أثناء الكتابة
    const codeInput = document.getElementById('course_code');
    codeInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/\s/g, '');
    });

    // 2. عداد أحرف الوصف
    const description = document.getElementById('description');
    const counter = document.getElementById('description-count');
    function updateCounter() {
        counter.textContent = description.value.length;
    }
    description.addEventListener('input', updateCounter);
    updateCounter();

    // 3. رسالة واضحة إذا كان عدد الساعات خارج المجال 1 - 6 (تمنع إرسال النموذج)
    const hours = document.getElementById('credit_hours');
    hours.addEventListener('input', function () {
        const value = Number(this.value);
        if (this.value !== '' && (!Number.isInteger(value) || value < 1 || value > 6)) {
            this.setCustomValidity('عدد الساعات يجب أن يكون عدداً صحيحاً بين 1 و 6');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush
