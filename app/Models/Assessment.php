<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'course_ids' => 'array',
        'question_ids' => 'array',
    ];

    /**
     * Relationship with Chapter (assuming chapter_id is JSON array)
     */
    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'assessment_chapter')
                    ->orWhereRaw('JSON_CONTAINS(assessments.chapter_id, CAST(chapters.id AS JSON))');
    }

    /**
     * Relationship with Lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relationship with UserAssessmentProgress
     */
    public function userProgress()
    {
        return $this->hasMany(UserAssessmentProgress::class, 'assessment_id');
    }


    /**
     * Get user's specific progress for this assessment
     */
    public function userProgressForUser($userId)
    {
        return $this->hasOne(UserAssessmentProgress::class, 'assessment_id')
                    ->where('user_id', $userId);
    }

    /**
     * Scope: Active assessments
     */
    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE());
    }

    /**
     * Scope: Paid assessments
     */
    public function scopePaid($query)
    {
        return $query->where('isPaid', true);
    }

    /**
     * Scope: Free assessments
     */
    public function scopeFree($query)
    {
        return $query->where('isPaid', false);
    }

    /**
     * Scope: Ongoing assessments
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date_time', '<=', now())
                     ->where('end_date_time', '>=', now());
    }

    /**
     * Many-to-Many relationship with Course
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'assessment_course', 'assessment_id', 'course_id');
    }

  	public function getCourseNamesAttribute()
    {
        return $this->courses->pluck('name')->join(', ') ?: 'N/A';
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function getChapterName(): string
    {
        return $this->chapter ? $this->chapter?->name : 'N/A';
    }

    public function getLessonName(): string
    {
        return $this->lesson ? $this->lesson?->name : 'N/A';
    }

    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class, 'assessment_id');
    }

    public function leaderboard()
    {
        return $this->hasMany(UserAssessmentProgress::class, 'assessment_id')
            ->where('status', 1)
            ->with('user')
            ->orderBy('achive_marks', 'desc');
    }

}
