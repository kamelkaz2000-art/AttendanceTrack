<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExamType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type_name',
    ];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
