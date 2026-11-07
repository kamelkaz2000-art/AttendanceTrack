{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| واجهة عرض كل المواد (Grid View) على شكل جدول
| أزرار الحذف وتغيير الحالة تظهر فقط للمدير ($canManage = true)
| المتغيرات القادمة من CourseController@index: $courses, $canManage, $title
--}}
@extends('courses.layout')

@section('title', $title)

@section('content')
<div class="container py-3">

    {{-- العنوان وزر الإضافة --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h1 class="h2 mb-0"><i class="bi bi-list-ul"></i> قائمة المواد</h1>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> إضافة مادة جديدة
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            {{-- table-responsive: يسمح بتمرير الجدول أفقياً على الجوال --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الرمز</th>
                            <th>اسم المادة</th>
                            <th>الساعات</th>
                            <th>القسم</th>
                            <th>المدرس المسؤول</th>
                            <th>الفصل</th>
                            <th>السنة</th>
                            <th>الحالة</th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td><span class="badge bg-dark">{{ $course->course_code }}</span></td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->credit_hours }}</td>
                                <td>{{ $course->department?->department_name ?? 'غير محدد' }}</td>
                                <td>{{ $course->instructor?->full_name ?? 'غير محدد' }}</td>
                                <td>{{ $course->semester?->semester_name ?? 'غير محدد' }}</td>
                                <td>{{ $course->academic_year }}</td>
                                <td>
                                    <span class="badge {{ $course->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $course->status_text }}
                                    </span>
                                </td>
                                <td class="text-center text-nowrap">
                                    {{-- عرض التفاصيل: للجميع --}}
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- التعديل: للجميع (مدير وموظف) --}}
                                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- تغيير الحالة والحذف: للمدير فقط --}}
                                    @if ($canManage)
                                        <form action="{{ route('courses.toggleStatus', $course->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="btn btn-sm {{ $course->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                                title="{{ $course->status === 'active' ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="bi {{ $course->status === 'active' ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"
                                                onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    لا توجد مواد حالياً
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted small">
            عدد المواد: {{ $courses->count() }}
        </div>
    </div>

</div>
@endsection
