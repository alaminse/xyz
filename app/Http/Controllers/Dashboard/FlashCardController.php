<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\FlashCard;
use App\Models\FlashCardQuestion;
use App\Models\Lesson;
use App\Models\UserFlashProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FlashCardController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function index(string $course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        if (! $course) {
            abort(404, 'Course not found');
        }

        $chapters = course_chapters($course, 'flush');
        $progress = UserFlashProgress::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.dashboard.flash.index', compact(
            'course',
            'chapters',
            'progress'
        ));
    }

    public function getTest($courseSlug, $chapterSlug, $lessonSlug = null)
    {
        /* =====================
        BASIC DATA
        ====================== */

        $course = Course::select('id', 'slug', 'name')
            ->where('slug', $courseSlug)
            ->firstOrFail();

        $chapter = Chapter::select('id', 'slug', 'name')
            ->where('slug', $chapterSlug)
            ->firstOrFail();

        $lesson = $lessonSlug
            ? Lesson::select('id', 'slug', 'name')->where('slug', $lessonSlug)->firstOrFail()
            : null;

        /* =====================
        ENROLL CHECK
        ====================== */

        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        /* =====================
        FLASHCARD
        ====================== */

        $flashCardQuery = FlashCard::whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE());

        if ($lesson) {
            $flashCardQuery->where('lesson_id', $lesson->id);
        }

        if ($enrolled->status === Status::FREETRIAL()) {
            $flashCardQuery->where('isPaid', 0);
        }

        $flashCard = $flashCardQuery->first();

        if (! $flashCard) {
            return redirect()->back()->with('error', 'No flash card available.');
        }

        /* =====================
        QUESTIONS
        ====================== */

        $questionIds = $flashCard->questions->pluck('id')->toArray();
        $totalOriginalQuestions = count($questionIds);

        /* =====================
        QUIZ (PROGRESS)
        ====================== */

        $quiz = UserFlashProgress::firstOrNew([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'chapter_id' => $chapter->id,
            'lesson_id' => $lesson?->id,
        ]);

        $remainingQuestionIds = [];
        $repeatQueueIds = [];

        /* =====================
        CORE LOGIC
        ====================== */

        if ($quiz->exists) {

            $remaining = json_decode($quiz->remaining_flashcards, true) ?? [];

            if (! empty($remaining)) {
                // ✅ Continue existing test
                $remainingQuestionIds = $remaining;

            } else {
                // 🔄 Reset & start new test
                $quiz->fill([
                    'total' => $totalOriginalQuestions,
                    'current_question_index' => 0,
                    'correct' => 0,
                    'wrong' => 0,
                    'flashs_id' => json_encode($questionIds),
                    'answered_flashcards' => json_encode([]),
                    'remaining_flashcards' => json_encode($questionIds),
                    'answers' => json_encode([]),
                    'question_queue' => json_encode([]),
                    'status' => null,
                    'finished_at' => null,
                ])->save();

                $remainingQuestionIds = $questionIds;
            }

        } else {
            // 🆕 First time create quiz
            $quiz->fill([
                'slug' => checkSlug('user_flash_progress'),
                'total' => $totalOriginalQuestions,
                'current_question_index' => 0,
                'correct' => 0,
                'wrong' => 0,
                'flashs_id' => json_encode($questionIds),
                'remaining_flashcards' => json_encode($questionIds),
                'answered_flashcards' => json_encode([]),
                'answers' => json_encode([]),
                'question_queue' => json_encode([]),
            ])->save();

            $remainingQuestionIds = $questionIds;
        }

        /* =====================
        QUESTIONS DATA
        ====================== */

        $questions = $flashCard->questions->whereIn('id', $remainingQuestionIds);

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        /* =====================
        VIEW
        ====================== */

        return view('frontend.dashboard.flash.test', compact(
            'courseSlug',
            'questions',
            'quiz',
            'course',
            'chapter',
            'lesson',
            'remainingQuestionIds',
            'repeatQueueIds',
            'totalOriginalQuestions'
        ));
    }

    public function updateProgress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:user_flash_progress,id',
            'question_id' => 'required|exists:flash_card_questions,id',
            'rating' => 'required|in:verywell,confused,notatall',
            'queue_position' => 'required|integer',
            'is_repeat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $quiz = UserFlashProgress::where('id', $request->quiz_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $answered  = json_decode($quiz->answered_flashcards, true) ?? [];
        $remaining = json_decode($quiz->remaining_flashcards, true) ?? [];
        $answers   = json_decode($quiz->answers, true) ?? [];

        $rating     = $request->rating;
        $questionId = $request->question_id;
        $isRepeat   = filter_var($request->is_repeat, FILTER_VALIDATE_BOOLEAN);

        /* =====================
        SCORE HANDLING
        ====================== */

        if ($rating === 'verywell') {
            $quiz->correct += 1;

            // repeat correction: reduce previous wrong
            if ($isRepeat) {
                $quiz->wrong = max(0, $quiz->wrong - 1);
            }
        } else {
            // confused OR notatall
            $quiz->wrong += 1;
        }

        /* =====================
        REMAINING FLASHCARDS
        ====================== */

        if ($rating === 'verywell') {
            $remaining = array_values(array_diff($remaining, [$questionId]));
        }

        /* =====================
        ANSWERED FLASHCARDS
        ====================== */

        if ($rating === 'verywell') {
            if (! in_array($questionId, $answered)) {
                $answered[] = $questionId;
            }
        }

        /* =====================
        STORE ANSWER HISTORY
        ====================== */

        $answers[] = [
            'question_id' => $questionId,
            'rating' => $rating,
            'is_repeat' => $isRepeat,
            'answered_at' => now()->toDateTimeString(),
        ];

        /* =====================
        SAVE
        ====================== */

        $quiz->answered_flashcards  = json_encode($answered);
        $quiz->remaining_flashcards = json_encode($remaining);
        $quiz->answers              = json_encode($answers);
        $quiz->current_question_index += 1;
        $quiz->save();

        return response()->json([
            'status' => 'success',
            'remaining' => count($remaining),
            'correct' => $quiz->correct,
            'wrong' => $quiz->wrong,
            'is_complete' => empty($remaining),
        ]);
    }


    public function review(string $slug)
    {
        $quiz = UserFlashProgress::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        abort_if(! $quiz->answers, 404, 'No answers found');

        $answers = json_decode($quiz->answers, true);

        // 🔹 Collect question IDs
        $questionIds = collect($answers)->pluck('question_id')->unique();

        // 🔹 Load questions
        $questions = FlashCardQuestion::whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        // 🔹 Merge question data into answers
        $answers = collect($answers)->map(function ($item) use ($questions) {
            $question = $questions->get($item['question_id']);

            return [
                'question_id' => $item['question_id'],
                'question' => $question?->question ?? 'Question not found',
                'answer' => $question?->answer ?? '',
                'rating' => $item['rating'],
                'answered_at' => $item['answered_at'],
            ];
        })->values();

        $data = [
            'total' => $quiz->total,
            'correct' => $quiz->correct,
            'wrong' => $quiz->wrong,
        ];

        return view('frontend.dashboard.flash.review', compact(
            'answers',
            'data',
            'quiz'
        ));
    }
}
