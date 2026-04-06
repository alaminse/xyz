<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\MockViva;
use App\Models\MockVivaProgress;
use Illuminate\Support\Facades\Auth;

class MockVivaController extends Controller
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

        $chapters = course_chapters($course, 'mock_viva');

        $progress = MockVivaProgress::with('chapter', 'lesson')
            ->where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->get();

        return view('frontend.dashboard.mock-viva.index', compact(
            'chapters',
            'course',
            'progress',
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

        // ✅ Get mock with isPaid
        $mockQuery = MockViva::select('id', 'chapter_id', 'lesson_id', 'isPaid', 'status')
            ->whereHas('courses', fn ($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE());

        if ($lesson) {
            $mockQuery->where('lesson_id', $lesson->id);
        }

        $mock = $mockQuery->first();

        if (! $mock) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        // ✅ isPaid from mock_vivas table
        $isPaid = (bool) $mock->isPaid;

        $questions = $mock->questions;

        if ($questions && $questions->count() > 0) {
            $questions = $questions->shuffle();
        } else {
            return redirect()->back()->with('error', 'No questions found!');
        }

        MockVivaProgress::firstOrCreate([
            'user_id'    => $this->user->id,
            'course_id'  => $course->id,
            'chapter_id' => $chapter->id,
            'lesson_id'  => $lesson?->id,
        ]);

        return view('frontend.dashboard.mock-viva.test', compact(
            'mock',
            'course_slug',
            'questions',
            'isLocked',
            'isPaid'    // 👈 added
        ));
    }
}
