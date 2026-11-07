<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| النموذج (Model) الخاص بجدول المواد courses
| يمثل المادة الدراسية ويحتوي على الحقول المسموح تعبئتها والعلاقات مع الجداول الأخرى
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /*
    |----------------------------------------------------------------------
    | الوظيفة الأولى (Assignment 1) - موضوعة كتعليق
    |----------------------------------------------------------------------
    | المطلوب: إنشاء النموذج Course مع تحديد $fillable للحقول
    | (course_name, course_code, credit_hours) و $casts لتحويل credit_hours إلى integer
    |
    | // الحقول المسموح تعبئتها دفعة واحدة (Mass Assignment)
    | protected $fillable = [
    |     'course_name',
    |     'course_code',
    |     'credit_hours',
    | ];
    |
    | // تحويل عدد الساعات تلقائياً إلى رقم صحيح عند القراءة والكتابة
    | protected $casts = [
    |     'credit_hours' => 'integer',
    | ];
    |----------------------------------------------------------------------
    */

    // ====================== الوظيفة الثانية (Assignment 2) ======================

    // 1. الخصائص الأساسية
    // (كود سابق موجود بالمشروع تم وضعه كتعليق بدون حذف)
    // public $timestamps = false;

    // تفعيل التواريخ: Laravel يعبّي created_at عند الإضافة و updated_at عند كل تعديل
    public $timestamps = true;

    // 2. الحقول القابلة للتعبئة (الحقول المسموح حفظها من النماذج)
    protected $fillable = [
        'course_name',
        'course_code',
        'credit_hours',
        'department_id',
        'instructor_id',
        'semester_id',
        'academic_year',
        'description',
        'prerequisite_id',
        'status',
    ];

    // 3. تحويل الأنواع: عدد الساعات رقم صحيح، والتواريخ كائنات تاريخ (Carbon)
    protected $casts = [
        'credit_hours' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // 4. القيم الافتراضية: كل مادة جديدة تكون مفعلة
    protected $attributes = [
        'status' => 'active',
    ];

    // 5. العلاقات

    // المادة تنتمي إلى قسم واحد (Many to One)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // المادة لها مدرس مسؤول واحد من جدول المستخدمين (المفتاح instructor_id)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // المادة تنتمي إلى فصل دراسي واحد
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // المتطلب السابق لهذه المادة (علاقة المادة مع نفسها)
    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    // المواد التي تعتبر هذه المادة متطلباً سابقاً لها
    public function prerequisiteCourses()
    {
        return $this->hasMany(Course::class, 'prerequisite_id');
    }

    // الشعب المرتبطة بالمادة (One to Many)
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    // المحاضرات المرتبطة بالمادة
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    // الامتحانات المرتبطة بالمادة
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    // 6. النطاقات (Scopes)

    // جلب المواد المفعلة فقط: Course::active()->get()
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // جلب المواد المعطلة فقط: Course::inactive()->get()
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // 7. Accessors & Mutators

    // نص الحالة بالعربي للعرض: $course->status_text
    public function getStatusTextAttribute()
    {
        return $this->status === 'active' ? 'مفعلة' : 'معطلة';
    }

    // تحويل رمز المادة إلى أحرف كبيرة وإزالة المسافات قبل الحفظ (cs101 تصبح CS101)
    public function setCourseCodeAttribute($value)
    {
        $this->attributes['course_code'] = strtoupper(trim($value));
    }

    // إزالة المسافات الزائدة من اسم المادة قبل الحفظ
    public function setCourseNameAttribute($value)
    {
        $this->attributes['course_name'] = trim($value);
    }
}
