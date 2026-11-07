<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| المتحكم (Controller) الخاص بالمواد الدراسية
| يستقبل الطلبات من المسارات، يتعامل مع النموذج Course، ثم يرسل البيانات إلى الواجهات
|
| الصلاحيات داخل الكتلة:
|  - مدير قسم المواد (department_manager): إضافة، تعديل، حذف، تغيير الحالة، عرض التفاصيل
|  - موظف قسم المواد (department_employee): إضافة، تعديل، عرض التفاصيل (بدون حذف أو تغيير حالة)
|  - مدير النظام (system_admin): جميع الصلاحيات
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;

class CourseController extends Controller
{
    /*
    |======================================================================
    | الوظيفة الأولى (Assignment 1) - موضوعة كتعليق بدون حذف
    |======================================================================
    | المطلوب: CourseController يحتوي على دوال CRUD
    | (index, create, store, show, edit, update, destroy)
    | مع مصفوفة بيانات وهمية تحتوي على 5 مواد دراسية (بدون قاعدة بيانات)
    |----------------------------------------------------------------------

    // بيانات وهمية مؤقتة: مصفوفة تحتوي على 5 مواد دراسية
    private $courses = [
        [
            'id' => 1,
            'course_name' => 'مدخل إلى البرمجة',
            'course_code' => 'CS101',
            'credit_hours' => 3,
        ],
        [
            'id' => 2,
            'course_name' => 'البرمجة المتقدمة',
            'course_code' => 'CS102',
            'credit_hours' => 3,
        ],
        [
            'id' => 3,
            'course_name' => 'قواعد البيانات',
            'course_code' => 'DB201',
            'credit_hours' => 4,
        ],
        [
            'id' => 4,
            'course_name' => 'برمجة الويب 2',
            'course_code' => 'WP202',
            'credit_hours' => 3,
        ],
        [
            'id' => 5,
            'course_name' => 'شبكات الحاسوب',
            'course_code' => 'NT301',
            'credit_hours' => 3,
        ],
    ];

    // عرض قائمة المواد
    public function index()
    {
        $courses = $this->courses;
        $title = 'قائمة المواد';

        return view('courses.index', compact('courses', 'title'));
    }

    // عرض نموذج إضافة مادة جديدة
    public function create()
    {
        $title = 'إضافة مادة جديدة';

        return view('courses.create', compact('title'));
    }

    // حفظ مادة جديدة (نتحقق من البيانات فقط لأنه لا توجد قاعدة بيانات بعد)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|min:3|max:100',
            'course_code' => 'required|string|max:20',
            'credit_hours' => 'required|integer|between:1,6',
        ]);

        // هنا يتم حفظ البيانات في قاعدة البيانات (في الوظيفة الثانية)
        // Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'تمت إضافة المادة بنجاح');
    }

    // عرض تفاصيل مادة محددة
    public function show($id)
    {
        $course = collect($this->courses)->firstWhere('id', $id);

        if (!$course) {
            abort(404, 'المادة غير موجودة');
        }

        $title = 'تفاصيل المادة: ' . $course['course_name'];

        return view('courses.show', compact('course', 'title'));
    }

    // عرض نموذج تعديل مادة
    public function edit($id)
    {
        $course = collect($this->courses)->firstWhere('id', $id);

        if (!$course) {
            abort(404, 'المادة غير موجودة');
        }

        $title = 'تعديل المادة: ' . $course['course_name'];

        return view('courses.edit', compact('course', 'title'));
    }

    // تحديث بيانات مادة
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|min:3|max:100',
            'course_code' => 'required|string|max:20',
            'credit_hours' => 'required|integer|between:1,6',
        ]);

        // هنا يتم تحديث البيانات في قاعدة البيانات (في الوظيفة الثانية)
        // Course::find($id)->update($validated);

        return redirect()->route('courses.show', $id)
            ->with('success', 'تم تحديث بيانات المادة بنجاح');
    }

    // حذف مادة (للمدير فقط - المسار محمي بـ role:manager)
    public function destroy($id)
    {
        // هنا يتم حذف المادة من قاعدة البيانات (في الوظيفة الثانية)
        // Course::destroy($id);

        return redirect()->route('courses.index')
            ->with('success', 'تم حذف المادة بنجاح');
    }

    // تغيير حالة المادة مفعلة / معطلة (للمدير فقط - المسار محمي بـ role:manager)
    public function toggleStatus($id)
    {
        return redirect()->route('courses.index')
            ->with('success', 'تم تغيير حالة المادة');
    }

    |======================================================================
    | نهاية الوظيفة الأولى
    |======================================================================
    */


