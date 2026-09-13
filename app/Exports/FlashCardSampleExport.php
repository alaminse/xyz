<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FlashCardSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'What is the normal ejection fraction of the left ventricle?',
                '55-70%',
            ],
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'What is the medical term for a heart attack?',
                'Myocardial Infarction',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name',
            'question', 'answer',
        ];
    }
}
