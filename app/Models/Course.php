<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'course_name', 'course_code', 'credit_hours', 
        'department_id', 'instructor_id', 'semester_id', 
        'academic_year', 'description', 'prerequisite_id', 'status'
    ];

    // أضف هذه الخاصية للتحويل البرمجي للأنواع (مطلوبة في الوظيفة)
    protected $casts = [
        'credit_hours' => 'integer',
    ];

    // المقرر ينتمي إلى قسم واحد
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // المقرر يشرف عليه مدرس واحد (من جدول المستخدمين users)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // المقرر يدرس في فصل دراسي واحد
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // المقرر قد يكون له مادة سابقة (متطلب سابق)
    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    // (أضف هذه العلاقة) للتحقق من عدم حذف المادة إذا كانت مرتبطة بشعب
    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id');
    }
}