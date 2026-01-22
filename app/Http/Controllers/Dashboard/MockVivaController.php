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
use Illuminate\Http\Request;
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


        $isPaid = $this->isPaid(course: $course->id);
        if ($isPaid == 9) return redirect()->back()->with('error', 'Something is Wrong!!');

        $chapters = course_chapters($course, 'mock_viva');

        $progress = MockVivaProgress::with('chapter', 'lesson')
            ->where('user_id', $this->user->id)
            ->where('course_id', $course->id)
            ->get();
        return view('frontend.dashboard.mock-viva.index', compact('chapters', 'course', 'progress'));
    }

    public function test($course_slug, $chapter, $lesson = null)
    {
        $course = Course::select('id')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id')->where('slug', $chapter)->firstOrFail();
        $lesson = $lesson ? Lesson::select('id')->where('slug', $lesson)->firstOrFail() : null;

        $data = MockViva::whereHas('courses', fn($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id);
        if ($lesson) {
            $data->where('lesson_id', $lesson->id);
        }
        $data->where('status', Status::ACTIVE());
        $mock = $data->first();

        if (!$mock) return redirect()->back()->with('error', 'This lesson is Empty!');
        $questions = $mock->questions;

        if ($questions && $questions->count() > 0) {
            $questions = $questions->shuffle();
        } else {
            return redirect()->back()->with('error', 'No questions found!');
        }

        MockVivaProgress::firstOrCreate([
            'user_id' => $this->user->id,
            'course_id' => $course->id,
            'chapter_id' => $chapter->id,
            'lesson_id' => $lesson ? $lesson->id : null,
        ]);

        return view('frontend.dashboard.mock-viva.test', compact('mock', 'course_slug', 'questions'));
    }


}
