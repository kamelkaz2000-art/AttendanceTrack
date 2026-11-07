<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'section_name',
        'course_id',
        'instructor_id',
        'semester_id',
        'capacity',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_section'
        )->withPivot('id', 'registration_date');
    }

    public function studentSections()
    {
        return $this->hasMany(StudentSection::class);
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
