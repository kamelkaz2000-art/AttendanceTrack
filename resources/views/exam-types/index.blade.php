{{-- الكتلة الخامسة: إدارة أنواع الامتحانات (مدير القسم) --}}

@extends('layouts.app')

@section('title', 'أنواع الامتحانات - AttendanceTrack')

@section('content')

    @php
        $inputClass = 'px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100';
    @endphp

    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">الامتحانات والدرجات</p>
        <h2 class="text-3xl font-extrabold text-slate-800">أنواع الامتحانات</h2>
        <p class="text-slate-500 mt-2">إضافة وتعديل وحذف أنواع الامتحانات (مثل: نصفي، نهائي، عملي، شفهي).</p>
    </div>

    @include('exams.partials.alerts')

    <!-- إضافة نوع -->
    <form action="{{ route('exam-types.store') }}" method="POST"
          class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6 flex flex-col sm:flex-row gap-4 sm:items-end">
        @csrf

        <div class="flex-1">
            <label for="type_name" class="block text-sm font-bold text-slate-700 mb-2">
                نوع جديد <span class="text-red-500">*</span>
            </label>
            <input type="text" id="type_name" name="type_name" value="{{ old('type_name') }}"
                   required minlength="2" maxlength="50" placeholder="مثال: مذاكرة"
                   class="w-full {{ $inputClass }}">
        </div>

        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
            إضافة النوع
        </button>
    </form>

    <!-- قائمة الأنواع -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right whitespace-nowrap text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-bold">الرقم</th>
                        <th class="px-5 py-4 font-bold">اسم النوع</th>
                        <th class="px-5 py-4 font-bold">عدد الامتحانات</th>
                        <th class="px-5 py-4 font-bold text-center">الإجراءات</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($examTypes as $type)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4 text-slate-500">{{ $type->id }}</td>

                            <td class="px-5 py-3">
                                <form action="{{ route('exam-types.update', $type->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="type_name" value="{{ $type->type_name }}"
                                           required minlength="2" maxlength="50"
                                           class="w-48 {{ $inputClass }} py-2">
                                    <button type="submit" class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                        حفظ
                                    </button>
                                </form>
                            </td>

                            <td class="px-5 py-4 text-slate-600">{{ $type->exams_count }}</td>

                            <td class="px-5 py-4 text-center">
                                @if ($type->exams_count == 0)
                                    <form action="{{ route('exam-types.destroy', $type->id) }}" method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف نوع الامتحان؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-bold transition">
                                            حذف
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">مرتبط بامتحانات</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center text-slate-400">لا توجد أنواع امتحانات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
