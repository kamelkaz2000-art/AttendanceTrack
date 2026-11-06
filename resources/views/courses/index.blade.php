@extends('layouts.app')

@section('title', 'إدارة المقررات')

@section('content')
@php
    // الصلاحيات: الحذف وتغيير الحالة لمدير القسم (ومدير النظام) فقط
    $canManage = in_array(auth()->user()->role->role_name, ['system_admin', 'department_manager']);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">إدارة المقررات الدراسية</h2>
    <!-- زر الإضافة يظهر للموظف والمدير -->
    <a href="{{ route('courses.create') }}" class="btn btn-primary">إضافة مقرر جديد</a>
</div>

<table class="table table-bordered table-striped bg-white align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>اسم المقرر</th>
            <th>رمز المقرر</th>
            <th>الساعات</th>
            <th>القسم</th>
            <th>المدرس</th>
            <th>الفصل</th>
            <th>الحالة</th>
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
                <td>{{ $course->department->department_name ?? '-' }}</td>
                <td>{{ $course->instructor->full_name ?? '-' }}</td>
                <td>{{ $course->semester->semester_name ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                        {{ $course->status === 'active' ? 'مفعلة' : 'معطلة' }}
                    </span>
                </td>
                <td class="text-nowrap">
                    <!-- العرض والتعديل متاحان لمدير القسم وموظف القسم -->
                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-warning">تعديل</a>

                    <!-- الحذف وتغيير الحالة: لمدير القسم فقط -->
                    @if($canManage)
                        <form action="{{ route('courses.changeStatus', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-secondary">
                                {{ $course->status === 'active' ? 'تعطيل' : 'تفعيل' }}
                            </button>
                        </form>

                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">لا يوجد مقررات حالياً.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $courses->links() }}
@endsection
