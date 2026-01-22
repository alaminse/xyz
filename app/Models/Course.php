<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

   
    public function notes()
    {
        return $this->belongsToMany(Note::class, 'course_note');
    }

    public function mcqs()
    {
        return $this->belongsToMany(Mcq::class, 'course_mcq');
    }

    public function flashCards()
    { 
        return $this->belongsToMany(\App\Models\FlashCard::class, 'course_flash_card');
    }


    
    public function sbas()
    {
        return $this->belongsToMany(Sba::class, 'course_sba');
    }
    public function mock_vivas()
    {
        return $this->belongsToMany(MockViva::class, 'course_mock_viva');

    }

    public function detail(): HasOne
    {
        return $this->hasOne(CourseDetails::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getCourseName(): string
    {
        return $this->parent ? $this->parent->name : 'N/A';
    }

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_course');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'assign_to', 'course_id', 'user_id');
    }

    public function assessments()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_course', 'course_id', 'assessment_id');
    }
    
}
