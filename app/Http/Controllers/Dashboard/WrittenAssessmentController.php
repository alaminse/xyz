<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\WrittenAssessment;
use App\Models\WrittenAssessmentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WrittenAssessmentController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    private function getEnrollment($courseId)
    {
        return EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $courseId)
            ->first();
    }

    public function index($course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $chapters = course_chapters($course, 'written');

        $progress = WrittenAssessmentProgress::with('chapter', 'lesson')
            ->where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->get()
            ->groupBy(fn ($p) => $p->chapter_id.'_'.$p->lesson_id);

        return view('frontend.dashboard.written-assessment.index', compact(
            'chapters',
            'course',
            'progress',
            'isLocked'
        ));
    }

    public function details($course_slug, $chapter, $lesson = null)
    {
        $course = Course::select('id', 'slug', 'name')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id', 'slug', 'name')->where('slug', $chapter)->firstOrFail();
        $lesson = $lesson ? Lesson::select('id', 'slug', 'name')->where('slug', $lesson)->firstOrFail() : null;

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $assessmentQuery = WrittenAssessment::with('questionGroups')
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id));

        if ($lesson) {
            $assessmentQuery->where('lesson_id', $lesson->id);
        }

        $assessments = $assessmentQuery->latest()->get();

        if ($assessments->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        // Flatten all question groups across all assessments into one list
        $questionGroups = $assessments->flatMap(function ($assessment) {
            return $assessment->questionGroups->map(function ($group) use ($assessment) {
                $group->questions = is_array($group->questions)
                    ? $group->questions
                    : json_decode($group->questions, true) ?? [];
                $group->isPaid = $assessment->isPaid;

                return $group;
            });
        });

        if ($questionGroups->isEmpty()) {
            return redirect()->back()->with('error', 'No questions found!');
        }

        $totalGroups = $questionGroups->count();

        // Check if 100% complete — if so reset progress so user starts fresh
        $completedCount = WrittenAssessmentProgress::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->where('chapter_id', $chapter->id)
            ->where('lesson_id', $lesson?->id)
            ->whereNotNull('question_group_id')
            ->count();

        if ($completedCount >= $totalGroups && $totalGroups > 0) {
            WrittenAssessmentProgress::where('user_id', $this->user->id)
                ->where('course_id', $course->id)
                ->where('chapter_id', $chapter->id)
                ->where('lesson_id', $lesson?->id)
                ->whereNotNull('question_group_id')
                ->delete();

            $completedGroupIds = [];
            $completedGroups = 0;
        } else {
            $completedGroupIds = WrittenAssessmentProgress::where('user_id', $this->user->id)
                ->where('course_id', $course->id)
                ->where('chapter_id', $chapter->id)
                ->where('lesson_id', $lesson?->id)
                ->whereNotNull('question_group_id')
                ->pluck('question_group_id')
                ->toArray();

            $completedGroups = count($completedGroupIds);
        }

        // isPaid — true if ANY assessment in this lesson is paid
        $isPaid = $assessments->contains(fn ($a) => (bool) $a->isPaid);

        return view('frontend.dashboard.written-assessment.details', compact(
            'questionGroups',
            'completedGroupIds',
            'totalGroups',
            'completedGroups',
            'course',
            'course_slug',
            'chapter',
            'lesson',
            'isLocked',
            'isPaid'
        ));
    }

    public function saveProgress(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'chapter_id' => 'required|integer',
            'lesson_id' => 'nullable|integer',
            'question_group_id' => 'required|integer',
        ]);

        WrittenAssessmentProgress::updateOrCreate(
            [
                'user_id' => $this->user->id,
                'course_id' => $request->course_id,
                'chapter_id' => $request->chapter_id,
                'lesson_id' => $request->lesson_id,
                'question_group_id' => $request->question_group_id,
            ],
            ['status' => 1]
        );

        $completed = WrittenAssessmentProgress::where('user_id', $this->user->id)
            ->where('course_id', $request->course_id)
            ->where('chapter_id', $request->chapter_id)
            ->where('lesson_id', $request->lesson_id)
            ->whereNotNull('question_group_id')
            ->count();

        $total = WrittenAssessment::with('questionGroups')
            ->where('chapter_id', $request->chapter_id)
            ->where('lesson_id', $request->lesson_id)
            ->get()
            ->sum(fn ($a) => $a->questionGroups->count());

        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'total' => $total,
            'percentage' => $percentage,
        ]);
    }

    public function index_progress($course_id)
    {
        $course = Course::select('id', 'slug')->findOrFail($course_id);

        $progressData = WrittenAssessmentProgress::with('chapter', 'lesson')
            ->where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->whereNotNull('question_group_id')
            ->get()
            ->groupBy(fn ($p) => $p->chapter_id.'_'.$p->lesson_id)
            ->map(function ($records) {
                $first = $records->first();
                $completed = $records->count();

                $total = WrittenAssessment::with('questionGroups')
                    ->where('chapter_id', $first->chapter_id)
                    ->where('lesson_id', $first->lesson_id)
                    ->get()
                    ->sum(fn ($a) => $a->questionGroups->count());

                $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

                return [
                    'chapter_name' => $first->chapter->name ?? '-',
                    'lesson_name' => $first->lesson->name ?? '-',
                    'chapter_slug' => $first->chapter->slug ?? '',
                    'lesson_slug' => $first->lesson->slug ?? '',
                    'completed' => $completed,
                    'total' => $total,
                    'percentage' => $percentage,
                ];
            })
            ->values();

        return response()->json(['success' => true, 'progress' => $progressData]);
    }
}
