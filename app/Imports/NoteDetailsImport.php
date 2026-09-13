<?php

namespace App\Imports;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Note;
use App\Models\NoteDetail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class NoteDetailsImport implements OnEachRow, WithHeadingRow
{
    public int $inserted = 0;
    public int $createdSets = 0;
    public int $skippedCount = 0;
    public array $skipped = [];

    protected array $noteCache = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $rowNumber = $row->getIndex() + 1;

        $chapterName = $this->normalize($data['chapter_name'] ?? '');
        $lessonName  = $this->normalize($data['lesson_name'] ?? '');
        $courseName  = $this->normalize($data['course_name'] ?? '');
        $title       = trim($data['title'] ?? '');
        $description = $data['description'] ?? '';

        if ($chapterName === '' || $lessonName === '' || $title === '') {
            $this->addSkip($rowNumber, 'Missing chapter_name, lesson_name, or title.');
            return;
        }

        if (trim($description) === '') {
            $this->addSkip($rowNumber, 'Missing description.');
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

        if (isset($this->noteCache[$cacheKey])) {
            $note = $this->noteCache[$cacheKey];
        } else {
            $note = Note::where('chapter_id', $chapter->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            if (! $note) {
                if ($courseName === '') {
                    $this->addSkip($rowNumber, "No Note set exists for '{$chapterName}' / '{$lessonName}', and no course_name given to auto-create one.");
                    return;
                }

                $courseNames = array_map(fn ($n) => $this->normalize($n), explode(',', $courseName));
                $courses = Course::get()->filter(function ($course) use ($courseNames) {
                    return in_array(strtolower($this->normalize($course->name)), array_map('strtolower', $courseNames));
                });

                if ($courses->isEmpty()) {
                    $this->addSkip($rowNumber, "Course(s) '{$courseName}' not found. Cannot auto-create Note set.");
                    return;
                }

                $note = Note::create([
                    'chapter_id' => $chapter->id,
                    'lesson_id'  => $lesson->id,
                    'slug'       => Str::slug($chapterName . '-' . $lessonName) . '-' . uniqid(),
                    'isPaid'     => 0,
                    'status'     => 1,
                ]);

                $note->courses()->sync($courses->pluck('id'));

                $this->createdSets++;
            }

            $this->noteCache[$cacheKey] = $note;
        }

        $exists = NoteDetail::where('note_id', $note->id)
            ->where('title', $title)
            ->exists();

        if ($exists) {
            $this->addSkip($rowNumber, 'Duplicate title (already exists in this note set).');
            return;
        }

        NoteDetail::create([
            'note_id'     => $note->id,
            'title'       => $title,
            'slug'        => Str::slug($title) . '-' . uniqid(),
            'description' => $description,
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
