<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChapterController extends Controller
{
    /**
     * Display a listing of chapters
     */
    public function index()
    {
        $courses = Course::latest()->get();
        $chapters = Chapter::latest()->get();
        $lessons = Lesson::where('status', '!=', 3)->latest()->get();

        return view('backend.chapter.index', compact('chapters', 'lessons', 'courses'));
    }

    /**
     * Store a newly created chapter
     */
    public function store(Request $request)
    {
        $validated = $this->validateChapter($request);

        DB::beginTransaction();
        try {
            // Create chapter
            $chapterData = $this->prepareChapterData($validated);
            $chapter = Chapter::create($chapterData);

            // Sync courses
            if (!empty($validated['course_ids'])) {
                $chapter->courses()->sync($validated['course_ids']);
            }

            // Sync lessons with pivot data
            if (!empty($validated['lesson_ids'])) {
                $this->syncLessonsWithPivotData($chapter, $validated['lesson_ids']);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Chapter created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Chapter creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->except(['_token'])
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create chapter. Please try again.');
        }
    }

    /**
     * Show the form for editing a chapter
     */
    public function edit(Chapter $chapter)
    {
        $chapter->load(['lessons', 'courses']);
        $lessons = Lesson::where('status', '!=', 3)->latest()->get();
        $courses = Course::latest()->get();

        return view('backend.chapter.edit', compact('chapter', 'lessons', 'courses'));
    }

    /**
     * Update the specified chapter
     */
    public function update(Request $request, Chapter $chapter)
    {
        $validated = $this->validateChapter($request, $chapter->id);

        DB::beginTransaction();
        try {
            // Update chapter
            $chapterData = $this->prepareChapterData($validated, true);
            $chapter->update($chapterData);

            // Sync courses
            if (!empty($validated['course_ids'])) {
                $chapter->courses()->sync($validated['course_ids']);
            } else {
                $chapter->courses()->detach();
            }

            // Sync lessons with pivot data
            if (!empty($validated['lesson_ids'])) {
                $this->syncLessonsWithPivotData($chapter, $validated['lesson_ids']);
            } else {
                $chapter->lessons()->detach();
            }

            DB::commit();

            return redirect()
                ->route('admin.chapters.index')
                ->with('success', 'Chapter updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Chapter update failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage(),
                'data' => $request->except(['_token'])
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update chapter. Please try again.');
        }
    }

    /**
     * Soft delete the specified chapter (set status to 3)
     */
    public function destroy(Chapter $chapter)
    {
        try {
            $chapter->update(['status' => 3]);

            return redirect()
                ->route('admin.chapters.index')
                ->with('success', 'Chapter deleted successfully');

        } catch (\Exception $e) {
            Log::error('Chapter deletion failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete chapter. Please try again.');
        }
    }

    /**
     * Toggle chapter status
     */
    public function status(Chapter $chapter)
    {
        try {
            $newStatus = $chapter->status == 1 ? 2 : 1;
            $chapter->update(['status' => $newStatus]);

            return redirect()
                ->back()
                ->with('success', 'Status updated successfully');

        } catch (\Exception $e) {
            Log::error('Status update failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Validate chapter request
     */
    private function validateChapter(Request $request, ?int $chapterId = null): array
    {
        $rules = [
            'name'              => 'required|string|max:500|unique:chapters,name' . ($chapterId ? ',' . $chapterId : ''),
            'lesson_ids'        => 'nullable|array',
            'lesson_ids.*'      => 'exists:lessons,id',
            'course_ids'        => 'nullable|array',
            'course_ids.*'      => 'exists:courses,id',
            'sba'               => 'nullable|boolean',
            'note'              => 'nullable|boolean',
            'mcq'               => 'nullable|boolean',
            'flush'             => 'nullable|boolean',
            'videos'            => 'nullable|boolean',
            'ospe'              => 'nullable|boolean',
            'written'           => 'nullable|boolean',
            'mock_viva'         => 'nullable|boolean',
            'self_assessment'   => 'nullable|boolean',
        ];

        // Add status validation for updates
        if ($chapterId) {
            $rules['status'] = 'required|integer|in:1,2,3';
        }

        return $request->validate($rules);
    }

    /**
     * Prepare chapter data for creation/update
     */
    private function prepareChapterData(array $validated, bool $isUpdate = false): array
    {
        $booleanFields = [
            'sba', 'note', 'mcq', 'flush', 'videos',
            'ospe', 'written', 'mock_viva', 'self_assessment'
        ];

        $data = ['name' => $validated['name']];

        // Add slug for new chapters
        if (!$isUpdate) {
            $data['slug'] = checkslug('chapters');
        }

        // Add status if exists (for updates)
        if (isset($validated['status'])) {
            $data['status'] = $validated['status'];
        }

        // Add boolean fields with default value 0
        foreach ($booleanFields as $field) {
            $data[$field] = $validated[$field] ?? 0;
        }

        return $data;
    }

    /**
     * Sync lessons with their pivot data
     */
    private function syncLessonsWithPivotData(Chapter $chapter, array $lessonIds): void
    {
        $syncData = [];

        // Get all lessons at once for better performance
        $lessons = Lesson::whereIn('id', $lessonIds)
            ->select('id', 'sba', 'note', 'mcq', 'flush', 'videos', 'ospe', 'written', 'mock_viva', 'self_assessment')
            ->get()
            ->keyBy('id');

        foreach ($lessonIds as $lessonId) {
            $lesson = $lessons->get($lessonId);

            if ($lesson) {
                $syncData[$lessonId] = [
                    'sba'               => $lesson->sba ?? 0,
                    'note'              => $lesson->note ?? 0,
                    'mcq'               => $lesson->mcq ?? 0,
                    'flush'             => $lesson->flush ?? 0,
                    'videos'            => $lesson->videos ?? 0,
                    'ospe'              => $lesson->ospe ?? 0,
                    'written'           => $lesson->written ?? 0,
                    'mock_viva'         => $lesson->mock_viva ?? 0,
                    'self_assessment'   => $lesson->self_assessment ?? 0,
                ];
            }
        }

        $chapter->lessons()->sync($syncData);
    }

    /**
     * Get chapters by course IDs (API endpoint)
     */
    public function getByChapters(Request $request)
    {
        $validated = $request->validate([
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'for' => 'nullable|in:sba,note,mcq,flush,videos,ospe,written,mock_viva,self_assessment'
        ]);

        $courseIds = $validated['course_ids'];
        $for = $validated['for'] ?? null;
        $courseCount = count($courseIds);

        try {
            $chapters = Chapter::select('chapters.id', 'chapters.name', 'chapters.slug')
                // ->where('chapters.status', 1)
                ->join('chapter_course', 'chapters.id', '=', 'chapter_course.chapter_id')
                ->whereIn('chapter_course.course_id', $courseIds)
                ->when($for, function ($query) use ($for) {
                    $query->where('chapters.' . $for, 1);
                })
                ->groupBy('chapters.id', 'chapters.name', 'chapters.slug')
                ->havingRaw('COUNT(DISTINCT chapter_course.course_id) = ?', [$courseCount])
                ->orderBy('chapters.name')
                ->get();

            return response()->json([
                'success' => true,
                'chapters' => $chapters,
                'count' => $chapters->count(),
                'selected_courses' => $courseCount
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get chapters by courses', [
                'course_ids' => $courseIds,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve chapters'
            ], 500);
        }
    }
}
