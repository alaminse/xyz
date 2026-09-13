<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'lesson_id',
        'slug',
        'isPaid',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(NoteDetail::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_note', 'note_id', 'course_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    
}
