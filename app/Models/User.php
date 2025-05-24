<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'emergency_contact',
        'emergency_phone',
        'birth_date',
        'diving_experience',
        'diving_certification',
        'medical_conditions',
        'ncu_identifier', // Add this
        'email_verified_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * 檢查使用者是否擁有特定角色
     */
    public function hasRole($roleSlug)
    {
        // 如果傳入陣列，檢查是否擁有任一角色
        if (is_array($roleSlug)) {
            return $this->roles()->whereIn('slug', $roleSlug)->exists();
        }

        // 如果傳入字串，檢查單一角色
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * 檢查使用者是否為管理員（admin 或 super）
     */
    public function isAdmin()
    {
        return $this->hasRole(['admin', 'super']);
    }

    /**
     * 檢查使用者是否為超級管理員
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super');
    }

    /**
     * 檢查使用者是否為正式社員
     */
    public function isMember()
    {
        return $this->hasRole('member');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
