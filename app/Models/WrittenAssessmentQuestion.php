<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WrittenAssessmentQuestion extends Model
{

    protected $fillable = ['written_assessment_id', 'questions'];

    protected $casts = [
        'questions' => 'array',
    ];
}
