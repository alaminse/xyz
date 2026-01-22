<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAssessmentProgress extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'question_ids' => 'array',
        'remaining_question' => 'array',
        'answered_question' => 'array',
    ];

    /**
     * Relationship with Assessment
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get course through assessment relationship
     */
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            Assessment::class,
            'id', // Foreign key on assessments table
            'id', // Foreign key on courses table
            'assessment_id', // Local key on user_assessment_progress table
            'id' // Local key on assessments table
        )->join('assessment_course', 'assessments.id', '=', 'assessment_course.assessment_id');
    }

    /**
     * Scope: Completed assessments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: In-progress assessments
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope: For specific course
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->whereHas('assessment.courses', function ($q) use ($courseId) {
            $q->where('courses.id', $courseId);
        });
    }

    /**
     * Get percentage score
     */
    public function getScorePercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return round(($this->achive_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    public function getAssessmentName(): string
    {
        return $this->assessment ? $this->assessment->name : 'N/A';
    }

    public function getUserName(): string
    {
        return $this->user ? $this->user->name : 'N/A';
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getCourseName(): string
    {
        return $this->course ? $this->course->name : 'N/A';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getRankAttribute()
    {
        return UserAssessmentProgress::where('assessment_id', $this->assessment_id)
            ->where('status', 1)
            ->where(function($query) {
                $query->where('achive_marks', '>', $this->achive_marks)
                    ->orWhere(function($q) {
                        $q->where('achive_marks', '=', $this->achive_marks)
                        ->whereRaw('created_at < ?', [$this->created_at]);
                    });
            })
            ->count() + 1;
    }

    public function getPercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return round(($this->achive_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }
}
