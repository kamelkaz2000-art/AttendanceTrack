{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| واجهة تعديل مادة موجودة
| النماذج في HTML تدعم GET و POST فقط، لذلك نستخدم @method('PUT') ليفهم Laravel أنه طلب تحديث
| الحقول نفسها موجودة في الملف courses/form.blade.php وتظهر فيها قيم المادة الحالية
| ملاحظة: تغيير الحالة لا يتم من هنا، بل من زر خاص للمدير فقط
--}}
@extends('courses.layout')

@section('title', $title)

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 h4"><i class="bi bi-pencil-square"></i> تعديل بيانات المادة</h3>
                    <span class="badge bg-dark">{{ $course->course_code }}</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- حقول المادة (مع القيم الحالية للمادة $course) --}}
                        @include('courses.form', ['course' => $course])

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-right"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
