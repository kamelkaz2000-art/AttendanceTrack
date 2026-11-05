<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    // المستخدم ينتمي إلى دور واحد
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // المستخدم ينتمي إلى قسم واحد
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
