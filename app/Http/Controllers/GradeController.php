<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
| متحكم الدرجات: رصد درجات الطلاب في الامتحان وتعديلها
*/

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Models\Exam;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    // صفحة رصد / تعديل درجات امتحان
    public function edit($id)
    {
        $exam = Exam::with(['examType', 'course', 'section'])->findOrFail($id);

        // طلاب الشعبة المرتبطة بالامتحان فقط
        $students = $exam->section
            ? $exam->section->students()->orderBy('full_name')->get()
            : collect();

        // الدرجات الحالية مفهرسة حسب معرف الطالب
        $currentGrades = $exam->grades()->get()->keyBy('student_id');

        return view('grades.edit', compact('exam', 'students', 'currentGrades'));
    }

    // حفظ الدرجات (إضافة جديدة أو تعديل موجودة)
    public function update(GradeRequest $request, $id)
    {
        $exam = Exam::with('section')->findOrFail($id);
        $data = $request->validated();

        // السماح فقط بطلاب مسجلين في شعبة الامتحان
        $allowedStudentIds = $exam->section
            ? $exam->section->students()->pluck('students.id')->all()
            : [];

        $saved = 0;

        DB::transaction(function () use ($data, $exam, $allowedStudentIds, &$saved) {
            foreach ($data['grades'] as $studentId => $grade) {
                // الحقل الفارغ يعني أن الدرجة لم ترصد بعد
                if ($grade === null || $grade === '') {
                    continue;
                }

                if (!in_array((int) $studentId, $allowedStudentIds, true)) {
                    continue;
                }

                // القيد UNIQUE (exam_id, student_id) يمنع التكرار، و updateOrCreate يعدل الموجود
                Grade::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => (int) $studentId],
                    ['grade' => $grade, 'recorded_date' => $data['recorded_date']]
                );

                $saved++;
            }
        });

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'تم حفظ ' . $saved . ' درجة بنجاح');
    }
}
