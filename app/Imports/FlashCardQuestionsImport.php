<?php

namespace App\Imports;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\FlashCard;
use App\Models\FlashCardQuestion;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class FlashCardQuestionsImport implements OnEachRow, WithHeadingRow
{
    public int $inserted = 0;
    public int $createdSets = 0;
    public int $skippedCount = 0;
    public array $skipped = [];

    protected array $flashCardCache = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $rowNumber = $row->getIndex() + 1;

        $chapterName = $this->normalize($data['chapter_name'] ?? '');
        $lessonName  = $this->normalize($data['lesson_name'] ?? '');
        $courseName  = $this->normalize($data['course_name'] ?? '');
        $question    = trim($data['question'] ?? '');
        $answer      = trim($data['answer'] ?? '');

        if ($chapterName === '' || $lessonName === '' || $question === '') {
            $this->addSkip($rowNumber, 'Missing chapter_name, lesson_name, or question.');
            return;
        }

        if ($answer === '') {
            $this->addSkip($rowNumber, 'Missing answer.');
            return;
        }

        $chapter = Chapter::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($chapterName)])->first();
        if (! $chapter) {
            $this->addSkip($rowNumber, "Chapter '{$chapterName}' not found. Please create it first.");
            return;
        }

        $lesson = Lesson::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($lessonName)])->first();
        if (! $lesson) {
            $this->addSkip($rowNumber, "Lesson '{$lessonName}' not found. Please create it first.");
            return;
        }

        $cacheKey = $chapter->id . '-' . $lesson->id;

        if (isset($this->flashCardCache[$cacheKey])) {
            $flashCard = $this->flashCardCache[$cacheKey];
        } else {
            $flashCard = FlashCard::where('chapter_id', $chapter->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            if (! $flashCard) {
                if ($courseName === '') {
                    $this->addSkip($rowNumber, "No Flash Card set exists for '{$chapterName}' / '{$lessonName}', and no course_name given to auto-create one.");
                    return;
                }

                $courseNames = array_map(fn ($n) => $this->normalize($n), explode(',', $courseName));
                $courses = Course::get()->filter(function ($course) use ($courseNames) {
                    return in_array(strtolower($this->normalize($course->name)), array_map('strtolower', $courseNames));
                });

                if ($courses->isEmpty()) {
                    $this->addSkip($rowNumber, "Course(s) '{$courseName}' not found. Cannot auto-create Flash Card set.");
                    return;
                }

                $flashCard = FlashCard::create([
                    'chapter_id' => $chapter->id,
                    'lesson_id'  => $lesson->id,
                    'isPaid'     => 0,
                    'status'     => 1,
                ]);

                $flashCard->courses()->sync($courses->pluck('id'));

                $this->createdSets++;
            }

            $this->flashCardCache[$cacheKey] = $flashCard;
        }

        $exists = FlashCardQuestion::where('flash_card_id', $flashCard->id)
            ->where('question', $question)
            ->exists();

        if ($exists) {
            $this->addSkip($rowNumber, 'Duplicate question (already exists).');
            return;
        }

        FlashCardQuestion::create([
            'flash_card_id' => $flashCard->id,
            'question'      => $question,
            'slug'          => Str::slug($question) . '-' . uniqid(),
            'answer'        => $answer,
        ]);

        $this->inserted++;
    }

    private function normalize(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        $value = str_replace(["\xC2\xA0", "\xA0"], ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        return trim($value);
    }

    private function addSkip(int $rowNumber, string $reason): void
    {
        $this->skippedCount++;

        if (! isset($this->skipped[$reason])) {
            $this->skipped[$reason] = ['count' => 0, 'rows' => []];
        }

        $this->skipped[$reason]['count']++;

        if (count($this->skipped[$reason]['rows']) < 5) {
            $this->skipped[$reason]['rows'][] = $rowNumber;
        }
    }
}
