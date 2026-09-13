<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NoteSampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'Anatomy of the Heart',
                'The heart is a four-chambered muscular organ responsible for pumping blood throughout the body. It consists of two atria and two ventricles.',
            ],
            [
                'Cardiology', 'Heart Basics', 'MBBS Course',
                'Cardiac Cycle',
                'The cardiac cycle refers to the sequence of events that occur during one complete heartbeat, including systole and diastole.',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'chapter_name', 'lesson_name', 'course_name',
            'title', 'description',
        ];
    }
}
