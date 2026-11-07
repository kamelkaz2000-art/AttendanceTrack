<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'phone',
        'role_id',
        'department_id',
        'photo',
        'birth_date',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'instructor_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'instructor_id');
    }
}