    /*
    |======================================================================
    | الوظيفة الثانية (Assignment 2) - الكود الفعّال الذي يعمل مع قاعدة البيانات
    |======================================================================
    */

    // أسماء أقسام الكتل البرمجية (أقسام إدارية للمستخدمين وليست أقساماً أكاديمية)
    // لا نعرضها في قائمة "القسم" عند إضافة مادة، لأن المادة تتبع لقسم أكاديمي
    private $blockDepartments = [
        'قسم المستخدمين',
        'قسم المواد',
        'قسم الطلاب',
        'قسم الحضور',
        'قسم الامتحانات',
    ];

    // اسم القسم الذي ينتمي له المدرسون (قسم الحضور = المدرس حسب ملف المشروع)
    private $instructorsDepartment = 'قسم الحضور';

    /**
     * الصفحة الرئيسية للكتلة الثانية مع لوحة التحكم
     * تعرض إحصائيات المواد وأزرار العمليات حسب دور المستخدم
     */
    public function home()
    {
        // إحصائيات لوحة التحكم
        $totalCourses = Course::count();                   // عدد كل المواد
        $activeCourses = Course::active()->count();        // عدد المواد المفعلة (Scope)
        $inactiveCourses = Course::inactive()->count();    // عدد المواد المعطلة (Scope)
        $totalHours = Course::sum('credit_hours');         // مجموع الساعات المعتمدة

        // آخر 5 مواد مضافة مع القسم والمدرس (with لتجنب مشكلة N+1)
        $latestCourses = Course::with(['department', 'instructor'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // هل المستخدم الحالي مدير (يستطيع الحذف وتغيير الحالة)؟
        $canManage = $this->canManage();

        $title = 'الصفحة الرئيسية - إدارة المواد';

        return view('courses.home', compact(
            'totalCourses',
            'activeCourses',
            'inactiveCourses',
            'totalHours',
            'latestCourses',
            'canManage',
            'title'
        ));
    }

    /**
     * عرض قائمة جميع المواد
     */
    public function index()
    {
        // جلب كل المواد مع القسم والمدرس والفصل الدراسي لكل مادة
        $courses = Course::with(['department', 'instructor', 'semester'])
            ->orderBy('id')
            ->get();

        $canManage = $this->canManage();
        $title = 'قائمة المواد';

        return view('courses.index', compact('courses', 'canManage', 'title'));
    }

    /**
     * عرض نموذج إضافة مادة جديدة
     */
    public function create()
    {
        // القوائم المنسدلة التي يحتاجها النموذج (الأقسام، المدرسين، الفصول، المتطلبات السابقة)
        $lists = $this->formLists();
        $title = 'إضافة مادة جديدة';

        return view('courses.create', array_merge($lists, compact('title')));
    }

    /**
     * حفظ مادة جديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        // التحقق من البيانات على مستوى الخادم (Laravel Validation)
        // إذا فشل التحقق يرجع المستخدم للنموذج مع رسائل الخطأ والقيم القديمة تلقائياً
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages(),
            $this->validationAttributes()
        );

        // كل مادة جديدة تكون مفعلة
        $validated['status'] = 'active';

        // الحفظ في جدول courses (الحقول المسموحة فقط حسب $fillable)
        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'تمت إضافة المادة بنجاح');
    }

    /**
     * عرض تفاصيل مادة محددة مع الشعب والمحاضرات المرتبطة بها
     */
    public function show($id)
    {
        // findOrFail: إذا لم توجد المادة يظهر خطأ 404 تلقائياً
        $course = Course::with([
            'department',
            'instructor',
            'semester',
            'prerequisite',
            'prerequisiteCourses',
            'sections.instructor',
            'sections.semester',
            'lectures.section',
        ])
            ->withCount('exams')
            ->findOrFail($id);

        $canManage = $this->canManage();
        $title = 'تفاصيل المادة: ' . $course->course_name;

        return view('courses.show', compact('course', 'canManage', 'title'));
    }

    /**
     * عرض نموذج تعديل مادة
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);

        // نفس القوائم مع استبعاد المادة نفسها من قائمة المتطلبات السابقة
        $lists = $this->formLists($course->id);
        $title = 'تعديل المادة: ' . $course->course_name;

        return view('courses.edit', array_merge($lists, compact('course', 'title')));
    }

    /**
     * تحديث بيانات مادة موجودة
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // نمرر رقم المادة حتى يتجاهل قيد unique المادة نفسها
        $validated = $request->validate(
            $this->validationRules($course->id),
            $this->validationMessages(),
            $this->validationAttributes()
        );

        $course->update($validated);

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'تم تعديل بيانات المادة بنجاح');
    }

    /**
     * حذف مادة: لمدير القسم ومدير النظام فقط
     * بشرط عدم ارتباطها بأي شعبة أو محاضرة أو امتحان أو مادة أخرى
     */
    public function destroy($id)
    {
        // التحقق من الصلاحية على مستوى الـ Controller (إضافة للـ Middleware في المسارات)
        if (!$this->canManage()) {
            abort(403, 'ليس لديك صلاحية حذف المواد');
        }

        $course = Course::findOrFail($id);

        // منع حذف المادة المرتبطة بشعب أو محاضرات أو امتحانات
        if ($course->sections()->exists() || $course->lectures()->exists() || $course->exams()->exists()) {
            return back()->with('error', 'لا يمكن حذف المادة لأنها مرتبطة بشعب أو محاضرات أو امتحانات');
        }

        // منع حذف مادة هي متطلب سابق لمادة أخرى
        if ($course->prerequisiteCourses()->exists()) {
            return back()->with('error', 'لا يمكن حذف المادة لأنها متطلب سابق لمواد أخرى');
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'تم حذف المادة بنجاح');
    }

    /**
     * تغيير حالة المادة (مفعلة ↔ معطلة): لمدير القسم ومدير النظام فقط
     */
    public function toggleStatus($id)
    {
        if (!$this->canManage()) {
            abort(403, 'ليس لديك صلاحية تغيير حالة المواد');
        }

        $course = Course::findOrFail($id);

        $course->status = $course->status === 'active' ? 'inactive' : 'active';
        $course->save();

        // back: الرجوع لنفس الصفحة التي ضغط منها المستخدم (القائمة أو التفاصيل)
        return back()->with('success', 'تم تغيير حالة المادة إلى: ' . $course->status_text);
    }

    // ===================== دوال مساعدة (لتجنب تكرار الكود - DRY) =====================

    /**
     * هل المستخدم الحالي مدير قسم أو مدير نظام؟ (صلاحية الحذف وتغيير الحالة)
     */
    private function canManage()
    {
        $currentRole = auth()->user()->role?->role_name;

        return in_array($currentRole, ['system_admin', 'department_manager'], true);
    }

    /**
     * القوائم المنسدلة المستخدمة في نموذجي الإضافة والتعديل
     * $exceptCourseId: رقم المادة التي لا يجب أن تظهر كمتطلب سابق لنفسها
     */
    private function formLists($exceptCourseId = null)
    {
        // الأقسام الأكاديمية فقط (بدون أقسام الكتل البرمجية)
        $departments = Department::whereNotIn('department_name', $this->blockDepartments)
            ->orderBy('department_name')
            ->get();

        // المدرسون: المستخدمون النشطون التابعون لقسم الحضور
        $instructors = User::where('status', 'active')
            ->whereHas('department', function ($query) {
                $query->where('department_name', $this->instructorsDepartment);
            })
            ->orderBy('full_name')
            ->get();

        // الفصول الدراسية
        $semesters = Semester::orderBy('start_date')->get();

        // المواد التي يمكن اختيارها كمتطلب سابق (كل المواد ما عدا المادة نفسها)
        $prerequisites = Course::when($exceptCourseId, function ($query) use ($exceptCourseId) {
            $query->where('id', '!=', $exceptCourseId);
        })
            ->orderBy('course_code')
            ->get();

        return compact('departments', 'instructors', 'semesters', 'prerequisites');
    }

    /**
     * قواعد التحقق من بيانات المادة (حسب قيود التحقق في ملف المشروع)
     * $courseId: يُمرر عند التعديل حتى لا يعتبر اسم ورمز المادة نفسها مكرراً
     */
    private function validationRules($courseId = null)
    {
        $ignore = $courseId ? ',' . $courseId : '';

        return [
            // اسم المادة: إجباري، نصي، بين 3 و 100 حرف، فريد
            'course_name' => 'required|string|min:3|max:100|unique:courses,course_name' . $ignore,

            // رمز المادة: إجباري، فريد، بصيغة محددة مثل CS101 (2-4 أحرف ثم 3 أرقام)
            'course_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z]{2,4}[0-9]{3}$/',
                'unique:courses,course_code' . $ignore,
            ],

            // عدد الساعات: إجباري، عدد صحيح موجب بين 1 و 6
            'credit_hours' => 'required|integer|between:1,6',

            // القسم والمدرس والفصل: إجبارية ويجب أن تكون موجودة في جداولها
            'department_id' => 'required|exists:departments,id',
            'instructor_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',

            // السنة الدراسية: إجبارية، عدد صحيح موجب (من الأولى إلى الرابعة)
            'academic_year' => 'required|integer|between:1,4',

            // الوصف: اختياري، أقل من 500 حرف
            'description' => 'nullable|string|max:500',

            // المتطلب السابق: اختياري، مادة موجودة، ولا يمكن أن يكون المادة نفسها
            'prerequisite_id' => 'nullable|exists:courses,id' . ($courseId ? '|not_in:' . $courseId : ''),
        ];
    }

    /**
     * رسائل الخطأ بالعربي (تظهر تحت كل حقل في النموذج)
     */
    private function validationMessages()
    {
        return [
            'required' => 'حقل :attribute مطلوب.',
            'string' => 'حقل :attribute يجب أن يكون نصاً.',
            'integer' => 'حقل :attribute يجب أن يكون عدداً صحيحاً.',
            // قواعد الحجم (min / max / between) رسالتها تختلف حسب نوع الحقل (نص أو رقم)
            // لذلك نكتبها كمصفوفة: string للنصوص و numeric للأرقام
            'min' => [
                'string' => 'حقل :attribute يجب أن يحتوي على :min أحرف على الأقل.',
                'numeric' => 'حقل :attribute يجب ألا يقل عن :min.',
                'array' => 'حقل :attribute يجب أن يحتوي على :min عناصر على الأقل.',
                'file' => 'حجم :attribute يجب ألا يقل عن :min كيلوبايت.',
            ],
            'max' => [
                'string' => 'حقل :attribute يجب ألا يتجاوز :max حرفاً.',
                'numeric' => 'حقل :attribute يجب ألا يتجاوز :max.',
                'array' => 'حقل :attribute يجب ألا يحتوي على أكثر من :max عناصر.',
                'file' => 'حجم :attribute يجب ألا يتجاوز :max كيلوبايت.',
            ],
            'between' => [
                'string' => 'حقل :attribute يجب أن يكون بين :min و :max حرفاً.',
                'numeric' => 'حقل :attribute يجب أن يكون بين :min و :max.',
                'array' => 'حقل :attribute يجب أن يحتوي بين :min و :max عناصر.',
                'file' => 'حجم :attribute يجب أن يكون بين :min و :max كيلوبايت.',
            ],
            'unique' => ':attribute مستخدم مسبقاً لمادة أخرى.',
            'exists' => 'قيمة :attribute المختارة غير موجودة.',
            'regex' => 'صيغة :attribute غير صحيحة، مثال صحيح: CS101.',
            'prerequisite_id.not_in' => 'لا يمكن أن تكون المادة متطلباً سابقاً لنفسها.',
        ];
    }

    /**
     * الأسماء العربية للحقول (تُستخدم مكان :attribute في رسائل الخطأ)
     */
    private function validationAttributes()
    {
        return [
            'course_name' => 'اسم المادة',
            'course_code' => 'رمز المادة',
            'credit_hours' => 'عدد الساعات',
            'department_id' => 'القسم',
            'instructor_id' => 'المدرس المسؤول',
            'semester_id' => 'الفصل الدراسي',
            'academic_year' => 'السنة الدراسية',
            'description' => 'الوصف',
            'prerequisite_id' => 'المتطلب السابق',
        ];
    }
}
