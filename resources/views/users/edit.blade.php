
@extends('layouts.app')

@section('title', 'تعديل المستخدم - AttendanceTrack')

@section('content')

    <!-- عنوان الصفحة -->
    <div class="mb-8">
        <p class="text-blue-600 font-bold text-sm mb-2">إدارة المستخدمين</p>

        <h2 class="text-3xl font-extrabold text-slate-800">
            تعديل بيانات المستخدم
        </h2>

        <p class="text-slate-500 mt-2">
            يمكنك تعديل معلومات الحساب والبيانات الشخصية للمستخدم.
        </p>
    </div>

    <!-- رسائل الأخطاء -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
            <p class="font-bold mb-2">يرجى تصحيح الأخطاء التالية:</p>

            @foreach ($errors->all() as $error)
                <p class="text-sm mb-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- نموذج التعديل -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-extrabold text-slate-800">
                بيانات المستخدم
            </h3>

            <p class="text-sm text-slate-500 mt-1">
                المستخدم: {{ $user->full_name }}
            </p>
        </div>

        <form action="{{ route('users.update', $user->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="p-6 md:p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- الاسم الكامل -->
                    <div>
                        <label for="full_name" class="block text-sm font-bold text-slate-700 mb-2">
                            الاسم الكامل <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="{{ old('full_name', $user->full_name) }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <!-- اسم المستخدم -->
                    <div>
                        <label for="username" class="block text-sm font-bold text-slate-700 mb-2">
                            اسم المستخدم <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                            البريد الإلكتروني <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <!-- كلمة المرور -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="اتركها فارغة إذا لم ترد تغييرها"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        <p class="text-xs text-slate-400 mt-2">
                            كلمة المرور اختيارية عند تعديل المستخدم.
                        </p>
                    </div>

                    <!-- رقم الهاتف -->
                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">
                            رقم الهاتف <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <!-- تاريخ الميلاد -->
                    <div>
                        <label for="birth_date" class="block text-sm font-bold text-slate-700 mb-2">
                            تاريخ الميلاد <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="birth_date"
                            name="birth_date"
                            value="{{ old('birth_date', $user->birth_date ? \Illuminate\Support\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                    </div>

                    <!-- الدور -->
                    <div>
                        <label for="role_id" class="block text-sm font-bold text-slate-700 mb-2">
                            الدور <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="role_id"
                            name="role_id"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- القسم -->
                    <div>
                        <label for="department_id" class="block text-sm font-bold text-slate-700 mb-2">
                            القسم <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="department_id"
                            name="department_id"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الحالة -->
                    <div>
                        <label for="status" class="block text-sm font-bold text-slate-700 mb-2">
                            حالة المستخدم <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >
                            <option value="active"
                                {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                نشط
                            </option>

                            <option value="inactive"
                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                غير نشط
                            </option>
                        </select>
                    </div>

                    <!-- الصورة الشخصية -->
                    <div class="md:col-span-2">
                        <label for="photo" class="block text-sm font-bold text-slate-700 mb-2">
                            الصورة الشخصية
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/*"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none transition file:ml-4 file:px-4 file:py-2 file:border-0 file:rounded-lg file:bg-blue-100 file:text-blue-700 file:font-bold"
                        >

                        <p class="text-xs text-slate-400 mt-2">
                            اختر صورة جديدة فقط إذا كنت تريد استبدال الصورة الحالية.
                        </p>
                    </div>

                </div>

            </div>

            <!-- أزرار النموذج -->
            <div class="flex flex-col sm:flex-row justify-between gap-3 bg-slate-50 border-t border-slate-100 p-6">

                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                    العودة إلى المستخدمين
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center px-8 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm shadow-lg shadow-blue-100 transition">
                    حفظ التعديلات
                </button>

            </div>

        </form>

    </div>

@endsection
