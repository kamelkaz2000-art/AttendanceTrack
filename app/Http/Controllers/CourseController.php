<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    /**
     * عرض قائمة جميع المقررات
     */
    public function index()
    {
        $courses = Course::with(['department', 'instructor', 'semester', 'prerequisite'])->latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * عرض صفحة إنشاء مقرر جديد (متاح لمدير وموظف القسم)
     */
    public function create()
    {
        $departments = Department::all();
        $instructors = $this->instructors();
        $semesters = Semester::all();
        $courses = Course::all(); // للمتطلب السابق (المادة السابقة)

        return view('courses.create', compact('departments', 'instructors', 'semesters', 'courses'));
    }

    /**
     * حفظ مقرر جديد في قاعدة البيانات مع قواعد التحقق المطلوبة
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_name'    => 'required|string|min:3|max:100', // إجباري، بين 3 و 100 حرف
            'course_code'    => ['required', 'string', 'max:20', 'regex:/^[A-Za-z]{2,4}[0-9]{2,4}$/', 'unique:courses,course_code'], // إجباري، فريد
            'credit_hours'   => 'required|integer|min:1|max:6', // إجباري، بين 1 و 6 ساعات
            'department_id'  => 'required|exists:departments,id', // إجباري من القائمة
            'instructor_id'  => 'required|exists:users,id', // إجباري من القائمة
            'semester_id'    => 'required|exists:semesters,id', // إجباري من القائمة
            'academic_year'  => 'required|string|max:20', // إجباري
            'description'    => 'nullable|string|max:500', // اختياري، أقل من 500 حرف
            'prerequisite_id'=> 'nullable|exists:courses,id', // اختياري
            'status'         => 'nullable|in:active,inactive',
        ]);

        Course::create($validatedData);

        return redirect()->route('courses.index')
                         ->with('success', 'تم إضافة المقرر بنجاح.');
    }

    /**
     * عرض تفاصيل مقرر محدد
     */
    public function show($id)
    {
        $course = Course::with(['department', 'instructor', 'semester', 'prerequisite'])->findOrFail($id);
        return view('courses.show', compact('course'));
    }

    /**
     * عرض صفحة تعديل مقرر
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $departments = Department::all();
        $instructors = $this->instructors();
        $semesters = Semester::all();
        $courses = Course::where('id', '!=', $id)->get();

        return view('courses.edit', compact('course', 'departments', 'instructors', 'semesters', 'courses'));
    }

    /**
     * تحديث بيانات المقرر
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validatedData = $request->validate([
            'course_name'    => 'required|string|min:3|max:100',
            'course_code'    => ['required', 'string', 'max:20', 'regex:/^[A-Za-z]{2,4}[0-9]{2,4}$/', 'unique:courses,course_code,' . $id],
            'credit_hours'   => 'required|integer|min:1|max:6',
            'department_id'  => 'required|exists:departments,id',
            'instructor_id'  => 'required|exists:users,id',
            'semester_id'    => 'required|exists:semesters,id',
            'academic_year'  => 'required|string|max:20',
            'description'    => 'nullable|string|max:500',
            'prerequisite_id'=> 'nullable|exists:courses,id',
            'status'         => 'nullable|in:active,inactive',
        ]);

        $course->update($validatedData);

        return redirect()->route('courses.index')
                         ->with('success', 'تم تعديل بيانات المقرر بنجاح.');
    }

    /**
     * تغيير حالة المقرر (مفعلة / معطلة) - حصرياً لمدير القسم فقط
     */
    public function changeStatus($id)
    {
        $course = Course::findOrFail($id);
        $course->status = ($course->status == 'active') ? 'inactive' : 'active';
        $course->save();

        return redirect()->route('courses.index')->with('success', 'تم تغيير حالة المقرر بنجاح.');
    }

    /**
     * حذف مقرر من قاعدة البيانات - حصرياً لمدير القسم فقط (مع التحقق من قيد النزاهة)
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // قيد النزاهة: التحقق من عدم حذف مادة مرتبطة بشعبة دراسية مسجلة
        // (جدول الشعب يأتي من الكتلة الثالثة، لذلك نتحقق من وجوده أولاً)
        if (class_exists(\App\Models\Section::class) && Schema::hasTable('sections')
            && $course->sections()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المادة لارتباطها بشعب دراسية مسجلة.');
        }

        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'تم حذف المقرر بنجاح.');
    }

    /**
     * قائمة المدرسين المسؤولين: جميع المستخدمين النشطين عدا الطلاب
     */
    private function instructors()
    {
        return User::where('status', 'active')
            ->whereHas('role', fn ($q) => $q->where('role_name', '!=', 'student'))
            ->orderBy('full_name')
            ->get();
    }
}
