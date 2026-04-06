<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\OspeStation;
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

        $chapters = course_chapters($course, 'ospe');

        $recentOspeStations = OspeStation::select('id', 'chapter_id', 'lesson_id', 'isPaid', 'status')
            ->whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
            ->with(['chapter', 'lesson'])
            ->latest()
            ->take(10)
            ->get();

        return view('frontend.dashboard.ospe.index', compact(
            'chapters',
            'course',
            'recentOspeStations',
            'isLocked'
        ));
    }

    public function test($course_slug, $chapter, $lesson = null)
    {
        $course  = Course::select('id', 'slug', 'name')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id', 'slug', 'name')->where('slug', $chapter)->firstOrFail();
        $lesson  = $lesson ? Lesson::select('id', 'slug', 'name')->where('slug', $lesson)->firstOrFail() : null;

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // ✅ Locked if FREETRIAL
        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        // ✅ Select isPaid from ospe_stations
        $ospeQuery = OspeStation::select('id', 'chapter_id', 'lesson_id', 'isPaid', 'status', 'image')
            ->whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE());

        if ($lesson) {
            $ospeQuery->where('lesson_id', $lesson->id);
        }

        $ospe = $ospeQuery->first();

        if (! $ospe) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        // ✅ isPaid from ospe_stations table
        $isPaid = (bool) $ospe->isPaid;

        $questions = $ospe->questions;

        if ($questions && $questions->count() > 0) {
            $questions = $questions->shuffle();
        } else {
            return redirect()->back()->with('error', 'No questions found!');
        }

        return view('frontend.dashboard.ospe.test', compact(
            'ospe',
            'course_slug',
            'questions',
            'isLocked',
            'isPaid'    // 👈 added
        ));
    }
}
