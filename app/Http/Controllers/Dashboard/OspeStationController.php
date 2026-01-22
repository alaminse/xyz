<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\OspeStation;
use App\Models\VerbalAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OspeStationController extends Controller
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

        $chapters = course_chapters($course, 'ospe');

        $recentOspeStations = OspeStation::whereHas('courses', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with(['chapter', 'lesson'])
            ->latest()
            ->take(10)
            ->get();

        return view('frontend.dashboard.ospe.index', compact('chapters', 'course', 'recentOspeStations'));
    }

    public function test($course_slug, $chapter, $lesson = null)
    {
        $course = Course::select('id')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id')->where('slug', $chapter)->firstOrFail();
        $lesson = $lesson ? Lesson::select('id')->where('slug', $lesson)->firstOrFail() : null;

        $data = OspeStation::whereHas('courses', fn($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id);
        if ($lesson) {
            $data->where('lesson_id', $lesson->id);
        }
        $data->where('status', Status::ACTIVE());
        $ospe = $data->first();

        if (!$ospe) return redirect()->back()->with('error', 'This lesson is Empty!');
        $questions = $ospe->questions;

        if ($questions && $questions->count() > 0) {
            $questions = $questions->shuffle();
        } else {
            return redirect()->back()->with('error', 'No questions found!');
        }

        return view('frontend.dashboard.ospe.test', compact('ospe', 'course_slug', 'questions'));
    }


}
