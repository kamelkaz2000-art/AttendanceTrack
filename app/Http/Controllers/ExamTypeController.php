<?php

/*
|==========================================================================
| الكتلة البرمجية الخامسة: إدارة الامتحانات والدرجات
| اسم الطالب: ..........  -  الرقم الجامعي: ..........
|==========================================================================
| متحكم أنواع الامتحانات (مدير القسم فقط): عرض، إضافة، تعديل، حذف
*/

namespace App\Http\Controllers;

use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    // عرض أنواع الامتحانات
    public function index()
    {
        $this->authorizeManager();

        $examTypes = ExamType::withCount('exams')->orderBy('type_name')->get();

        return view('exam-types.index', compact('examTypes'));
    }

    // إضافة نوع امتحان جديد
    public function store(Request $request)
    {
        $this->authorizeManager();

        $data = $this->validateType($request);

        ExamType::create($data);

        return redirect()->route('exam-types.index')
            ->with('success', 'تمت إضافة نوع الامتحان بنجاح');
    }

    // تعديل اسم نوع الامتحان
    public function update(Request $request, $id)
    {
        $this->authorizeManager();

        $examType = ExamType::findOrFail($id);

        $data = $this->validateType($request, $examType->id);

        $examType->update($data);

        return redirect()->route('exam-types.index')
            ->with('success', 'تم تعديل نوع الامتحان بنجاح');
    }

    // حذف نوع امتحان بشرط عدم ارتباطه بامتحانات
    public function destroy($id)
    {
        $this->authorizeManager();

        $examType = ExamType::findOrFail($id);

        if ($examType->exams()->exists()) {
            return redirect()->route('exam-types.index')
                ->withErrors(['type' => 'لا يمكن حذف نوع امتحان مرتبط بامتحانات موجودة.']);
        }

        $examType->delete();

        return redirect()->route('exam-types.index')
            ->with('success', 'تم حذف نوع الامتحان بنجاح');
    }

    // قواعد التحقق المشتركة بين الإضافة والتعديل (DRY)
    private function validateType(Request $request, $ignoreId = null): array
    {
        $request->merge(['type_name' => trim(strip_tags((string) $request->input('type_name')))]);

        return $request->validate([
            'type_name' => 'required|string|min:2|max:50|unique:exam_types,type_name' . ($ignoreId ? ',' . $ignoreId : ''),
        ], [
            'type_name.required' => 'اسم نوع الامتحان مطلوب.',
            'type_name.min' => 'اسم نوع الامتحان قصير جداً.',
            'type_name.max' => 'اسم نوع الامتحان يجب ألا يتجاوز 50 حرفاً.',
            'type_name.unique' => 'نوع الامتحان موجود مسبقاً.',
        ]);
    }

    private function authorizeManager(): void
    {
        if (!ExamController::isManager()) {
            abort(403, 'ليس لديك صلاحية إدارة أنواع الامتحانات');
        }
    }
}
