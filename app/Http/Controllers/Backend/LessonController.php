<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons
     */
    public function index()
    {
        $lessons = Lesson::latest()->get();
        return view('backend.lesson.index', compact('lessons'));
    }

    /**
     * Store a newly created lesson
     */
    public function store(Request $request)
    {
        $validated = $this->validateLesson($request);
        $validated['slug'] = checkslug('lessons');

        DB::beginTransaction();
        try {
            Lesson::create($validated);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Lesson created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lesson creation failed', [
                'error' => $e->getMessage(),
                'data'  => $request->except(['_token'])
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create lesson. Please try again.');
        }
    }

    /**
     * Show the form for editing a lesson
     */
    public function edit(Lesson $lesson)
    {
        return view('backend.lesson.edit', compact('lesson'));
    }

    /**
     * Update the specified lesson
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $this->validateLesson($request, $lesson->id);

        DB::beginTransaction();
        try {
            // Prepare lesson data
            $lessonData = $this->prepareLessonData($validated);

            // Update lesson
            $lesson->update($lessonData);

            // Update pivot data for all related chapters
            $this->updateChapterPivots($lesson, $validated);

            DB::commit();

            return redirect()
                ->route('admin.lessons.index')
                ->with('success', 'Lesson updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Lesson update failed', [
                'lesson_id' => $lesson->id,
                'error'     => $e->getMessage(),
                'data'      => $request->except(['_token'])
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update lesson. Please try again.');
        }
    }

    /**
     * Soft delete the specified lesson (set status to 3)
     */
    public function destroy(Lesson $lesson)
    {
        try {
            $lesson->update(['status' => 3]);

            return redirect()
                ->route('admin.lessons.index')
                ->with('success', 'Lesson deleted successfully');

        } catch (\Exception $e) {
            Log::error('Lesson deletion failed', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete lesson. Please try again.');
        }
    }

    /**
     * Toggle lesson status
     */
    public function status(Lesson $lesson)
    {
        try {
            $newStatus = $lesson->status == 1 ? 2 : 1;
            $lesson->update(['status' => $newStatus]);

            return redirect()
                ->back()
                ->with('success', 'Status updated successfully');

        } catch (\Exception $e) {
            Log::error('Status update failed', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Get lessons for a specific chapter (API endpoint)
     */
    public function get(Request $request)
    {
        $validated = $request->validate([
            'chapterid' => 'required|exists:chapters,id',
            'for'       => 'nullable|in:sba,note,mcq,flush,videos,ospe,written,mock_viva,self_assessment'
        ]);

        $chapterId = $validated['chapterid'];
        $for = $validated['for'] ?? null;

        try {
            $chapter = Chapter::with(['lessons' => function ($query) use ($for) {
                // $query->where('lessons.status', 1);
                if ($for) {
                    $query->wherePivot($for, 1);
                }
            }])->find($chapterId);

            if (!$chapter) {
                return response()->json([
                    'error' => 'Chapter not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'chapter' => $chapter
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get chapter lessons', [
                'chapter_id' => $chapterId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Failed to retrieve lessons'
            ], 500);
        }
    }

    /**
     * Validate lesson request
     */
    private function validateLesson(Request $request, ?int $lessonId = null): array
    {
        $rules = [
            'name'              => 'required|string|max:500|unique:lessons,name' . ($lessonId ? ',' . $lessonId : ''),
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
        if ($lessonId) {
            $rules['status'] = 'required|integer|in:1,2,3';
        }

        return $request->validate($rules);
    }

    /**
     * Prepare lesson data for creation/update
     */
    private function prepareLessonData(array $validated): array
    {
        $booleanFields = [
            'sba', 'note', 'mcq', 'flush', 'videos',
            'ospe', 'written', 'mock_viva', 'self_assessment'
        ];

        $data = [
            'name' => $validated['name'],
            'status' => $validated['status'] ?? null, // Will be filtered out if null for creates
        ];

        // Add all boolean fields (0 if not present)
        foreach ($booleanFields as $field) {
            $data[$field] = $validated[$field] ?? 0;
        }

        // Remove null values (for create operations where status isn't needed)
        return array_filter($data, fn($value) => $value !== null);
    }

    /**
     * Update pivot data for all related chapters
     */
    private function updateChapterPivots(Lesson $lesson, array $validated): void
    {
        $pivotFields = ['sba', 'note', 'mcq', 'flush', 'videos', 'ospe', 'written', 'mock_viva','self_assessment'];

        $pivotData = [];
        foreach ($pivotFields as $field) {
            $pivotData[$field] = $validated[$field] ?? 0;
        }

        // Update all chapter pivots at once
        foreach ($lesson->chapters as $chapter) {
            $chapter->pivot->update($pivotData);
        }
    }
}
