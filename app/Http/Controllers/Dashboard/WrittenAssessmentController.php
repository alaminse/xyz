<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\WrittenAssessment;
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

        // ✅ Locked if FREETRIAL
        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $chapters = course_chapters($course, 'written');

        $latestQuery = WrittenAssessment::select('id', 'slug', 'question', 'isPaid', 'status')
            ->whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
            ->where('status', Status::ACTIVE());

        // ✅ Free trial users only see free items in recent list
        if ($isLocked) {
            $latestQuery->where('isPaid', 0);
        }

        $latest = $latestQuery->orderBy('id', 'desc')->take(5)->get();

        return view('frontend.dashboard.written-assessment.index', compact(
            'chapters',
            'latest',
            'course',
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

        // ✅ Locked if FREETRIAL
        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $writtenQuery = WrittenAssessment::select('id', 'slug', 'question', 'isPaid', 'status')
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id));

        if ($lesson) {
            $writtenQuery->where('lesson_id', $lesson->id);
        }

        // ✅ Show all questions — lock is applied per item in blade
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
        $assessment = WrittenAssessment::where('slug', $slug)->firstOrFail();

        $course = $assessment->courses->first();
        $course_slug = $course?->slug;

        // ✅ Enrollment check
        $enrolled = $this->getEnrollment($course?->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // ✅ Locked if FREETRIAL
        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        // ✅ isPaid from written_assessments
        $isPaid = (bool) $assessment->isPaid;

        // 🔒 Block direct URL access if paid + locked
        if ($isPaid && $isLocked) {
            return redirect()->route('courses.checkout', ['course' => $course_slug])
                ->with('error', 'This is premium content. Please upgrade your plan.');
        }

        return view('frontend.dashboard.written-assessment.details', compact(
            'assessment',
            'query',
            'course_slug',
            'isLocked',
            'isPaid'
        ));
    }
}
