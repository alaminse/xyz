<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OspeQuestion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

       public function note()
    {
        return $this->belongsTo(Note::class, 'note_id');
    }

    public function getNoteTitle(): string
    {
        return $this->note ? $this->note?->title : 'N/A';
    }
}
