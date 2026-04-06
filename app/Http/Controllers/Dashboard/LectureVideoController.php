<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\LectureVideo;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LectureVideoController extends Controller
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
        return EnrollUser::where('user_id', Auth::id())
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

        $chapters = course_chapters($course, 'videos');

        $latestQuery = LectureVideo::select('id', 'slug', 'title', 'isPaid', 'status')
            ->whereHas('courses', fn($q) => $q->where('course_id', $course->id))
            ->where('status', Status::ACTIVE());

        // ✅ Free trial users only see free videos in recent list
        if ($isLocked) {
            $latestQuery->where('isPaid', 0);
        }

        $latest = $latestQuery->orderBy('id', 'desc')->take(5)->get();

        return view('frontend.dashboard.lecture-video.index', compact(
            'chapters',
            'latest',
            'course',
            'isLocked'
        ));
    }

    public function details($course_slug, $chapter, $lesson = null)
    {
        $course  = Course::select('id', 'name', 'slug')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id')->where('slug', $chapter)->firstOrFail();
        $lesson  = $lesson ? Lesson::select('id')->where('slug', $lesson)->firstOrFail() : null;

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        $isLocked = $enrolled->status === Status::FREETRIAL()->value;

        $query = LectureVideo::select('id', 'slug', 'title', 'isPaid', 'status')
            ->whereHas('courses', fn($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id)
            ->where('status', Status::ACTIVE());

        if ($lesson) {
            $query->where('lesson_id', $lesson->id);
        }

        $videos = $query->latest()->get();

        if ($videos->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is Empty!');
        }

        return view('frontend.dashboard.lecture-video.lists', compact(
            'videos',
            'course',
            'isLocked'
        ));
    }

    public function single_details($slug, $course_slug = null)
    {
        if (! $course_slug) {
            return redirect()->back()->with('error', 'Course not found. Try again!');
        }

        abort_unless(Auth::check(), 403, 'Please login to watch this video');

        $video  = LectureVideo::where('slug', $slug)->firstOrFail();
        $course = Course::select('id', 'name', 'slug')->where('slug', $course_slug)->firstOrFail();

        $enrolled = $this->getEnrollment($course->id);

        if (! $enrolled) {
            return redirect()->back()->with('error', 'You are not enrolled in this course.');
        }

        // ✅ If video is paid and user is on free trial → block
        if ($video->isPaid && $enrolled->status === Status::FREETRIAL()->value) {
            return redirect()->route('courses.checkout', ['course' => $course_slug])
                ->with('error', 'This is premium content. Please upgrade your plan.');
        }

        $signedUrl = $this->generateSignedBunnyUrl($video);

        return view('frontend.dashboard.lecture-video.details', [
            'video'      => $video,
            'course'     => $course,
            'course_slug' => $course_slug,
            'signedUrl'  => $signedUrl,
            'userEmail'  => Auth::user()->email,
        ]);
    }

    private function generateSignedBunnyUrl($video)
    {
        $libraryId = env('BUNNY_VIDEO_LIBRARY_ID');
        $videoId   = $video->uploaded_link;
        $expires   = now()->addHours(2)->timestamp;
        $token     = hash('sha256', env('BUNNY_API_KEY') . $videoId . $expires);

        return "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}?token={$token}&expires={$expires}";
    }
}
