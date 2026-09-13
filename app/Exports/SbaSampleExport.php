<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SbaSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'A 55-year-old man presents with chest pain radiating to the left arm. What is the most likely diagnosis?',
                'Myocardial Infarction', 'Gastritis', 'Pneumonia', 'Costochondritis', 'Panic Attack',
                'Myocardial Infarction',
                'Classic presentation of MI includes chest pain radiating to the left arm, especially in older patients.',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name',
            'question',
            'option1', 'option2', 'option3', 'option4', 'option5',
            'correct_option', 'explain',
        ];
    }
}
