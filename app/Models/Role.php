<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public $timestamps = false;

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'roleId');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'roleId');
    }
}
