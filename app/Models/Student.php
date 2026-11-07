<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'university_id',
        'national_id',
        'birth_date',
        'phone',
        'email',
        'department_id',
        'academic_year',
        'photo',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sections()
    {
        return $this->belongsToMany(
            Section::class,
            'student_section'
        )->withPivot('id', 'registration_date');
    }

    public function studentSections()
    {
        return $this->hasMany(StudentSection::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
