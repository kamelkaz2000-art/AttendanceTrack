@extends('layouts.app')

@section('content')
<div class="container">
    <h2>إدارة المقررات الدراسية</h2>
    <a href="{{ route('courses.create') }}" class="btn btn-primary mb-3">إضافة مقرر جديد</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم المقرر</th>
                <th>رمز المقرر (Code)</th>
                <th>عدد الساعات المعتمدة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->course_name }}</td>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->credit_hours }}</td>
                    <td>
                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-info">عرض</a>
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">لا يوجد مقررات حالياً.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $courses->links() }}
</div>
@endsection