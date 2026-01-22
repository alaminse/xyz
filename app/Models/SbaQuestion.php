<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbaQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['sba_id', 'note_id', 'question', 'slug', 'option1', 'option2', 'option3', 'option4', 'option5', 'correct_option', 'explain'];

    public function sba()
    {
        return $this->belongsTo(Sba::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
