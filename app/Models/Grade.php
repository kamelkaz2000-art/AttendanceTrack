<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    /*
    // الوظيفة الأولى (Assignment 1)
    protected $fillable = [
        'exam_id',
        'student_id',
        'grade',
    ];
    */

    // الكتلة الخامسة: تفعيل created_at و updated_at لتتبع تعديل الدرجات
    public $timestamps = true;

    protected $fillable = [
        'exam_id',
        'student_id',
        'grade',
        'recorded_date',
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'grade' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
