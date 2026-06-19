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
            ->get();

        return view('frontend.dashboard.written-assessment.index', compact(
            'chapters',
            'course',
            'progress',
            'isLocked'
        ));
    }

    public function details($course_slug, $chapter, $lesson = null)
    {
        $course  = Course::select('id', 'slug')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id', 'slug')->where('slug', $chapter)->firstOrFail();
        $lesson  = $lesson ? Lesson::select('id', 'slug')->where('slug', $lesson)->firstOrFail() : null;

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $writtenQuery = WrittenAssessment::select('id', 'slug', 'isPaid', 'status')
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id));

        if ($lesson) {
            $writtenQuery->where('lesson_id', $lesson->id);
        }

        $written = $writtenQuery->latest()->get();

        if ($written->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        return view('frontend.dashboard.written-assessment.lists', compact(
            'written',
            'course_slug',
            'isLocked'
        ));
    }

    public function single_details($slug, $query = null)
    {
        $assessment = WrittenAssessment::with('questionGroups')
            ->where('slug', $slug)
            ->firstOrFail();

        $course      = $assessment->courses->first();
        $course_slug = $course?->slug;

        $enrolled = $this->getEnrollment($course?->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;
        $isPaid   = (bool) $assessment->isPaid;

        if ($isPaid && $isLocked) {
            return redirect()->route('courses.checkout', ['course' => $course_slug])
                ->with('error', 'This is premium content. Please upgrade your plan.');
        }

        // Parse questions for each group
        $questionGroups = $assessment->questionGroups->map(function ($group) {
            $group->questions = is_array($group->questions)
                ? $group->questions
                : json_decode($group->questions, true) ?? [];
            return $group;
        });

        WrittenAssessmentProgress::firstOrCreate([
            'user_id'    => $this->user->id,
            'course_id'  => $course->id,
            'chapter_id' => $assessment->chapter_id,
            'lesson_id'  => $assessment->lesson_id,
        ]);

        return view('frontend.dashboard.written-assessment.details', compact(
            'assessment',
            'questionGroups',
            'query',
            'course_slug',
            'isLocked',
            'isPaid'
        ));
    }
}
