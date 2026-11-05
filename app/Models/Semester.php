<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'semester_name',
        'start_date',
        'end_date',
    ];

    // علاقة الفصل الدراسي مع المقررات (الفصل الواحد يضم عدة مقررات)
    public function courses()
    {
        return $this->hasMany(Course::class, 'semester_id');
    }
}