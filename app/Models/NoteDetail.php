<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'title',
        'slug',
        'description',
    ];
    

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
