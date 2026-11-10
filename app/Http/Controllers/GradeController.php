<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: ..........  -  الرقم الجامعي: ..........
|==========================================================================
| متحكم الدرجات:
| - موظف القسم: رصد درجات الطلاب في الامتحان وتعديلها
| - مدير القسم: إصدار كشف الدرجات النهائي (حسب الشعبة ولكل طالب)
*/

namespace App\Http\Controllers;

use App\Http\Requests\GradeRequest;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
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

    // إصدار النتائج: كشف درجات نهائي لطلاب شعبة (مدير القسم فقط)
    public function results(Request $request)
    {
        $this->authorizeManager();

        $sections = Section::with('course')->orderBy('section_name')->get();

        $request->validate([
            'section_id' => 'nullable|integer|exists:sections,id',
        ]);

        $section = null;
        $exams = collect();
        $rows = collect();

        if ($request->filled('section_id')) {
            $section = Section::with(['course', 'students'])->findOrFail($request->section_id);

            $exams = Exam::with('examType')
                ->where('section_id', $section->id)
                ->orderBy('exam_date')
                ->get();

            $grades = Grade::whereIn('exam_id', $exams->pluck('id'))->get()
                ->groupBy('student_id');

            // حساب المجموع والنسبة والنتيجة لكل طالب
            $rows = $section->students->sortBy('full_name')->map(function ($student) use ($exams, $grades) {
                return $this->buildResult($student, $exams, $grades->get($student->id, collect()));
            })->values();
        }

        return view('grades.results', compact('sections', 'section', 'exams', 'rows'));
    }

    // كشف الدرجات النهائي لطالب واحد في جميع المواد (مدير القسم فقط)
    public function studentReport($studentId)
    {
        $this->authorizeManager();

        $student = Student::with('sections.course')->findOrFail($studentId);

        $courses = $student->sections->map(function ($section) use ($student) {
            $exams = Exam::with('examType')
                ->where('section_id', $section->id)
                ->orderBy('exam_date')
                ->get();

            $grades = Grade::where('student_id', $student->id)
                ->whereIn('exam_id', $exams->pluck('id'))
                ->get();

            return [
                'section' => $section,
                'exams' => $exams,
                'grades' => $grades->keyBy('exam_id'),
                'result' => $this->buildResult($student, $exams, $grades),
            ];
        });

        return view('grades.student-report', compact('student', 'courses'));
    }

    // حساب نتيجة الطالب: المجموع، النسبة، ناجح/راسب/غير مكتمل
    private function buildResult($student, $exams, $studentGrades): array
    {
        $gradesByExam = $studentGrades->keyBy('exam_id');

        $total = (float) $studentGrades->sum('grade');
        $maxTotal = (float) $exams->sum('max_grade');
        $minTotal = (float) $exams->sum('min_grade');
        $complete = $exams->isNotEmpty() && $gradesByExam->count() === $exams->count();

        if (!$complete) {
            $status = 'incomplete';
        } else {
            $status = $total >= $minTotal ? 'passed' : 'failed';
        }

        return [
            'student' => $student,
            'grades' => $gradesByExam,
            'total' => $total,
            'max_total' => $maxTotal,
            'percentage' => $maxTotal > 0 ? round($total / $maxTotal * 100, 1) : null,
            'status' => $status,
        ];
    }

    // التحقق من صلاحية المدير على مستوى الـ Controller
    private function authorizeManager(): void
    {
        if (!ExamController::isManager()) {
            abort(403, 'ليس لديك صلاحية إصدار النتائج');
        }
    }
}
