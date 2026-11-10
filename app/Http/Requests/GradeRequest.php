<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: ..........  -  الرقم الجامعي: ..........
|==========================================================================
| التحقق من درجات الطلاب عند الرصد أو التعديل
*/

namespace App\Http\Requests;

use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $exam = Exam::findOrFail($this->route('id'));
        $maxGrade = (float) $exam->max_grade;

        return [
            'recorded_date' => 'required|date|before_or_equal:today',
            'grades' => 'required|array',
            // الدرجة اختيارية لكل طالب (الحقل الفارغ = لم ترصد بعد)، وإن وجدت فهي بين 0 والدرجة العظمى
            'grades.*' => 'nullable|numeric|min:0|max:' . $maxGrade,
        ];
    }

    public function messages(): array
    {
        return [
            'recorded_date.required' => 'تاريخ رصد الدرجات مطلوب.',
            'recorded_date.date' => 'تاريخ رصد الدرجات غير صحيح.',
            'recorded_date.before_or_equal' => 'تاريخ الرصد لا يمكن أن يكون في المستقبل.',
            'grades.required' => 'لا توجد درجات لحفظها.',
            'grades.array' => 'صيغة الدرجات غير صحيحة.',
            'grades.*.numeric' => 'يجب أن تكون الدرجة رقماً.',
            'grades.*.min' => 'الدرجة لا يمكن أن تقل عن الصفر.',
            'grades.*.max' => 'الدرجة لا يمكن أن تتجاوز الدرجة العظمى (:max).',
        ];
    }
}
