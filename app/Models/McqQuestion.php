<?php
// App\Models\McqQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    protected $fillable = [
        'note_id',
        'mcq_id',
        'question',
        'slug',
        'option1',
        'answer1',
        'option2',
        'answer2',
        'option3',
        'answer3',
        'option4',
        'answer4',
        'option5',
        'answer5',
        'explain'
    ];

    protected $casts = [
        'answer1' => 'boolean',
        'answer2' => 'boolean',
        'answer3' => 'boolean',
        'answer4' => 'boolean',
        'answer5' => 'boolean',
    ];

    public function mcq()
    {
        return $this->belongsTo(Mcq::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    // Get correct answers as array
    public function getCorrectAnswers()
    {
        $correct = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($this->{"answer{$i}"}) {
                $correct[] = "option{$i}";
            }
        }
        return $correct;
    }
}
