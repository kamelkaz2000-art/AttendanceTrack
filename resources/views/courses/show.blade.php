{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| واجهة العرض المخصص: تفاصيل مادة واحدة
| تعرض كل بيانات المادة + المتطلب السابق + المواد التي تعتمد عليها + الشعب والمحاضرات المرتبطة
| أزرار الحذف وتغيير الحالة تظهر للمدير فقط
| المتغيرات القادمة من CourseController@show: $course, $canManage, $title
--}}
@extends('courses.layout')

@section('title', $title)

@section('content')
<div class="container py-3">

    <div class="row g-3">
        {{-- بيانات المادة --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 h4"><i class="bi bi-journal-text"></i> تفاصيل المادة</h3>
                    <span class="badge {{ $course->status === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ $course->status_text }}
                    </span>
                </div>

                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th style="width: 35%;">اسم المادة</th>
                            <td>{{ $course->course_name }}</td>
                        </tr>
                        <tr>
                            <th>رمز المادة</th>
                            <td><span class="badge bg-dark">{{ $course->course_code }}</span></td>
                        </tr>
                        <tr>
                            <th>عدد الساعات</th>
                            <td>{{ $course->credit_hours }}</td>
                        </tr>
                        <tr>
                            <th>القسم</th>
                            <td>{{ $course->department?->department_name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <th>المدرس المسؤول</th>
                            <td>{{ $course->instructor?->full_name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <th>الفصل الدراسي</th>
                            <td>
                                {{ $course->semester?->semester_name ?? 'غير محدد' }}
                                @if ($course->semester)
                                    <small class="text-muted">
                                        ({{ $course->semester->start_date->format('Y-m-d') }} إلى {{ $course->semester->end_date->format('Y-m-d') }})
                                    </small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>السنة الدراسية</th>
                            <td>{{ $course->academic_year }}</td>
                        </tr>
                        <tr>
                            <th>المتطلب السابق</th>
                            <td>
                                @if ($course->prerequisite)
                                    <a href="{{ route('courses.show', $course->prerequisite->id) }}">
                                        {{ $course->prerequisite->course_code }} - {{ $course->prerequisite->course_name }}
                                    </a>
                                @else
                                    <span class="text-muted">لا يوجد</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>الوصف</th>
                            <td>{{ $course->description ?? 'لا يوجد وصف' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإضافة</th>
                            <td>{{ $course->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تعديل</th>
                            <td>{{ $course->updated_at?->format('Y-m-d H:i') ?? 'لم يتم التعديل' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- أزرار العمليات --}}
                <div class="card-footer d-flex flex-wrap gap-2">
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> العودة للقائمة
                    </a>

                    <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> تعديل
                    </a>

                    {{-- للمدير فقط --}}
                    @if ($canManage)
                        <form action="{{ route('courses.toggleStatus', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $course->status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                <i class="bi bi-arrow-repeat"></i>
                                {{ $course->status === 'active' ? 'تعطيل المادة' : 'تفعيل المادة' }}
                            </button>
                        </form>

                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ارتباطات المادة --}}
        <div class="col-lg-5">
            {{-- ملخص الارتباطات --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header"><i class="bi bi-diagram-3"></i> ارتباطات المادة</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        عدد الشعب <span class="badge bg-primary">{{ $course->sections->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        عدد المحاضرات <span class="badge bg-primary">{{ $course->lectures->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        عدد الامتحانات <span class="badge bg-primary">{{ $course->exams_count }}</span>
                    </li>
                </ul>
            </div>

            {{-- المواد التي تحتاج هذه المادة كمتطلب سابق --}}
            <div class="card shadow-sm">
                <div class="card-header"><i class="bi bi-arrow-down-circle"></i> مواد تعتمد على هذه المادة</div>
                <ul class="list-group list-group-flush">
                    @forelse ($course->prerequisiteCourses as $dependent)
                        <li class="list-group-item">
                            <a href="{{ route('courses.show', $dependent->id) }}">
                                {{ $dependent->course_code }} - {{ $dependent->course_name }}
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">لا توجد</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- الشعب المرتبطة بالمادة --}}
    <div class="card shadow-sm mt-3">
        <div class="card-header"><i class="bi bi-people"></i> الشعب المرتبطة بالمادة</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>اسم الشعبة</th>
                            <th>المدرس</th>
                            <th>الفصل</th>
                            <th>السعة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($course->sections as $section)
                            <tr>
                                <td>{{ $section->section_name }}</td>
                                <td>{{ $section->instructor?->full_name ?? 'غير محدد' }}</td>
                                <td>{{ $section->semester?->semester_name ?? 'غير محدد' }}</td>
                                <td>{{ $section->capacity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">لا توجد شعب مرتبطة بهذه المادة حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- المحاضرات المرتبطة بالمادة --}}
    <div class="card shadow-sm mt-3">
        <div class="card-header"><i class="bi bi-easel"></i> المحاضرات المرتبطة بالمادة</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>عنوان المحاضرة</th>
                            <th>التاريخ</th>
                            <th>الوقت</th>
                            <th>الشعبة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($course->lectures as $lecture)
                            <tr>
                                <td>{{ $lecture->title }}</td>
                                <td>{{ $lecture->lecture_date?->format('Y-m-d') }}</td>
                                <td>{{ $lecture->lecture_time }}</td>
                                <td>{{ $lecture->section?->section_name ?? 'غير محدد' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">لا توجد محاضرات مرتبطة بهذه المادة حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
