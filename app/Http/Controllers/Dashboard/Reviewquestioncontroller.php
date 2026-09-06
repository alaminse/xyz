<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\Mcq;
use App\Models\Sba;
use App\Models\UserMcqProgress;
use App\Models\UserSbaProgress;
use Illuminate\Support\Facades\Auth;

class ReviewQuestionController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    /**
     * Chapter/lesson picker page — same pattern as
     * SbaController@index / McqController@index.
     */
    public function index($courseSlug)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $courseSlug)
            ->firstOrFail();

        // Chapters that have either SBA or MCQ content, with their lessons.
        // Swap this for your own course_chapters() helper if you'd rather
        // reuse the exact same filtering logic as sba/mcq index pages.
        $chapters = $course->chapters()->with('lessons')->get();

        return view('frontend.dashboard.review.index', compact('course', 'chapters'));
    }

    /**
     * Combined SBA + MCQ "already solved" review for a chapter
     * (optionally scoped to a single lesson).
     */
    public function show($courseSlug, $chapterSlug, $lessonSlug = null)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $chapter = Chapter::where('slug', $chapterSlug)->firstOrFail();
        $lesson = $lessonSlug ? Lesson::where('slug', $lessonSlug)->firstOrFail() : null;

        $userId = $this->user->id;

        // ---------------------------------------------------------------
        // Same enrollment/lock check as SbaController@review / McqController@review
        // ---------------------------------------------------------------
        $enrolled = EnrollUser::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        // ---------------------------------------------------------------
        // isPaid is per-module (per sba_id / mcq_id), not global — build
        // lookup maps so each reviewed question carries its own flag.
        // ---------------------------------------------------------------
        $sbaPaidMap = Sba::where('chapter_id', $chapter->id)
            ->when($lesson, fn ($q) => $q->where('lesson_id', $lesson->id))
            ->pluck('isPaid', 'id');

        $mcqPaidMap = Mcq::where('chapter_id', $chapter->id)
            ->when($lesson, fn ($q) => $q->where('lesson_id', $lesson->id))
            ->pluck('isPaid', 'id');

        $sbaItems = $this->collectSbaAnswers($userId, $course, $chapter, $lesson, $sbaPaidMap);
        $mcqItems = $this->collectMcqAnswers($userId, $course, $chapter, $lesson, $mcqPaidMap);

        $allItems = $sbaItems->concat($mcqItems)->values();

        $totalCount = $allItems->count();
        $correctCount = $allItems->filter(fn ($item) => (bool) ($item['is_correct'] ?? false))->count();

        return view('frontend.dashboard.review.show', compact(
            'course',
            'chapter',
            'lesson',
            'allItems',
            'sbaItems',
            'mcqItems',
            'totalCount',
            'correctCount',
            'isLocked'
        ));
    }

    /**
     * user_sba_progress.answers is a plain JSON LIST, each element:
     * { sba_id, question_id, question, selected_option, correct_option,
     *   option1..5, is_correct, note_title, note_description, explain, answered_at }
     *
     * Multiple progress rows can exist for the same chapter (one per lesson,
     * or one chapter-wide row), so we merge all of them, keyed by question_id
     * so a re-answered question only shows once (latest wins).
     */
    private function collectSbaAnswers($userId, Course $course, Chapter $chapter, ?Lesson $lesson, $paidMap)
    {
        $rows = UserSbaProgress::query()
            ->where('user_id', $userId)
            ->where('course_id', $course->id)
            ->where('chapter_id', $chapter->id)
            ->when($lesson, fn ($q) => $q->where('lesson_id', $lesson->id))
            ->whereNotNull('answers')
            ->get();

        $merged = [];

        foreach ($rows as $row) {
            $decoded = json_decode($row->answers ?? '[]', true) ?: [];

            foreach ($decoded as $answer) {
                if (! isset($answer['question_id'])) {
                    continue;
                }

                $answer['type'] = 'sba';
                $answer['isPaid'] = (bool) ($paidMap[$answer['sba_id']] ?? false);

                $merged[$answer['question_id']] = $answer;
            }
        }

        return collect($merged)->values();
    }

    /**
     * user_mcq_progress.answers is a plain JSON LIST, each element:
     * { mcq_id, question_id, question,
     *   answers: { option1: {option_text, selected, correct, is_correct}, ... },
     *   correct_count, total_options, note_title, note_description, explain, answered_at }
     *
     * There is no top-level is_correct here, so we derive "fully correct"
     * as correct_count === total_options.
     */
    private function collectMcqAnswers($userId, Course $course, Chapter $chapter, ?Lesson $lesson, $paidMap)
    {
        $rows = UserMcqProgress::query()
            ->where('user_id', $userId)
            ->where('course_id', $course->id)
            ->where('chapter_id', $chapter->id)
            ->when($lesson, fn ($q) => $q->where('lesson_id', $lesson->id))
            ->whereNotNull('answers')
            ->get();

        $merged = [];

        foreach ($rows as $row) {
            $decoded = json_decode($row->answers ?? '[]', true) ?: [];

            foreach ($decoded as $answer) {
                if (! isset($answer['question_id'])) {
                    continue;
                }

                $answer['type'] = 'mcq';
                $answer['is_correct'] = ($answer['correct_count'] ?? 0) === ($answer['total_options'] ?? -1);
                $answer['isPaid'] = (bool) ($paidMap[$answer['mcq_id']] ?? false);

                $merged[$answer['question_id']] = $answer;
            }
        }

        return collect($merged)->values();
    }
}
