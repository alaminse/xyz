<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcq extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];



    public function questions()
    {
        return $this->hasMany(McqQuestion::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_mcq');
    }

    public function getCourseNames(): string
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
        return $this->chapter?->name ?? 'N/A';
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getLessonName(): string
    {
        return $this->lesson?->name ?? 'N/A';
    }

    // Note relationship
    public function note()
    {
        return $this->belongsTo(\App\Models\Note::class, 'note_id');
    }

    public function getNoteTitle(): string
    {
        return $this->note ? $this->note->title : 'N/A';
    }

    public function answers()
    {
        return $this->hasMany(McqAnswer::class, 'mcq_id');
    }

    public function getData()
    {
        $slug = request('slug');

        $course = Course::where('slug', $slug)->first();
        if (!$course) {
            return response()->json(['html' => '']);
        }

        $mcqs = Mcq::whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id); // ✅ Correct filtering
            })
            ->with(['chapter', 'lesson'])
            ->orderBy('id', 'DESC')
            ->get();

        $html = view('backend.includes.mcq_rows', compact('mcqs'))->render();

        return response()->json(['html' => $html]);
    }



}
