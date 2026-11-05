@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تفاصيل المقرر</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $course->course_name }}</h4>
            <p class="card-text"><strong>رمز المقرر:</strong> {{ $course->course_code }}</p>
            <p class="card-text"><strong>الساعات المعتمدة:</strong> {{ $course->credit_hours }}</p>
            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning">تعديل</a>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>
    </div>
</div>
@endsection