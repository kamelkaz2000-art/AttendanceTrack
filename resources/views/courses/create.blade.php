{{--
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| واجهة إدخال البيانات: إضافة مادة جديدة
| النموذج يرسل البيانات بطريقة POST إلى المسار courses.store
| @csrf: رمز حماية ضد هجمات CSRF (إجباري في كل نموذج POST)
| الحقول نفسها موجودة في الملف courses/form.blade.php
--}}
@extends('courses.layout')

@section('title', $title)

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="mb-0 h4"><i class="bi bi-journal-plus"></i> إضافة مادة جديدة</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('courses.store') }}" method="POST">
                        @csrf

                        {{-- حقول المادة (فارغة لأننا في صفحة الإضافة) --}}
                        @include('courses.form')

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-right"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ المادة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
