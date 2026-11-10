<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: غازي القوتلي  -  الرقم الجامعي: 202311542
|==========================================================================
| متحكم الامتحانات:
| - موظف القسم: إضافة، بحث متقدم (Ajax)، تعديل، عرض التفاصيل
| - مدير القسم: كل ما سبق + حذف الامتحان (بشرط عدم وجود درجات)
*/

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;

class ExamController extends Controller
{
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

    // عرض الامتحانات مع البحث المتقدم (يدعم Ajax)
    public function index(Request $request)
    {
        $filters = $request->validate([
            'exam_type_id' => 'nullable|integer|exists:exam_types,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'exam_date' => 'nullable|date',
        ]);

        // بناء الاستعلام باستخدام Eloquent (Prepared Statements تلقائياً)
        $exams = Exam::with(['examType', 'course', 'section'])
            ->withCount('grades')
            ->when($filters['exam_type_id'] ?? null, fn ($q, $v) => $q->where('exam_type_id', $v))
            ->when($filters['course_id'] ?? null, fn ($q, $v) => $q->where('course_id', $v))
            ->when($filters['section_id'] ?? null, fn ($q, $v) => $q->where('section_id', $v))
            ->when($filters['exam_date'] ?? null, fn ($q, $v) => $q->whereDate('exam_date', $v))
            ->orderByDesc('exam_date')
            ->get();

        $isManager = self::isManager();

        // طلب Ajax: إرجاع صفوف الجدول فقط دون إعادة تحميل الصفحة
        if ($request->ajax()) {
            return response()->json([
                'html' => view('exams.partials.rows', compact('exams', 'isManager'))->render(),
                'count' => $exams->count(),
            ]);
        }

        return view('exams.index', array_merge(
            compact('exams', 'isManager', 'filters'),
            $this->formLists()
        ));
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

        // إحصائيات الامتحان
        $stats = [
            'students' => $exam->section?->students->count() ?? 0,
            'recorded' => $grades->count(),
            'average' => $grades->count() ? round($grades->avg('grade'), 2) : null,
            'highest' => $grades->max('grade'),
            'lowest' => $grades->min('grade'),
            'passed' => $grades->filter(fn ($g) => (float) $g->grade >= (float) $exam->min_grade)->count(),
        ];

        $isManager = self::isManager();

        return view('exams.show', compact('exam', 'grades', 'stats', 'isManager'));
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

    // القوائم المنسدلة المشتركة بين صفحات الإضافة والتعديل والبحث (DRY)
    private function formLists(): array
    {
        return [
            'examTypes' => ExamType::orderBy('type_name')->get(),
            'courses' => Course::orderBy('course_name')->get(),
            'sections' => Section::with('course')->orderBy('section_name')->get(),
        ];
    }
}
