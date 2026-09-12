<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class McqSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'What is 2+2?',
                '3', 0, '4', 1, '5', 0, '6', 0, '7', 0,
                'Basic addition',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name',
            'question',
            'option1', 'answer1', 'option2', 'answer2',
            'option3', 'answer3', 'option4', 'answer4',
            'option5', 'answer5', 'explain',
        ];
    }
}
