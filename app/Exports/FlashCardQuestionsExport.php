<?php

namespace App\Exports;

use App\Models\FlashCardQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FlashCardQuestionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = FlashCardQuestion::with(['flashCard.chapter', 'flashCard.lesson', 'flashCard.courses']);

        if ($this->courseId) {
            $query->whereHas('flashCard.courses', function ($q) {
                $q->where('courses.id', $this->courseId);
            });
        }

        if ($this->chapterId) {
            $query->whereHas('flashCard', function ($q) {
                $q->where('chapter_id', $this->chapterId);
            });
        }

        if ($this->lessonId) {
            $query->whereHas('flashCard', function ($q) {
                $q->where('lesson_id', $this->lessonId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name', 'flash_card_set_id', 'is_paid', 'status',
            'question', 'answer',
        ];
    }

    public function map($row): array
    {
        $flashCard = $row->flashCard;
        $courseNames = $flashCard && $flashCard->courses ? $flashCard->courses->pluck('name')->implode(', ') : '';

        return [
            $flashCard->chapter->name ?? '',
            $flashCard->lesson->name ?? '',
            $courseNames,
            $flashCard->id ?? '',
            $flashCard && $flashCard->isPaid ? 'Yes' : 'No',
            $flashCard && $flashCard->status == 1 ? 'Active' : 'Inactive',
            $row->question,
            $row->answer,
        ];
    }
}
