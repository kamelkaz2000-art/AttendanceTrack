{{-- الكتلة الخامسة: رسائل النجاح والأخطاء المشتركة بين صفحات الامتحانات --}}

@if (session('success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4">
        <span class="text-xl">✓</span>
        <p class="font-semibold text-sm">{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
        <p class="font-bold mb-2">يرجى تصحيح الأخطاء التالية:</p>

        @foreach ($errors->all() as $error)
            <p class="text-sm mb-1">{{ $error }}</p>
        @endforeach
    </div>
@endif
