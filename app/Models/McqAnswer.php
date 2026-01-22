<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class McqAnswer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'correct' => 'boolean',
    ];

    /**
     * Belongs to the MCQ question
     */
    public function mcq(): BelongsTo
    {
            return $this->belongsTo(Mcq::class, 'mcq_id');
        }

    /**
     * Belongs to a quiz attempt (user progress)
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(UserMcqProgress::class, 'quiz_id');
    }
}
