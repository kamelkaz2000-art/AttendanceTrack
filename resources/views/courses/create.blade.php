@extends('layouts.app')

@section('content')
<div class="container">
    <h2>إضافة مقرر جديد</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>اسم المقرر</label>
            <input type="text" name="course_name" class="form-control" value="{{ old('course_name') }}" required>
        </div>

        <div class="mb-3">
            <label>رمز المقرر (Course Code)</label>
            <input type="text" name="course_code" class="form-control" value="{{ old('course_code') }}" required>
        </div>

        <div class="mb-3">
            <label>عدد الساعات المعتمدة (1 إلى 6)</label>
            <input type="number" name="credit_hours" class="form-control" value="{{ old('credit_hours') }}" min="1" max="6" required>
        </div>

        <div class="mb-3">
            <label>القسم</label>
            <select name="department_id" class="form-control" required>
                <option value="">اختر القسم</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>المدرس المسؤول</label>
            <select name="instructor_id" class="form-control" required>
                <option value="">اختر المدرس</option>
                @foreach($instructors as $inst)
                    <option value="{{ $inst->id }}" {{ old('instructor_id') == $inst->id ? 'selected' : '' }}>{{ $inst->full_name ?? $inst->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>الفصل الدراسي</label>
            <select name="semester_id" class="form-control" required>
                <option value="">اختر الفصل الدراسي</option>
                @foreach($semesters as $sem)
                    <option value="{{ $sem->id }}" {{ old('semester_id') == $sem->id ? 'selected' : '' }}>{{ $sem->semester_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>السنة الدراسية</label>
            <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year', '2026-2027') }}" required>
        </div>

        <div class="mb-3">
            <label>المتطلب السابق (اختياري)</label>
            <select name="prerequisite_id" class="form-control">
                <option value="">لا يوجد</option>
                @foreach($courses as $crs)
                    <option value="{{ $crs->id }}" {{ old('prerequisite_id') == $crs->id ? 'selected' : '' }}>{{ $crs->course_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>وصف المقرر (اختياري)</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">حفظ المقرر</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection