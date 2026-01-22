<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Mcq;
use App\Enums\Status;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Chapter;
use App\Models\McqAnswer;
use App\Models\EnrollUser;
use App\Models\McqQuestion;
use Illuminate\Http\Request;
use App\Models\UserMcqProgress;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class McqController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function index($course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        if (! $course) {
            abort(404, 'Course not found');
        }

        $chapters = course_chapters($course, 'mcq');

        $enrolledCourseIds = EnrollUser::where('user_id', $this->user->id)
            ->pluck('course_id');

        $progress = UserMcqProgress::where('user_id', $this->user->id)
            ->whereIn('course_id', $enrolledCourseIds)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.dashboard.mcq.index', compact('course', 'chapters', 'progress'));
    }

    public function getTest($course_slug, $chapter, $lesson = null)
    {
        // Course, Chapter, Lesson খোঁজা
        $course = Course::where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::where('slug', $chapter)->firstOrFail();
        $lesson = Lesson::where('slug', $lesson)->firstOrFail();

        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $mcqQuery = Mcq::query()
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id)
            );

        if ($lesson) {
            $mcqQuery->where('lesson_id', $lesson->id);
        }

        if ($enrolled->status === Status::FREETRIAL()) {
            $mcqQuery->where('isPaid', 0);
        }

        $mcq = $mcqQuery->first();
        if (! $mcq || $mcq->questions->isEmpty()) {
            return redirect()->back()->with('error', 'No flash card or questions available for this topic.');
        }

        // MCQ Questions load করা
        $mcqQuestions = McqQuestion::with('note')
            ->where('mcq_id', $mcq->id)
            ->get();

        if ($mcqQuestions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        // MCQ Question IDs collect করা
        $questionIds = $mcqQuestions->pluck('id')->toArray();

        // User Progress খোঁজা বা তৈরি করা
        $quiz = UserMcqProgress::firstOrNew([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'chapter_id' => $chapter->id,
            'lesson_id' => $lesson->id,
        ]);

        if ($quiz->exists) {
            // নতুন Questions যোগ করা
            $existingIds = json_decode($quiz->mcq_ids, true) ?? [];
            $newQuestions = array_diff($questionIds, $existingIds);

            if (! empty($newQuestions)) {
                $remainingIds = json_decode($quiz->remaining_mcq, true) ?? [];
                $quiz->remaining_mcq = json_encode(array_unique(array_merge($remainingIds, $newQuestions)));
                $quiz->mcq_ids = json_encode(array_unique(array_merge($existingIds, $newQuestions)));
                $quiz->total = count(json_decode($quiz->mcq_ids, true));
            }
        } else {
            // নতুন quiz তৈরি করা
            $quiz->fill([
                'slug' => checkSlug('user_mcq_progress'),
                'total' => count($questionIds),
                'current_question_index' => 0,
                'mcq_ids' => json_encode($questionIds),
                'remaining_mcq' => json_encode($questionIds),
                'answered_mcq' => json_encode([]),
                'progress' => 0,
                'progress_cut' => 0,
                'answers' => json_encode([]),
                'correct' => 0,
                'wrong' => 0,
            ]);
        }

        $quiz->save();

        // বাকি Questions বের করা
        $remainingQuestionIds = json_decode($quiz->remaining_mcq, true) ?? [];

        // ✅ যদি কোনো প্রশ্ন না থাকে তাহলে রিসেট করে আবার শুরু করা
        if (empty($remainingQuestionIds)) {
            $quiz->update([
                'total' => count($questionIds),
                'current_question_index' => 0,
                'remaining_mcq' => json_encode($questionIds),
                'answered_mcq' => json_encode([]),
                'progress' => 0,
                'progress_cut' => 0,
                'answers' => json_encode([]),
                'correct' => 0,
                'wrong' => 0,
            ]);

            $remainingQuestionIds = $questionIds;
        }

        // Enrolled user check করা
        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->first();

        // শুধু remaining Questions load করা
        $mcqQuestionsFiltered = McqQuestion::with('note')
            ->whereIn('id', $remainingQuestionIds)
            ->when($enrolled && $enrolled->status === Status::FREETRIAL(), function ($query) {
                return $query->whereHas('mcq', function ($q) {
                    $q->where('isPaid', 0);
                });
            })
            ->inRandomOrder()
            ->get();

        if ($mcqQuestionsFiltered->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available.');
        }

        return view('frontend.dashboard.mcq.test', compact('mcqQuestionsFiltered', 'quiz', 'chapter', 'lesson', 'course', 'mcq'));
    }

    public function updateProgress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quiz_id'           => 'required|exists:user_mcq_progress,id',
                'mcq_id'            => 'required|exists:mcqs,id',
                'question_id'       => 'required|exists:mcq_questions,id',
                'answers'           => 'required|array',
                'answers.option1'   => 'nullable|in:0,1',
                'answers.option2'   => 'nullable|in:0,1',
                'answers.option3'   => 'nullable|in:0,1',
                'answers.option4'   => 'nullable|in:0,1',
                'answers.option5'   => 'nullable|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }

            $validated = $validator->validated();

            // Quiz খোঁজা এবং ownership verify করা
            $quiz = UserMcqProgress::where('id', $validated['quiz_id'])
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Question এবং MCQ load করা
            $question = McqQuestion::with('note')->findOrFail($validated['question_id']);
            $mcq = Mcq::findOrFail($validated['mcq_id']);

            // User এর answers (option1, option2, etc.)
            $userAnswers = $validated['answers']; // [option1 => 1, option2 => 0, ...]

            $correctCount = 0;
            $totalOptions = count($userAnswers);
            $answerResults = [];

            // প্রতিটি answer check করা
            foreach ($userAnswers as $optionKey => $selectedValue) {
                // option1 => answer1, option2 => answer2
                $answerKey = str_replace('option', 'answer', $optionKey);
                $correctAnswer = $question->$answerKey;

                if ((int)$selectedValue === (int)$correctAnswer) {
                    $correctCount++;
                    $answerResults[$optionKey] = [
                        'option_text'   => $question->$optionKey,
                        'selected'      => (int)$selectedValue,
                        'correct'       => (int)$correctAnswer,
                        'is_correct'    => true
                    ];
                } else {
                    $answerResults[$optionKey] = [
                        'option_text'   => $question->$optionKey,
                        'selected'      => (int)$selectedValue,
                        'correct'       => (int)$correctAnswer,
                        'is_correct'    => false
                    ];
                }
            }

            // Progress data বের করা
            $remainingMcq = json_decode($quiz->remaining_mcq, true) ?? [];
            $answeredMcq = json_decode($quiz->answered_mcq, true) ?? [];
            $allAnswers = json_decode($quiz->answers, true) ?? [];

            // Answer key তৈরি করা
            $answerKey = $validated['question_id'];

            // Check if already answered
            if (in_array($answerKey, $answeredMcq)) {
                return response()->json([
                    'error' => 'You have already answered this question.'
                ], 422);
            }

            // Remaining থেকে এই question remove করা
            $remainingMcq = array_values(array_diff($remainingMcq, [$answerKey]));

            // Answered এ যোগ করা
            $answeredMcq[] = $answerKey;

            // Complete answer data store করা
            $allAnswers[] = [
                'mcq_id'            => $mcq->id,
                'question_id'       => $question->id,
                'question'          => $question->question,
                'answers'           => $answerResults,
                'correct_count'     => $correctCount,
                'total_options'     => $totalOptions,
                'note_title'        => $question->note?->title,
                'note_description'  => $question->note?->description,
                'explain'           => $question->explain,
                'answered_at'       => now()->toDateTimeString(),
            ];

            // Quiz stats আপডেট করা
            $quiz->correct += $correctCount;
            $quiz->wrong += ($totalOptions - $correctCount);

            // Progress calculate করা
            $totalQuestions = count(json_decode($quiz->mcq_ids, true) ?? []);
            $answeredCount = count($answeredMcq);
            $quiz->progress = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100, 2) : 0;

            // Progress cut (সঠিক উত্তরের percentage)
            $totalOptionsAnswered = $quiz->correct + $quiz->wrong;
            $quiz->progress_cut = $totalOptionsAnswered > 0 ? round(($quiz->correct / $totalOptionsAnswered) * 100, 2) : 0;

            // Current question index আপডেট করা
            $quiz->current_question_index = $answeredCount;

            // Data save করা
            $quiz->remaining_mcq = json_encode($remainingMcq);
            $quiz->answered_mcq = json_encode($answeredMcq);
            $quiz->answers = json_encode($allAnswers);
            $quiz->save();

            return response()->json([
                'status'            => 'success',
                'correct_count'     => $correctCount,
                'total_options'     => $totalOptions,
                'answer_results'    => $answerResults,
                'quiz'              => [
                    'progress'          => $quiz->progress,
                    'progress_cut'      => $quiz->progress_cut,
                    'correct'           => $quiz->correct,
                    'wrong'             => $quiz->wrong,
                    'remaining'         => count($remainingMcq),
                    'answered'          => count($answeredMcq),
                ],
                'question'          => [
                    'id'                => $question->id,
                    'question'          => $question->question,
                    'explain'           => $question->explain,
                    'note'              => $question->note,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('MCQ Progress Update Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Failed to update progress. Please try again.'
            ], 500);
        }
    }

    public function review($slug)
    {
        $quiz = UserMcqProgress::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($quiz) {
            $questions = json_decode($quiz->answers);
            $data['total'] = $quiz->progress_cut;
            $data['correct'] = $quiz->correct;

            return view('frontend.dashboard.mcq.review', compact('questions', 'data', 'quiz'));
        }

        return redirect()->back()->with('error', 'Something went wrong!! Try Again');
    }

    public function finishQuiz(Request $request)
    {
        if (empty($request->mcq_ids)) {
            return response()->json(['status' => 'error', 'Mcq Not Found']);
        }

        $questions = json_decode($request->mcq_ids, true);
        if (! is_array($questions)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['answers' => 'The answers field must be a valid JSON array.'],
            ]);
        }

        $answers = [];
        $correct = 0;

        foreach ($questions as $key => $answer) {
            // ✅ UPDATED: Load 'questions' relationship (hasMany)
            $mcq = Mcq::with(['questions', 'note'])->findOrFail($answer['mcq_id']);

            // ✅ NEW: Get specific question
            $question = $mcq->questions()->where('id', $answer['question_id'])->firstOrFail();

            $op = json_decode($request->selectedOptions, true);

            $answers[] = (object) [
                'mcq_id' => $mcq->id,
                'question_id' => $question->id,
                'question' => $question->question,
                'option1' => $question->option1,
                'answer1' => $question->answer1,
                'option2' => $question->option2,
                'answer2' => $question->answer2,
                'option3' => $question->option3,
                'answer3' => $question->answer3,
                'option4' => $question->option4,
                'answer4' => $question->answer4,
                'option5' => $question->option5,
                'answer5' => $question->answer5,
                'options' => (object) $op[$question->id],
                'note_title' => $mcq->note?->title,
                'note_description' => $mcq->note?->description,
                'explain' => $question->explain,
            ];
        }
        $data['total'] = count($questions) * 5;
        $data['correct'] = $correct;

        return view('frontend.dashboard.mcq.review', compact('answers', 'data'));
    }
}
