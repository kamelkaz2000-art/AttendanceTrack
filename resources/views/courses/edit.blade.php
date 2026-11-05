@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تعديل بيانات المقرر</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('courses.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>اسم المقرر</label>
            <input type="text" name="course_name" class="form-control" value="{{ old('course_name', $course->course_name) }}" required>
        </div>
        <div class="mb-3">
            <label>رمز المقرر (Course Code)</label>
            <input type="text" name="course_code" class="form-control" value="{{ old('course_code', $course->course_code) }}" required>
        </div>
        <div class="mb-3">
            <label>عدد الساعات المعتمدة</label>
            <input type="number" name="credit_hours" class="form-control" value="{{ old('credit_hours', $course->credit_hours) }}" min="1" max="10" required>
        </div>
        <button type="submit" class="btn btn-primary">تحديث البيانات</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection