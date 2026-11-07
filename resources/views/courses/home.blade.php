{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| الصفحة الرئيسية للكتلة مع لوحة التحكم (Dashboard)
| تعرض: إحصائيات المواد + أزرار العمليات حسب الدور + صلاحيات المستخدم + آخر المواد المضافة
| المتغيرات القادمة من CourseController@home:
| $totalCourses, $activeCourses, $inactiveCourses, $totalHours, $latestCourses, $canManage, $title
--}}
@extends('courses.layout')

@section('title', $title)

@section('content')
<div class="container py-3">

    {{-- عنوان الصفحة والترحيب --}}
    <div class="mb-4">
        <h1 class="h2"><i class="bi bi-speedometer2"></i> لوحة تحكم إدارة المواد</h1>
        <p class="text-muted mb-0">
            مرحباً {{ auth()->user()->full_name }}،
            أنت مسجل كـ <strong>{{ auth()->user()->role?->description }}</strong>
            في {{ auth()->user()->department?->department_name }}
        </p>
    </div>

    {{-- بطاقات الإحصائيات --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">عدد المواد</div>
                        <div class="fs-3 fw-bold">{{ $totalCourses }}</div>
                    </div>
                    <i class="bi bi-journal-text stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 bg-success text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">المواد المفعلة</div>
                        <div class="fs-3 fw-bold">{{ $activeCourses }}</div>
                    </div>
                    <i class="bi bi-check-circle stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 bg-secondary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">المواد المعطلة</div>
                        <div class="fs-3 fw-bold">{{ $inactiveCourses }}</div>
                    </div>
                    <i class="bi bi-slash-circle stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 bg-info text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small">مجموع الساعات</div>
                        <div class="fs-3 fw-bold">{{ $totalHours }}</div>
                    </div>
                    <i class="bi bi-clock-history stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- أزرار العمليات السريعة --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header"><i class="bi bi-lightning-charge"></i> العمليات</div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul"></i> عرض كل المواد
                    </a>
                    <a href="{{ route('courses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> إضافة مادة جديدة
                    </a>
                </div>
            </div>
        </div>

        {{-- صلاحيات المستخدم الحالي: تتغير حسب الدور (مدير / موظف) --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header"><i class="bi bi-shield-lock"></i> صلاحياتك في هذه الكتلة</div>
                <div class="card-body">
                    <span class="badge bg-success mb-1"><i class="bi bi-check"></i> إضافة مادة</span>
                    <span class="badge bg-success mb-1"><i class="bi bi-check"></i> تعديل مادة</span>
                    <span class="badge bg-success mb-1"><i class="bi bi-check"></i> عرض المواد</span>
                    <span class="badge bg-success mb-1"><i class="bi bi-check"></i> عرض التفاصيل</span>

                    @if ($canManage)
                        <span class="badge bg-success mb-1"><i class="bi bi-check"></i> حذف مادة</span>
                        <span class="badge bg-success mb-1"><i class="bi bi-check"></i> تغيير الحالة</span>
                    @else
                        <span class="badge bg-light text-muted border mb-1"><i class="bi bi-x"></i> حذف مادة (للمدير فقط)</span>
                        <span class="badge bg-light text-muted border mb-1"><i class="bi bi-x"></i> تغيير الحالة (للمدير فقط)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- آخر المواد المضافة --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock"></i> آخر المواد المضافة</span>
            <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary">عرض الكل</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الرمز</th>
                            <th>اسم المادة</th>
                            <th>القسم</th>
                            <th>المدرس</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestCourses as $course)
                            <tr>
                                <td><span class="badge bg-dark">{{ $course->course_code }}</span></td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->department?->department_name ?? 'غير محدد' }}</td>
                                <td>{{ $course->instructor?->full_name ?? 'غير محدد' }}</td>
                                <td>
                                    <span class="badge {{ $course->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $course->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">لا توجد مواد حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
