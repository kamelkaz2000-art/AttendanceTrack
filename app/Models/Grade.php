<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'exam_id',
        'student_id',
        'grade',
        'recorded_date',
    ];

    protected $casts = [
        'recorded_date' => 'date',
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
