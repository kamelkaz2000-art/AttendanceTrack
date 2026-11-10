<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
| التحقق من بيانات الامتحان على مستوى الخادم (Laravel Validation Request)
*/

namespace App\Http\Requests;

use App\Models\Grade;
use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ExamRequest extends FormRequest
{
    // الصلاحيات يتم التحقق منها عبر Middleware في المسارات
    public function authorize(): bool
    {
        return true;
    }

    // تنظيف المدخلات النصية قبل التحقق (منع XSS)
    protected function prepareForValidation(): void
    {
        $this->merge([
            'room' => trim(strip_tags((string) $this->input('room'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'exam_type_id' => 'required|integer|exists:exam_types,id',
            'exam_date' => 'required|date|after_or_equal:2000-01-01|before_or_equal:2100-12-31',
            'exam_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1|max:600',
            'max_grade' => 'required|numeric|min:10|max:100',
            'min_grade' => 'required|numeric|min:0|lt:max_grade',
            'course_id' => 'required|integer|exists:courses,id',
            'section_id' => 'required|integer|exists:sections,id',
            'room' => 'required|string|min:2|max:50',
        ];
    }

    // تحققات إضافية تعتمد على العلاقات بين الجداول
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // الشعبة يجب أن تتبع المادة المختارة
                $section = Section::find($this->input('section_id'));

                if ($section && (int) $section->course_id !== (int) $this->input('course_id')) {
                    $validator->errors()->add('section_id', 'الشعبة المختارة لا تتبع المادة المحددة.');
                }

                // عند التعديل: لا يمكن جعل الدرجة العظمى أقل من أعلى درجة مرصودة
                $examId = $this->route('id');

                if ($examId) {
                    $highestGrade = Grade::where('exam_id', $examId)->max('grade');

                    if ($highestGrade !== null && (float) $this->input('max_grade') < (float) $highestGrade) {
                        $validator->errors()->add(
                            'max_grade',
                            'لا يمكن أن تكون الدرجة العظمى أقل من أعلى درجة مرصودة (' . (float) $highestGrade . ').'
                        );
                    }
                }
            },
        ];
    }

    // رسائل الخطأ باللغة العربية
    public function messages(): array
    {
        return [
            'exam_type_id.required' => 'يرجى اختيار نوع الامتحان.',
            'exam_type_id.exists' => 'نوع الامتحان المختار غير موجود.',
            'exam_date.required' => 'تاريخ الامتحان مطلوب.',
            'exam_date.date' => 'تاريخ الامتحان غير صحيح.',
            'exam_date.after_or_equal' => 'تاريخ الامتحان غير منطقي.',
            'exam_date.before_or_equal' => 'تاريخ الامتحان غير منطقي.',
            'exam_time.required' => 'وقت الامتحان مطلوب.',
            'exam_time.date_format' => 'صيغة وقت الامتحان غير صحيحة (مثال 10:30).',
            'duration.required' => 'مدة الامتحان مطلوبة.',
            'duration.integer' => 'مدة الامتحان يجب أن تكون عدداً صحيحاً بالدقائق.',
            'duration.min' => 'مدة الامتحان يجب أن تكون رقماً موجباً.',
            'duration.max' => 'مدة الامتحان لا يمكن أن تتجاوز 600 دقيقة.',
            'max_grade.required' => 'الدرجة العظمى مطلوبة.',
            'max_grade.numeric' => 'الدرجة العظمى يجب أن تكون رقماً.',
            'max_grade.min' => 'الدرجة العظمى يجب أن تكون بين 10 و 100.',
            'max_grade.max' => 'الدرجة العظمى يجب أن تكون بين 10 و 100.',
            'min_grade.required' => 'الدرجة الدنيا مطلوبة.',
            'min_grade.numeric' => 'الدرجة الدنيا يجب أن تكون رقماً.',
            'min_grade.min' => 'الدرجة الدنيا لا يمكن أن تكون سالبة.',
            'min_grade.lt' => 'الدرجة الدنيا يجب أن تكون أقل من الدرجة العظمى.',
            'course_id.required' => 'يرجى اختيار المادة.',
            'course_id.exists' => 'المادة المختارة غير موجودة.',
            'section_id.required' => 'يرجى اختيار الشعبة.',
            'section_id.exists' => 'الشعبة المختارة غير موجودة.',
            'room.required' => 'القاعة مطلوبة.',
            'room.min' => 'اسم القاعة قصير جداً.',
            'room.max' => 'اسم القاعة يجب ألا يتجاوز 50 حرفاً.',
        ];
    }
}
