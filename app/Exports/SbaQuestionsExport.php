<?php

namespace App\Exports;

use App\Models\SbaQuestion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SbaQuestionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = SbaQuestion::with(['sba.chapter', 'sba.lesson', 'sba.courses']);

        if ($this->courseId) {
            $query->whereHas('sba.courses', function ($q) {
                $q->where('courses.id', $this->courseId);
            });
        }

        if ($this->chapterId) {
            $query->whereHas('sba', function ($q) {
                $q->where('chapter_id', $this->chapterId);
            });
        }

        if ($this->lessonId) {
            $query->whereHas('sba', function ($q) {
                $q->where('lesson_id', $this->lessonId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name', 'sba_set_id', 'is_paid', 'status',
            'question',
            'option1', 'option2', 'option3', 'option4', 'option5',
            'correct_option', 'explain',
        ];
    }

    public function map($row): array
    {
        $sba = $row->sba;
        $courseNames = $sba && $sba->courses ? $sba->courses->pluck('name')->implode(', ') : '';

        return [
            $sba->chapter->name ?? '',
            $sba->lesson->name ?? '',
            $courseNames,
            $sba->id ?? '',
            $sba && $sba->isPaid ? 'Yes' : 'No',
            $sba && $sba->status == 1 ? 'Active' : 'Inactive',
            $row->question,
            $row->option1,
            $row->option2,
            $row->option3,
            $row->option4,
            $row->option5,
            $row->correct_option,
            $row->explain,
        ];
    }
}
