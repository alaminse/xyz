<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\UserAssessmentProgress;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssessmentController extends Controller
{
    protected $user;

    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
        // Inject authenticated user into the controller constructor
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function see_all($course)
    {
        $cour = Course::where('slug', $course)->firstOrFail();
        $progress = UserAssessmentProgress::with('assessment')
            ->where('user_id', $this->user->id)
            ->where('course_id', $cour->id)
            ->latest()
            ->get();

        return view('frontend.dashboard.assessment.see_all', compact('progress', 'cour'));
    }

    public function show($slug)
    {
        $user_assessment = UserAssessmentProgress::where('slug', $slug)->firstOrFail();

        $data = $this->getDetails($user_assessment->slug);

        // Add this line
        $assessment = Assessment::find($user_assessment->assessment_id);

        $user = Auth::user();
        $name = $user->name.'-'.$user->id;
        $course = Course::select('id', 'name', 'slug')->first($user_assessment->course_id);

        // Update compact to include assessment
        return view('frontend.dashboard.assessment.show', compact('data', 'assessment', 'course'));
    }

    // Alternative: Accessor ব্যবহার করে rank পেতে - FIXED VERSION
    public function rank($slug)
    {
        $assessment = Assessment::where('slug', $slug)->firstOrFail();

        // IMPORTANT: প্রথমে created_at তারপর achive_marks দিয়ে sort করতে হবে
        $leaderboard = UserAssessmentProgress::with('user')
            ->where('assessment_id', $assessment->id)
            ->active()
            ->orderBy('achive_marks', 'desc')   // দ্বিতীয় priority: একই সময়ে বেশি marks
            ->get();

        // Current user এর rank খুঁজুন
        $userId = auth()->id();
        $userRank = $leaderboard->firstWhere('user_id', $userId);

        // Total participants
        $totalParticipants = $leaderboard->count();

        // Top 3 winners
        $topThree = $leaderboard->take(3);

        return view('frontend.dashboard.assessment.rank', compact(
            'assessment',
            'leaderboard',
            'userRank',
            'totalParticipants',
            'topThree'
        ));
    }

    public function index(string $course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        if (! $course) {
            abort(404, 'Course not found');
        }

        $chapters = course_chapters($course, 'self_assessment');

        $progress = UserAssessmentProgress::with('assessment')
            ->where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.dashboard.assessment.index', compact(
            'course',
            'chapters',
            'progress'
        ));
    }

    public function getByChapter($course, $chapter, $lesson)
    {
        $cour = Course::select('id', 'slug', 'name')->where('slug', $course)->firstOrFail();
        $chap = Chapter::select('id', 'name', 'slug')->where('slug', $chapter)->firstOrFail();
        $less = Lesson::select('id', 'name', 'slug')->where('slug', $lesson)->firstOrFail();

        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $cour->id)
            ->first();

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // $attendedAssessmentIds = UserAssessmentProgress::where('user_id', $this->user->id)
        //     ->where('course_id', $cour->id)
        //     ->pluck('assessment_id')
        //     ->toArray();

        $assessments = Assessment::select('id', 'chapter_id', 'lesson_id', 'name', 'slug', 'start_date_time', 'end_date_time', 'total_marks', 'time', 'question_ids', 'isPaid', 'status', 'created_at', 'updated_at')
            ->where('chapter_id', $chap->id)
            ->where('lesson_id', $less->id)
            ->whereHas('courses', function ($query) use ($cour) {
                $query->where('courses.id', $cour->id);
            })
            // ->whereNotIn('id', $attendedAssessmentIds)
            ->when($enrolled, function ($query) use ($enrolled) {
                if ($enrolled->status === Status::FREETRIAL()) {
                    return $query->where('isPaid', 0);
                }

                return $query->where('status', Status::ACTIVE());
            }, function ($query) {
                return $query->where('status', Status::ACTIVE());
            })
            ->orderBy('id', 'DESC')
            ->get();

        $progress = UserAssessmentProgress::where('user_id', $this->user->id)
            ->where('course_id', $cour->id)
            ->whereHas('assessment', function ($query) use ($chap, $less) {
                $query->where('chapter_id', $chap->id)
                    ->where('lesson_id', $less->id);
            })
            ->with('assessment')
            ->latest('id')
            ->get();

        return view('frontend.dashboard.assessment.see_all', compact('assessments', 'cour', 'chap', 'less', 'progress'));
    }

    public function exam($assessment, $course = null)
    {
        $course = Course::select('id', 'name', 'slug')->where('slug', $course)->first();

        if (! $course) {
            return redirect()->back()->with('error', 'Course not found for this assessment!!');
        }

        $dateToMatch = Carbon::now()->toDateTimeString();

        // Find assessment with start_date_time check
        $assessment = Assessment::where('start_date_time', '<=', $dateToMatch)
            ->where('end_date_time', '>=', $dateToMatch)
            ->where('status', Status::ACTIVE())
            ->where('slug', $assessment)
            ->first();

        if (! $assessment) {
            return redirect()->back()->with('error', 'Exam not available!! Try Again!!');
        }

        // Check if assessment has ended
        if ($assessment->end_date_time && $assessment->end_date_time < $dateToMatch) {
            return redirect()->back()->with('error', 'This exam has already ended!!');
        }

        // Get question IDs
        $questionIds = $assessment->question_ids;

        if (empty($questionIds)) {
            return redirect()->back()->with('error', 'No questions found for this assessment.');
        }

        // Decode question IDs (if stored as JSON)
        $decodedIds = is_string($questionIds) ? json_decode($questionIds, true) : $questionIds;

        if (empty($decodedIds)) {
            return redirect()->back()->with('error', 'Invalid question data.');
        }

        // Get questions in random order
        $questions = AssessmentQuestion::whereIn('id', $decodedIds)
            ->inRandomOrder()
            ->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available for this assessment.');
        }

        return view('frontend.dashboard.assessment.test', compact('course', 'assessment', 'questions'));
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|exists:courses,id',
            'assessment_id' => 'required|integer|exists:assessments,id',
            'selected_options' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $validated = $validator->validated();
        $assessment = Assessment::findOrFail($validated['assessment_id']);
        $selectedOptions = json_decode($validated['selected_options'], true);

        $achieved_marks = 0;
        $questionDetails = [];
        $answeredQuestionIds = [];

        foreach ($selectedOptions as $index => $value) {
            $questionDetail = [];
            $question = AssessmentQuestion::findOrFail($value['id']);
            $questionData = json_decode($question->questions);

            $answeredQuestionIds[] = $question->id;

            $questionDetail['question_id'] = $question->id;
            $questionDetail['explanation'] = $question->explanation ?? '';
            $questionDetail['question'] = $questionData->question;
            $questionDetail['question_type'] = $question->question_type;
            $questionDetail['mark_per_question'] = $question->mark_per_question;
            $questionDetail['minus_mark'] = $question->minus_mark;

            $type = $question->question_type;

            if ($type === 'sba') {
                $correctOption = $questionData->correct_option ?? null;
                $userAnswer = $value['option'] ?? null;

                $questionDetail['options'] = [
                    'option1' => $questionData->option1 ?? '',
                    'option2' => $questionData->option2 ?? '',
                    'option3' => $questionData->option3 ?? '',
                    'option4' => $questionData->option4 ?? '',
                    'option5' => $questionData->option5 ?? '',
                    'correct_option' => $correctOption,
                    'user_option' => $userAnswer,
                ];

                // Marking
                if (! empty($userAnswer) && ! empty($correctOption)) {
                    if ($userAnswer === $correctOption) {
                        $achieved_marks += $question->mark_per_question;
                        $questionDetail['is_correct'] = true;
                        $questionDetail['marks_obtained'] = $question->mark_per_question;
                    } else {
                        $achieved_marks -= $question->minus_mark;
                        $questionDetail['is_correct'] = false;
                        $questionDetail['marks_obtained'] = -$question->minus_mark;
                    }
                } else {
                    $questionDetail['is_correct'] = false;
                    $questionDetail['marks_obtained'] = 0;
                }

            } elseif ($type === 'mcq') {
                $questionDetail['options'] = [
                    'option1' => $questionData->option1 ?? '',
                    'option2' => $questionData->option2 ?? '',
                    'option3' => $questionData->option3 ?? '',
                    'option4' => $questionData->option4 ?? '',
                    'option5' => $questionData->option5 ?? '',
                    'answers1' => $questionData->answers1 ?? '',
                    'answers2' => $questionData->answers2 ?? '',
                    'answers3' => $questionData->answers3 ?? '',
                    'answers4' => $questionData->answers4 ?? '',
                    'answers5' => $questionData->answers5 ?? '',
                ];

                $totalCorrect = 0;
                $totalWrong = 0;
                $totalAnswered = 0;
                $totalAvailableOptions = 0;

                // প্রতিটি option check করুন
                foreach (range(1, 5) as $optionIndex) {
                    $optionKey = 'option'.$optionIndex;
                    $answerKey = 'answers'.$optionIndex;

                    // যদি option exist না করে, skip করুন
                    if (empty($questionData->{$optionKey})) {
                        continue;
                    }

                    $totalAvailableOptions++;

                    $userSelection = $value['options']['option'.$optionIndex] ?? null;
                    $correctAnswer = $questionData->{$answerKey} == '1' ? 'true' : 'false';

                    $questionDetail['options']['user_option'.$optionIndex] = $userSelection;

                    // যদি user উত্তর দিয়ে থাকে
                    if (isset($userSelection) && ! empty($userSelection)) {
                        $totalAnswered++;

                        if ($userSelection == $correctAnswer) {
                            $totalCorrect++;
                        } else {
                            $totalWrong++;
                        }
                    }
                }

                // প্রতিটি option এর জন্য marks calculate
                $marksPerOption = $question->mark_per_question; // প্রতিটি option এর value
                $minusMarkPerOption = $question->minus_mark; // প্রতিটি ভুল এর জন্য minus

                $questionMarks = ($totalCorrect * $marksPerOption) - ($totalWrong * $minusMarkPerOption);
                $achieved_marks += $questionMarks;

                $questionDetail['is_correct'] = $totalCorrect == $totalAvailableOptions && $totalWrong == 0;
                $questionDetail['marks_obtained'] = round($questionMarks, 2);
                $questionDetail['total_correct'] = $totalCorrect;
                $questionDetail['total_wrong'] = $totalWrong;
                $questionDetail['total_answered'] = $totalAnswered;
                $questionDetail['total_options'] = $totalAvailableOptions;
                $questionDetail['marks_per_option'] = round($marksPerOption, 2);
            }

            $questionDetails[] = $questionDetail;
        }

        // Prevent negative total marks
        $achieved_marks = max(0, $achieved_marks);

        // Calculate percentage
        $percentage = $assessment->total_marks > 0
            ? round(($achieved_marks / $assessment->total_marks) * 100, 2)
            : 0;

        $user_assessment = UserAssessmentProgress::updateOrCreate(
            [
                'course_id'     => $validated['course_id'],
                'user_id'       => $this->user->id,
                'assessment_id' => $assessment->id,
            ],
            [
                'slug'                  => checkSlug('user_assessment_progress'),
                'total_marks'           => $assessment->total_marks,
                'achive_marks'          => $achieved_marks,
                'percentage'            => $percentage,
                'question_ids'          => $assessment->question_ids,
                'remaining_question'    => json_encode([]),
                'answered_question'     => json_encode($answeredQuestionIds),
                'selectedOptions'       => json_encode($selectedOptions),
                'details'               => json_encode($questionDetails),
                'current_index'         => count($selectedOptions) - 1,
            ]
        );

        return redirect()->route('assessments.show', ['slug' => $user_assessment->slug]);
    }

    private function getDetails($slug)
    {
        $progress = UserAssessmentProgress::with(['course', 'user'])->where('slug', $slug)->first();
        if (! $progress) {
            throw new \Exception("Progress not found for slug: $slug");
        }

        $user_answer = [];
        if ($progress->details != null) {
            $user_answer = json_decode($progress->details, true);
        }

        $assessment = Assessment::find($progress->assessment_id);
        if (! $assessment) {
            throw new \Exception("Assessment not found for ID: {$progress->assessment_id}");
        }

        // Calculate rank
        $allProgress = UserAssessmentProgress::where('assessment_id', $progress->assessment_id)
            ->orderByDesc('achive_marks')
            ->get();

        $rank = $allProgress->search(function ($item) use ($progress) {
            return $item->id === $progress->id;
        }) + 1;

        return [
            'course' => $progress->course?->slug,
            'slug' => $progress->slug,
            'student_name' => $progress->user?->name,
            'name' => $assessment->name,
            'achive_marks' => $progress->achive_marks,
            'total_marks' => $assessment->total_marks,
            'time' => $assessment->time,
            'rank' => $rank,
            'details' => $user_answer,
            'assessment' => $assessment, // Add this
        ];
    }

    public function print($slug)
    {
        // Increase execution time and memory limit
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');

        $data = $this->getDetails($slug);
        $user = Auth::user();
        $name = $user->name.'-'.$user->id;

        try {
            // Load the view and generate the PDF
            $pdf = Pdf::loadView('frontend.dashboard.assessment.print', compact('data'));

            $pdf->setPaper('A4', 'portrait');
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->set_option('isRemoteEnabled', true);

            return $pdf->stream('watermarked_pdf.pdf');

        } catch (\Exception $e) {
            Log::error('PDF Generation Error: '.$e->getMessage());

            return back()->with('error', 'Failed to generate PDF. Please try again.');
        }
    }
}
