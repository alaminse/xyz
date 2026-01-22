<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FlashCardQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_card_id',
        'question',
        'slug',
        'answer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            if (empty($question->slug)) {
                $question->slug = Str::slug($question->question) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function flashCard()
    {
        return $this->belongsTo(FlashCard::class);
    }
}
