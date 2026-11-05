<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * عرض قائمة جميع المقررات
     */
    public function index()
    {
        $courses = Course::latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    /**
     * عرض صفحة إنشاء مقرر جديد
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * حفظ مقرر جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_name'  => 'required|string|max:255',
            'course_code'  => 'required|string|max:50|unique:courses,course_code',
            'credit_hours' => 'required|integer|min:1|max:10',
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
        $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }

    /**
     * عرض صفحة تعديل مقرر
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    /**
     * تحديث بيانات المقرر
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validatedData = $request->validate([
            'course_name'  => 'required|string|max:255',
            'course_code'  => 'required|string|max:50|unique:courses,course_code,' . $id,
            'credit_hours' => 'required|integer|min:1|max:10',
        ]);

        $course->update($validatedData);

        return redirect()->route('courses.index')
                         ->with('success', 'تم تعديل بيانات المقرر بنجاح.');
    }

    /**
     * حذف مقرر من قاعدة البيانات
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'تم حذف المقرر بنجاح.');
    }
}