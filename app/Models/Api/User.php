<?php

namespace App\Models\Api;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [''];

    protected $hidden = [
        'password',
        'CreationTime',
        'CreatorUserId',
        'LastModificationTime',
        'LastModifierUserId',
        'IsDeleted',
        'DeleterUserId',
        'DeletionTime',
        'TenantId',
        'googleId',
    ];

    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'userId');
    }
}
