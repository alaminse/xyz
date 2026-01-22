<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
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

        $chapters = course_chapters($course, 'note');

        $latest = Note::select('id', 'slug', 'title', 'status')
        ->whereHas('courses', function ($q) use ($course) {
            $q->where('courses.id', $course->id);
        });

        if ($isPaid == 0) {
            $latest = $latest->where('isPaid', $isPaid);
        }

        $latest = $latest->orderBy('id', 'desc')->where('status', Status::ACTIVE())->take(5)->get();

        return view('frontend.dashboard.notes.index', compact('chapters', 'latest', 'course'));
    }

    public function details($course_slug, $chapter_slug, $lesson_slug = null)
    {
        // Course
        $course = Course::select('id', 'parent_id', 'slug', 'name')->where('slug', $course_slug)->firstOrFail();

        // Chapter
        $chapter = Chapter::where('slug', $chapter_slug)->firstOrFail();

        // Lesson (optional)
        $lesson = Lesson::where('slug', $lesson_slug)->firstOrFail();

        $notesQuery = Note::select('id', 'chapter_id', 'lesson_id', 'title', 'slug')->where('chapter_id', $chapter->id)
            ->where('status', 1)
            ->whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            });

        // If lesson exists
        if ($lesson) {
            $notesQuery->where('lesson_id', $lesson->id);
        }

        $notes = $notesQuery->get();

        // Empty check
        if ($notes->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is empty!');
        }

        return view('frontend.dashboard.notes.lists', compact(
            'notes',
            'course_slug'
        ));
    }

    public function single_details($slug, $query = null)
    {
        $note = Note::where('slug', $slug)->first();

        return view('frontend.dashboard.notes.details', compact('note', 'query'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $course_slug = $request->input('course_slug');

        // Search notes based on attributes (adjust fields as needed)
        $notes = Note::where('title', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->get(); // Adjust pagination as needed

        return view('frontend.dashboard.notes.lists', compact('notes', 'course_slug', 'query'));
    }
}
