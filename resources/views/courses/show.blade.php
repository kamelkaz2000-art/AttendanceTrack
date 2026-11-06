@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تفاصيل المقرر</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $course->course_name }} ({{ $course->course_code }})</h4>
            <hr>
            <p class="card-text"><strong>الساعات المعتمدة:</strong> {{ $course->credit_hours }}</p>
            <p class="card-text"><strong>القسم:</strong> {{ $course->department->department_name ?? 'غير محدد' }}</p>
            <p class="card-text"><strong>المدرس المسؤول:</strong> {{ $course->instructor->full_name ?? $course->instructor->name ?? 'غير محدد' }}</p>
            <p class="card-text"><strong>الفصل الدراسي:</strong> {{ $course->semester->semester_name ?? 'غير محدد' }}</p>
            <p class="card-text"><strong>السنة الدراسية:</strong> {{ $course->academic_year }}</p>
            <p class="card-text"><strong>المتطلب السابق:</strong> {{ $course->prerequisite->course_name ?? 'لا يوجد' }}</p>
            <p class="card-text"><strong>حالة المقرر:</strong> <span class="badge bg-{{ $course->status == 'active' ? 'success' : 'secondary' }}">{{ $course->status }}</span></p>
            <p class="card-text"><strong>الوصف:</strong> {{ $course->description ?? 'لا يوجد وصف' }}</p>
            
            <br>
            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning">تعديل</a>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>
    </div>
</div>
@endsection