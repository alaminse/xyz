<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMcqProgress extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getCourseName(): string
    {
        return $this->course ? $this->course?->name : 'N/A';
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function getChapterName(): string
    {
        return $this->chapter ? $this->chapter?->name : 'N/A';
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getLessonName(): string
    {
        return $this->lesson ? $this->lesson?->name : 'N/A';
    }
}
