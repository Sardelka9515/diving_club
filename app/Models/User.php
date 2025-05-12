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

    public function hasRole($roleSlug)
    {
        if (is_array($roleSlug)) {
            return $this->roles()->whereIn('slug', $roleSlug)->exists();
        }
        
        return $this->roles()->where('slug', $roleSlug)->exists();
    }
}