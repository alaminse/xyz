<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Status;

class Chapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'chapter_lesson', 'chapter_id', 'lesson_id')
                    ->withPivot('sba', 'note', 'mcq', 'flush', 'videos', 'ospe', 'written', 'mock_viva', 'self_assessment');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'chapter_course');
    }

    public function ospeStations()
    {
        return $this->hasMany(OspeStation::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class)
            ->whereRaw('JSON_CONTAINS(chapter_id, ?)', [json_encode($this->id)]);
    }

    // Or if you want just the count:
    public function getAssessmentsCountAttribute()
    {
        return Assessment::whereRaw('JSON_CONTAINS(chapter_id, ?)', [json_encode($this->id)])
            ->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE());
    }

    // Add this relationship for Flash Cards
    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }

}
