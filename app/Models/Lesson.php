<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'slug',
        'sba',
        'note',
        'mcq',
        'flush',
        'videos',
        'ospe',
        'written',
        'mock_viva',
        'self_assessment',
        'status',
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_lesson', 'lesson_id', 'chapter_id')
                    ->withPivot('sba', 'note', 'mcq', 'flush', 'videos', 'ospe', 'written', 'mock_viva');
    }

    // Existing relationships (keep your existing ones)
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // Add this relationship for Flash Cards
    public function flashCards()
    {
        return $this->hasMany(FlashCard::class);
    }
}
