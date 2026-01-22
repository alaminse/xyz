<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sba extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function questions()
    {
        return $this->hasMany(SbaQuestion::class);
    }

    // ✅ Many-to-many course relationship
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_sba');
    }

    // ✅ Return comma-separated course names
    public function getCourseName(): string
    {
        return $this->courses->isNotEmpty()
            ? $this->courses->pluck('name')->join(', ')
            : 'N/A';
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function getChapterName(): string
    {
        return $this->chapter ? $this->chapter->name : 'N/A';
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getLessonName(): string
    {
        return $this->lesson ? $this->lesson->name : 'N/A';
    }

    public function note()
    {
        return $this->belongsTo(\App\Models\Note::class, 'note_id');
    }

    public function getNoteTitle(): string
    {
        return $this->note ? $this->note->title : 'N/A';
    }
}
