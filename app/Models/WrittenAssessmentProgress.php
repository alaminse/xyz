<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrittenAssessmentProgress extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'course_id', 'chapter_id',
        'lesson_id', 'question_group_id', 'status'
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
