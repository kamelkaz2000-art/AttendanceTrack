<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    // الكتلة الخامسة: تفعيل created_at و updated_at لتتبع تعديلات الامتحان
    public $timestamps = true;

    protected $fillable = [
        'exam_type_id',
        'exam_date',
        'exam_time',
        'duration',
        'max_grade',
        'min_grade',
        'section_id',
        'course_id',
        'room',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'max_grade' => 'decimal:2',
        'min_grade' => 'decimal:2',
    ];

    // هل يحتوي الامتحان على درجات مسجلة؟ (يمنع الحذف)
    public function hasGrades(): bool
    {
        return $this->grades()->exists();
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
