<?php

namespace App\Exports;

use App\Models\McqQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class McqQuestionsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
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
        $query = McqQuestion::with(['mcq.chapter', 'mcq.lesson', 'mcq.courses']);

        // Filter by course (optional)
        if ($this->courseId) {
            $query->whereHas('mcq.courses', function ($q) {
                $q->where('courses.id', $this->courseId);
            });
        }

        // Filter by chapter (optional)
        if ($this->chapterId) {
            $query->whereHas('mcq', function ($q) {
                $q->where('chapter_id', $this->chapterId);
            });
        }

        // Filter by lesson (optional)
        if ($this->lessonId) {
            $query->whereHas('mcq', function ($q) {
                $q->where('lesson_id', $this->lessonId);
            });
        }

        // No filter at all -> exports ALL questions from ALL MCQ sets
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name', 'mcq_set_id', 'is_paid', 'status',
            'question',
            'option1', 'answer1',
            'option2', 'answer2',
            'option3', 'answer3',
            'option4', 'answer4',
            'option5', 'answer5',
            'explain',
        ];
    }

    public function map($row): array
    {
        $mcq = $row->mcq;
        $courseNames = $mcq && $mcq->courses ? $mcq->courses->pluck('name')->implode(', ') : '';

        return [
            $mcq->chapter->name ?? '',
            $mcq->lesson->name ?? '',
            $courseNames,
            $mcq->id ?? '',
            $mcq && $mcq->isPaid ? 'Yes' : 'No',
            $mcq && $mcq->status == 1 ? 'Active' : 'Inactive',
            $row->question,
            $row->option1, $row->answer1 ? 1 : 0,
            $row->option2, $row->answer2 ? 1 : 0,
            $row->option3, $row->answer3 ? 1 : 0,
            $row->option4, $row->answer4 ? 1 : 0,
            $row->option5, $row->answer5 ? 1 : 0,
            $row->explain,
        ];
    }
}
