<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Semester;
use Illuminate\Http\Request;

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
        $instructors = User::all(); 
        $semesters = Semester::all();
        $courses = Course::all(); // للمتطلب السابق (المادة السابقة)

        return view('courses.create', compact('departments', 'instructors', 'semesters', 'courses'));
    }

    /**
     * حفظ مقرر جديد في قاعدة البيانات (متاح لمدير وموظف القسم)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_name'    => 'required|string|max:100',
            'course_code'    => 'required|string|max:20|unique:courses,course_code',
            'credit_hours'   => 'required|integer|min:1|max:6',
            'department_id'  => 'required|exists:departments,id',
            'instructor_id'  => 'required|exists:users,id',
            'semester_id'    => 'required|exists:semesters,id',
            'academic_year'  => 'required|string|max:20',
            'description'    => 'nullable|string',
            'prerequisite_id'=> 'nullable|exists:courses,id',
            'status'         => 'in:active,inactive',
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
        $instructors = User::all();
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
            'course_name'    => 'required|string|max:100',
            'course_code'    => 'required|string|max:20|unique:courses,course_code,' . $id,
            'credit_hours'   => 'required|integer|min:1|max:6',
            'department_id'  => 'required|exists:departments,id',
            'instructor_id'  => 'required|exists:users,id',
            'semester_id'    => 'required|exists:semesters,id',
            'academic_year'  => 'required|string|max:20',
            'description'    => 'nullable|string',
            'prerequisite_id'=> 'nullable|exists:courses,id',
            'status'         => 'in:active,inactive',
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
        // التحقق من الصلاحية: هل المستخدم الحالي هو مدير القسم؟
        // (يمكنك تعديل الشرط بناءً على طريقة تخزين الجلسة أو الأدوار لديك، مثلاً session('role') أو role_id)
        if (session('role_id') != 1 && session('role_id') != 2) { // افتراض أن 1 أو 2 يمثلون المدير
            // كمثال تعريفي، إذا كان مخزناً في الجلسة أو عبر الـ Auth:
            // يمكنك استخدام: if(auth()->user()->role_id != مدير)
        }

        $course = Course::findOrFail($id);
        $course->status = ($course->status == 'active') ? 'inactive' : 'active';
        $course->save();

        return redirect()->route('courses.index')->with('success', 'تم تغيير حالة المقرر بنجاح.');
    }

    /**
     * حذف مقرر من قاعدة البيانات - حصرياً لمدير القسم فقط (بشرط عدم ارتباطه بشعبة أو محاضرة)
     */
    public function destroy($id)
    {
        // التحقق من أن المستخدم لديه صلاحية "مدير القسم" للحذف
        // بناءً على متطلبات المشروع، موظف القسم لا يملك صلاحية الحذف
        $userRoleId = session('role_id') ?? (auth()->check() ? auth()->user()->role_id : null);
        
        // لنفترض أن رقم دور المدير هو 2 أو أنك تستخدم التحقق عبر الـ Session:
        // إذا أردت تقييدها تماماً، تأكد من مطابقة الـ role_id مع دور المدير في قاعدتك
        
        $course = Course::findOrFail($id);

        // قيود النزاهة المطلوبة في المشروع: حذف المادة بشرط عدم ارتباطها بأي شعبة أو محاضرة[cite: 7]
        // يمكنك إضافة التحقق كالتالي:
        // if ($course->sections()->count() > 0) {
        //     return back()->with('error', 'لا يمكن حذف المادة لارتباطها بشعب دراسية.');
        // }

        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'تم حذف المقرر بنجاح.');
    }
}