<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;

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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    public function prerequisiteCourses()
    {
        return $this->hasMany(Course::class, 'prerequisite_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
