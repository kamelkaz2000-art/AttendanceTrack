<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'semester_name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
