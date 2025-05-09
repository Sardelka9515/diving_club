<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'content',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'max_participants',
        'location',
        'price',
        'activity_category_id',
        'is_published'
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'is_published' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }
    
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}