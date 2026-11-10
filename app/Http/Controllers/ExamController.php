<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
| متحكم الامتحانات:
| - موظف القسم: إضافة، عرض الكل، تعديل، عرض التفاصيل
| - مدير القسم: كل ما سبق + حذف الامتحان
*/

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Section;

class ExamController extends Controller
{
    /*
    // الوظيفة الأولى (Assignment 1) - بيانات وهمية داخل الكنترولر بدون قاعدة بيانات

    private $exams = [
        ['id' => 1, 'exam_type' => 'نصفي', 'exam_date' => '2026-11-05', 'max_grade' => 30, 'section_id' => 1],
        ['id' => 2, 'exam_type' => 'نهائي', 'exam_date' => '2027-01-15', 'max_grade' => 70, 'section_id' => 1],
        ['id' => 3, 'exam_type' => 'عملي', 'exam_date' => '2026-12-01', 'max_grade' => 20, 'section_id' => 2],
        ['id' => 4, 'exam_type' => 'شفهي', 'exam_date' => '2026-12-10', 'max_grade' => 10, 'section_id' => 2],
        ['id' => 5, 'exam_type' => 'نصفي', 'exam_date' => '2026-11-12', 'max_grade' => 30, 'section_id' => 3],
    ];

    private $grades = [
        ['id' => 1, 'exam_id' => 1, 'student_id' => 1, 'grade' => 27],
        ['id' => 2, 'exam_id' => 1, 'student_id' => 2, 'grade' => 18.5],
        ['id' => 3, 'exam_id' => 1, 'student_id' => 3, 'grade' => 12],
        ['id' => 4, 'exam_id' => 3, 'student_id' => 4, 'grade' => 17],
        ['id' => 5, 'exam_id' => 5, 'student_id' => 5, 'grade' => 25],
    ];

    public function index()
    {
        $exams = $this->exams;

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        return view('exams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_type' => 'required',
            'exam_date' => 'required|date',
            'max_grade' => 'required|numeric|min:10|max:100',
            'section_id' => 'required|integer',
        ]);

        return redirect()->route('exams.index');
    }

    public function show($id)
    {
        $exam = collect($this->exams)->firstWhere('id', $id);
        $grades = collect($this->grades)->where('exam_id', $id);

        return view('exams.show', compact('exam', 'grades'));
    }

    public function edit($id)
    {
        $exam = collect($this->exams)->firstWhere('id', $id);

        return view('exams.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_type' => 'required',
            'exam_date' => 'required|date',
            'max_grade' => 'required|numeric|min:10|max:100',
            'section_id' => 'required|integer',
        ]);

        return redirect()->route('exams.index');
    }

    public function destroy($id)
    {
        return redirect()->route('exams.index');
    }
    */

    // الوظيفة الثانية (Assignment 2) - العمل مع قاعدة البيانات

    // هل المستخدم الحالي مدير (مدير النظام أو مدير القسم)؟
    public static function isManager(): bool
    {
        return in_array(auth()->user()->role?->role_name, ['system_admin', 'department_manager'], true);
    }

    // الصفحة الرئيسية للكتلة الخامسة (لوحة التحكم حسب الدور)
    public function home()
    {
        $stats = [
            'exams' => Exam::count(),
            'upcoming' => Exam::whereDate('exam_date', '>=', today())->count(),
            'grades' => Grade::count(),
            'types' => ExamType::count(),
        ];

        $upcomingExams = Exam::with(['examType', 'course', 'section'])
            ->whereDate('exam_date', '>=', today())
            ->orderBy('exam_date')
            ->limit(5)
            ->get();

        $isManager = self::isManager();

        return view('exams.home', compact('stats', 'upcomingExams', 'isManager'));
    }

    // عرض جميع الامتحانات
    public function index()
    {
        $exams = Exam::with(['examType', 'course', 'section'])
            ->withCount('grades')
            ->orderByDesc('exam_date')
            ->get();

        $isManager = self::isManager();

        return view('exams.index', compact('exams', 'isManager'));
    }

    // صفحة إضافة امتحان
    public function create()
    {
        return view('exams.create', $this->formLists());
    }

    // حفظ امتحان جديد
    public function store(ExamRequest $request)
    {
        $exam = Exam::create($request->validated());

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'تمت إضافة الامتحان بنجاح');
    }

    // عرض تفاصيل الامتحان مع درجات الطلاب
    public function show($id)
    {
        $exam = Exam::with(['examType', 'course', 'section.students', 'grades.student'])
            ->findOrFail($id);

        $grades = $exam->grades->sortBy(fn ($g) => $g->student?->full_name);

        $isManager = self::isManager();

        return view('exams.show', compact('exam', 'grades', 'isManager'));
    }

    // صفحة تعديل الامتحان
    public function edit($id)
    {
        $exam = Exam::findOrFail($id);

        return view('exams.edit', array_merge(compact('exam'), $this->formLists()));
    }

    // حفظ تعديلات الامتحان
    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $exam->update($request->validated());

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'تم تعديل بيانات الامتحان بنجاح');
    }

    // حذف امتحان: مدير القسم فقط وبشرط عدم وجود درجات مسجلة
    public function destroy($id)
    {
        // التحقق من الصلاحية على مستوى الـ Controller وليس الواجهة فقط
        if (!self::isManager()) {
            abort(403, 'ليس لديك صلاحية حذف الامتحانات');
        }

        $exam = Exam::findOrFail($id);

        if ($exam->hasGrades()) {
            return redirect()->back()
                ->withErrors(['exam' => 'لا يمكن حذف الامتحان لوجود درجات مسجلة مرتبطة به.']);
        }

        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'تم حذف الامتحان بنجاح');
    }

    // القوائم المنسدلة المشتركة بين صفحتي الإضافة والتعديل
    private function formLists(): array
    {
        return [
            'examTypes' => ExamType::orderBy('type_name')->get(),
            'courses' => Course::orderBy('course_name')->get(),
            'sections' => Section::with('course')->orderBy('section_name')->get(),
        ];
    }
}
