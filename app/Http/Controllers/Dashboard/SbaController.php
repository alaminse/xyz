<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Sba;
use App\Models\SbaQuestion;
use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\UserSbaProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SbaController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function index($courseSlug)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $courseSlug)
            ->firstOrFail();

        if (!$course) {
            abort(404, 'Course not found');
        }

        $chapters = course_chapters($course, 'sba');

        $enrolledCourseIds = EnrollUser::where('user_id', $this->user->id)
            ->pluck('course_id');

        $progress = UserSbaProgress::where('user_id', $this->user->id)
            ->whereIn('course_id', $enrolledCourseIds)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.dashboard.sba.index', compact('course', 'chapters', 'progress'));
    }

    public function getTest($courseSlug, $chapterSlug, $lessonSlug = null)
    {
        // Course, Chapter, Lesson খোঁজা
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $chapter = Chapter::where('slug', $chapterSlug)->firstOrFail();
        $lesson = $lessonSlug ? Lesson::where('slug', $lessonSlug)->firstOrFail() : null;

        // Enrollment check
        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // SBA query
        $sbaQuery = Sba::query()
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id));

        if ($lesson) {
            $sbaQuery->where('lesson_id', $lesson->id);
        }

        if ($enrolled->status === Status::FREETRIAL()) {
            $sbaQuery->where('isPaid', 0);
        }

        $sba = $sbaQuery->first();

        if (!$sba || !$sba->questions || $sba->questions->isEmpty()) {
            return redirect()->back()->with('error', 'No SBA questions available for this topic.');
        }

        // SBA Questions load করা
        $sbaQuestions = SbaQuestion::with(['sba.note'])
            ->where('sba_id', $sba->id)
            ->get();

        if ($sbaQuestions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        // Question IDs collect করা
        $questionIds = $sbaQuestions->pluck('id')->toArray();

        // User Progress খোঁজা বা তৈরি করা
        $quiz = UserSbaProgress::firstOrNew([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'chapter_id' => $chapter->id,
            'lesson_id' => $lesson?->id,
        ]);

        if ($quiz->exists) {
            // নতুন Questions যোগ করা
            $existingIds = json_decode($quiz->sbas_id, true) ?? [];
            $newQuestions = array_diff($questionIds, $existingIds);

            if (!empty($newQuestions)) {
                $remainingIds = json_decode($quiz->remaining_sba, true) ?? [];
                $quiz->remaining_sba = json_encode(array_unique(array_merge($remainingIds, $newQuestions)));
                $quiz->sbas_id = json_encode(array_unique(array_merge($existingIds, $newQuestions)));
                $quiz->total = count(json_decode($quiz->sbas_id, true));
            }
        } else {
            // নতুন quiz তৈরি করা
            $quiz->fill([
                'slug' => checkSlug('user_sba_progress'),
                'total' => count($questionIds),
                'current_question_index' => 0,
                'sbas_id' => json_encode($questionIds),
                'remaining_sba' => json_encode($questionIds),
                'answered_sba' => json_encode([]),
                'progress' => 0,
                'progress_cut' => 0,
                'answers' => json_encode([]),
                'correct' => 0,
                'wrong' => 0,
            ]);
        }

        $quiz->save();

        // বাকি Questions বের করা
        $remainingQuestionIds = json_decode($quiz->remaining_sba, true) ?? [];

        // ✅ যদি কোনো প্রশ্ন না থাকে তাহলে রিসেট করে আবার শুরু করা
        if (empty($remainingQuestionIds)) {
            $quiz->update([
                'total' => count($questionIds),
                'current_question_index' => 0,
                'remaining_sba' => json_encode($questionIds),
                'answered_sba' => json_encode([]),
                'progress' => 0,
                'progress_cut' => 0,
                'answers' => json_encode([]),
                'correct' => 0,
                'wrong' => 0,
            ]);

            $remainingQuestionIds = $questionIds;
        }

        // শুধু remaining Questions load করা
        $sbaQuestionsFiltered = SbaQuestion::with(['note'])
            ->whereIn('id', $remainingQuestionIds)
            ->when($enrolled && $enrolled->status === Status::FREETRIAL(), function ($query) {
                return $query->whereHas('sba', function ($q) {
                    $q->where('isPaid', 0);
                });
            })
            ->inRandomOrder()
            ->get();

        if ($sbaQuestionsFiltered->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        return view('frontend.dashboard.sba.test', compact(
            'sbaQuestionsFiltered',
            'quiz',
            'chapter',
            'lesson',
            'course',
            'sba'
        ));
    }

    public function updateProgress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id' => 'required|exists:user_sba_progress,id',
                'sba_id' => 'required|exists:sbas,id',
                'question_id' => 'required|exists:sba_questions,id',
                'selected_option' => 'required|string|in:option1,option2,option3,option4,option5',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }

            $validated = $validator->validated();

            // Quiz and ownership verify
            $quiz = UserSbaProgress::where('id', $validated['quiz_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Question and SBA load
            $question = SbaQuestion::with('note')->findOrFail($validated['question_id']);
            $sba = Sba::findOrFail($validated['sba_id']);

            // User selected option
            $selectedOption = $validated['selected_option'];
            $correctOption = $question->correct_option;

            // Check if correct
            $isCorrect = ($selectedOption === $correctOption);

            // Progress data
            $remainingSba = json_decode($quiz->remaining_sba, true) ?? [];
            $answeredSba = json_decode($quiz->answered_sba, true) ?? [];
            $allAnswers = json_decode($quiz->answers, true) ?? [];

            // Answer key
            $answerKey = $validated['question_id'];

            // Check if already answered
            if (in_array($answerKey, $answeredSba)) {
                return response()->json([
                    'error' => 'You have already answered this question.'
                ], 422);
            }

            // Remaining question remove
            $remainingSba = array_values(array_diff($remainingSba, [$answerKey]));

            // Answered
            $answeredSba[] = $answerKey;

            // Complete answer data store
            $allAnswers[] = [
                'sba_id' => $sba->id,
                'question_id' => $question->id,
                'question' => $question->question,
                'selected_option' => $selectedOption,
                'correct_option' => $correctOption,
                'option1' => $question->option1,
                'option2' => $question->option2,
                'option3' => $question->option3,
                'option4' => $question->option4,
                'option5' => $question->option5,
                'is_correct' => $isCorrect,
                'note_title' => $question->note?->title,
                'note_description' => $question->note?->description,
                'explain' => $question->explain,
                'answered_at' => now()->toDateTimeString(),
            ];

            // Quiz stats
            if ($isCorrect) {
                $quiz->correct += 1;
            } else {
                $quiz->wrong += 1;
            }

            // Progress calculate
            $totalQuestions = count(json_decode($quiz->sbas_id, true) ?? []);
            $answeredCount = count($answeredSba);
            // Progress cut (percentage)
            $totalAnswered = $quiz->correct + $quiz->wrong;
            // Current question index
            $quiz->current_question_index = $answeredCount;

            // Data save করা
            $quiz->remaining_sba = json_encode($remainingSba);
            $quiz->answered_sba = json_encode($answeredSba);
            $quiz->answers = json_encode($allAnswers);
            $quiz->save();

            return response()->json([
                'status' => 'success',
                'is_correct' => $isCorrect,
                'correct_option' => $correctOption,
                'selected_option' => $selectedOption,
                'quiz' => [
                    'correct' => $quiz->correct,
                    'wrong' => $quiz->wrong,
                    'remaining' => count($remainingSba),
                    'answered' => count($answeredSba),
                ],
                'question' => [
                    'id' => $question->id,
                    'question' => $question->question,
                    'explain' => $question->explain,
                    'note' => $question->sba->note,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('SBA Progress Update Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Failed to update progress. Please try again.'
            ], 500);
        }
    }

    public function review($slug)
    {
        $quiz = UserSbaProgress::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$quiz || $quiz->answers == null) {
            return redirect()->back()->with('error', 'You have not provided any answers yet.');
        }

        $answers = json_decode($quiz->answers, true);

        return view('frontend.dashboard.sba.review', compact('answers', 'quiz'));
    }

    public function finishQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:user_sba_progress,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid quiz session.');
        }

        $quiz = UserSbaProgress::where('id', $request->quiz_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return redirect()->route('sbas.review', $quiz->slug);
    }
}
