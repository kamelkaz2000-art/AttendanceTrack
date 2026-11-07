<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'lecture_date',
        'lecture_time',
        'duration',
        'section_id',
        'course_id',
        'instructor_id',
    ];

    protected $casts = [
        'lecture_date' => 'date',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
