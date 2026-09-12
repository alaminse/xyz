<?php

namespace App\Imports;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Mcq;
use App\Models\McqQuestion;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class McqQuestionsImport implements OnEachRow, WithHeadingRow
{
    public int $inserted = 0;
    public int $createdSets = 0;
    public int $skippedCount = 0;
    public array $skipped = [];

    // cache so we don't re-query the same chapter/lesson/course repeatedly
    protected array $mcqCache = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $rowNumber = $row->getIndex() + 1;

        $chapterName = trim($data['chapter_name'] ?? '');
        $lessonName  = trim($data['lesson_name'] ?? '');
        $courseName  = trim($data['course_name'] ?? '');
        $question    = trim($data['question'] ?? '');

        if ($chapterName === '' || $lessonName === '' || $question === '') {
            $this->addSkip($rowNumber, 'Missing chapter_name, lesson_name, or question.');
            return;
        }

        $chapter = Chapter::where('name', $chapterName)->first();
        if (! $chapter) {
            $this->addSkip($rowNumber, "Chapter '{$chapterName}' not found. Please create it first.");
            return;
        }

        $lesson = Lesson::where('name', $lessonName)->first();
        if (! $lesson) {
            $this->addSkip($rowNumber, "Lesson '{$lessonName}' not found. Please create it first.");
            return;
        }

        // Cache key so repeated rows for the same chapter+lesson reuse the same Mcq
        $cacheKey = $chapter->id . '-' . $lesson->id;

        if (isset($this->mcqCache[$cacheKey])) {
            $mcq = $this->mcqCache[$cacheKey];
        } else {
            $mcq = Mcq::where('chapter_id', $chapter->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            if (! $mcq) {
                // Auto-create the MCQ set
                if ($courseName === '') {
                    $this->addSkip($rowNumber, "No MCQ set exists for '{$chapterName}' / '{$lessonName}', and no course_name given to auto-create one.");
                    return;
                }

                // course_name can contain multiple comma-separated course names
                $courseNames = array_map('trim', explode(',', $courseName));
                $courses = Course::whereIn('name', $courseNames)->get();

                if ($courses->isEmpty()) {
                    $this->addSkip($rowNumber, "Course(s) '{$courseName}' not found. Cannot auto-create MCQ set.");
                    return;
                }

                $mcq = Mcq::create([
                    'chapter_id' => $chapter->id,
                    'lesson_id'  => $lesson->id,
                    'slug'       => Str::slug($chapterName . '-' . $lessonName) . '-' . uniqid(),
                    'isPaid'     => 0,
                    'status'     => 1,
                ]);

                $mcq->courses()->sync($courses->pluck('id'));

                $this->createdSets++;
            }

            $this->mcqCache[$cacheKey] = $mcq;
        }

        // Duplicate question check
        $exists = McqQuestion::where('mcq_id', $mcq->id)
            ->where('question', $question)
            ->exists();

        if ($exists) {
            $this->addSkip($rowNumber, 'Duplicate question (already exists).');
            return;
        }

        McqQuestion::create([
            'mcq_id'   => $mcq->id,
            'question' => $question,
            'slug'     => Str::slug($question) . '-' . uniqid(),
            'option1'  => $data['option1'] ?? null,
            'answer1'  => (int) ($data['answer1'] ?? 0),
            'option2'  => $data['option2'] ?? null,
            'answer2'  => (int) ($data['answer2'] ?? 0),
            'option3'  => $data['option3'] ?? null,
            'answer3'  => (int) ($data['answer3'] ?? 0),
            'option4'  => $data['option4'] ?? null,
            'answer4'  => (int) ($data['answer4'] ?? 0),
            'option5'  => $data['option5'] ?? null,
            'answer5'  => (int) ($data['answer5'] ?? 0),
            'explain'  => $data['explain'] ?? null,
        ]);

        $this->inserted++;
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
