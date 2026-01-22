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

    private function isPaid($course)
    {
        $enrolled = EnrollUser::where('user_id', Auth::user()->id)
            ->where('course_id', $course)
            ->first();

        return $enrolled->status == Status::ACTIVE()->value
            ? 1
            : ($enrolled->status == Status::FREETRIAL()->value ? 0 : 9);

    }

    public function index($course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        if (! $course) {
            abort(404, 'Course not found');
        }

        $chapters = course_chapters($course, 'written');

        $isPaid = $this->isPaid(course: $course->id);

        $latest = WrittenAssessment::select('id', 'slug', 'question', 'status')
            ->whereHas('courses', fn ($q) => $q->where('course_id', $course->id));
        if ($isPaid == 0) {
            $latest = $latest->where('isPaid', $isPaid);
        }
        $latest = $latest->orderBy('id', 'desc')->where('status', Status::ACTIVE())->take(5)->get();

        return view('frontend.dashboard.written-assessment.index', compact('chapters', 'latest', 'course'));
    }

    public function details($course_slug, $chapter, $lesson = null)
    {
        $course = Course::select('id')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id')->where('slug', $chapter)->firstOrFail();
        $lesson = $lesson ? Lesson::select('id')->where('slug', $lesson)->firstOrFail() : null;

        $enrolled = EnrollUser::where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->first();

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $written = WrittenAssessment::query()
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE())
            ->whereHas('courses', fn ($q) => $q->where('courses.id', $course->id)
            );

        if ($lesson) {
            $written->where('lesson_id', $lesson->id);
        }

        if ($enrolled->status === Status::FREETRIAL()) {
            $written->where('isPaid', 0);
        }

        // $data = WrittenAssessment::whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
        //     ->where('chapter_id', $chapter->id);
        // if ($lesson) {
        //     $data->where('lesson_id', $lesson->id);
        // }
        // $data->where('status', Status::ACTIVE());

        $written = $written->latest()->get();

        if ($written->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        return view('frontend.dashboard.written-assessment.lists', compact('written', 'course_slug'));
    }

    public function single_details($slug, $query = null)
    {
        $assessment = WrittenAssessment::where('slug', $slug)->first();
        $course_slug = $assessment->courses->first()->slug ?? null;

        return view('frontend.dashboard.written-assessment.details', compact('assessment', 'query', 'course_slug'));
    }
}
