<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'permission_name',
        'role_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
