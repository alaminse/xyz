<?php

namespace App\Exports;

use App\Models\NoteDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NoteDetailsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $courseId;
    protected $chapterId;
    protected $lessonId;

    public function __construct($courseId = null, $chapterId = null, $lessonId = null)
    {
        $this->courseId = $courseId;
        $this->chapterId = $chapterId;
        $this->lessonId = $lessonId;
    }

    public function collection()
    {
        $query = NoteDetail::with(['note.chapter', 'note.lesson', 'note.courses']);

        if ($this->courseId) {
            $query->whereHas('note.courses', function ($q) {
                $q->where('courses.id', $this->courseId);
            });
        }

        if ($this->chapterId) {
            $query->whereHas('note', function ($q) {
                $q->where('chapter_id', $this->chapterId);
            });
        }

        if ($this->lessonId) {
            $query->whereHas('note', function ($q) {
                $q->where('lesson_id', $this->lessonId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name', 'note_set_id', 'is_paid', 'status',
            'title', 'description',
        ];
    }

    public function map($row): array
    {
        $note = $row->note;
        $courseNames = $note && $note->courses ? $note->courses->pluck('name')->implode(', ') : '';

        return [
            $note->chapter->name ?? '',
            $note->lesson->name ?? '',
            $courseNames,
            $note->id ?? '',
            $note && $note->isPaid ? 'Yes' : 'No',
            $note && $note->status == 1 ? 'Active' : 'Inactive',
            $row->title,
            $row->description,
        ];
    }
}
