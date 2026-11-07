<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSection extends Model
{
    use HasFactory;

    protected $table = 'student_section';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'section_id',
        'registration_date',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
