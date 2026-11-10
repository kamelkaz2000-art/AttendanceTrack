{{-- الكتلة الخامسة: عرض الامتحانات مع البحث المتقدم (Ajax + jQuery) --}}

@extends('layouts.app')

@section('title', 'الامتحانات - AttendanceTrack')

@section('content')

    @php
        $filterClass = 'w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-sm';
    @endphp

    <!-- عنوان الصفحة -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
            <h2 class="text-3xl font-extrabold text-slate-800">إدارة الامتحانات</h2>
            <p class="text-slate-500 mt-2">البحث عن الامتحانات ورصد درجات الطلاب وعرض التفاصيل.</p>
        </div>

        <a href="{{ route('exams.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-5 py-3 rounded-xl font-bold text-sm transition shadow-lg shadow-blue-100">
            <span>＋</span>
            إضافة امتحان جديد
        </a>
    </div>

    @include('exams.partials.alerts')

    <!-- فلاتر البحث المتقدم -->
    <form id="search-form" action="{{ route('exams.index') }}" method="GET"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-extrabold text-slate-800">🔎 البحث المتقدم</h3>
            <span id="search-loading" class="hidden text-xs text-blue-600 font-bold">جارٍ البحث...</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <select name="exam_type_id" class="{{ $filterClass }}">
                <option value="">كل الأنواع</option>
                @foreach ($examTypes as $type)
                    <option value="{{ $type->id }}" {{ ($filters['exam_type_id'] ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->type_name }}
                    </option>
                @endforeach
            </select>

            <select name="course_id" class="{{ $filterClass }}">
                <option value="">كل المواد</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                        {{ $course->course_name }}
                    </option>
                @endforeach
            </select>

            <select name="section_id" class="{{ $filterClass }}">
                <option value="">كل الشعب</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" {{ ($filters['section_id'] ?? '') == $section->id ? 'selected' : '' }}>
                        {{ $section->section_name }} - {{ $section->course?->course_name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="exam_date" value="{{ $filters['exam_date'] ?? '' }}" class="{{ $filterClass }}">

            <a href="{{ route('exams.index') }}" id="reset-filters"
               class="inline-flex items-center justify-center px-4 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                مسح الفلاتر
            </a>
        </div>

        <!-- زر بديل في حال تعطيل الجافاسكربت -->
        <noscript>
            <button type="submit" class="mt-4 bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold">بحث</button>
        </noscript>
    </form>

    <!-- جدول الامتحانات (Grid View) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-6 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800">قائمة الامتحانات</h3>
                <p class="text-sm text-slate-500 mt-1">تتحدث النتائج تلقائياً عند تغيير الفلاتر.</p>
            </div>

            <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold">
                عدد السجلات: <span id="exams-count">{{ $exams->count() }}</span>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 text-sm">
                    <tr>
                        <th class="px-5 py-4 font-bold">الرقم</th>
                        <th class="px-5 py-4 font-bold">النوع</th>
                        <th class="px-5 py-4 font-bold">المادة</th>
                        <th class="px-5 py-4 font-bold">الشعبة</th>
                        <th class="px-5 py-4 font-bold">التاريخ والوقت</th>
                        <th class="px-5 py-4 font-bold">القاعة</th>
                        <th class="px-5 py-4 font-bold">الدنيا / العظمى</th>
                        <th class="px-5 py-4 font-bold">الدرجات</th>
                        <th class="px-5 py-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody id="exams-rows" class="divide-y divide-slate-100 text-sm">
                    @include('exams.partials.rows')
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                {{ $isManager ? 'بصفتك مدير القسم يمكنك حذف الامتحانات التي لا تحتوي على درجات.' : 'يتم عرض الإجراءات حسب صلاحيات موظف القسم.' }}
            </p>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    // البحث الديناميكي باستخدام Ajax دون إعادة تحميل الصفحة
    $(function () {
        const $form = $('#search-form');
        let timer = null;

        function runSearch() {
            $('#search-loading').removeClass('hidden');

            $.ajax({
                url: $form.attr('action'),
                method: 'GET',
                data: $form.serialize(),
                dataType: 'json',
            })
            .done(function (response) {
                $('#exams-rows').html(response.html);
                $('#exams-count').text(response.count);

                // تحديث الرابط ليعكس الفلاتر الحالية
                history.replaceState(null, '', $form.attr('action') + '?' + $form.serialize());
            })
            .fail(function () {
                alert('حدث خطأ أثناء البحث، يرجى المحاولة مرة أخرى.');
            })
            .always(function () {
                $('#search-loading').addClass('hidden');
            });
        }

        // تأخير بسيط لتجنب إرسال طلبات كثيرة
        $form.on('change input', 'select, input', function () {
            clearTimeout(timer);
            timer = setTimeout(runSearch, 250);
        });

        $form.on('submit', function (e) {
            e.preventDefault();
            runSearch();
        });

        $('#reset-filters').on('click', function (e) {
            e.preventDefault();
            $form[0].reset();
            $form.find('select, input').val('');
            runSearch();
        });
    });
</script>
@endpush
