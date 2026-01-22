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

        $chapters = course_chapters($course, 'videos');

        $isPaid = $this->isPaid(course: $course->id);
        if ($isPaid == 9) return redirect()->back()->with('error', 'Something is Wrong!!');

        $latest = LectureVideo::select('id', 'slug', 'title', 'status')
            ->whereHas('courses', fn($q) => $q->where('course_id', $course->id));
        if ($isPaid == 0) {
            $latest = $latest->where('isPaid', $isPaid);
        }

        $latest = $latest->orderBy('id', 'desc')->where('status', Status::ACTIVE())->take(5)->get();

        return view('frontend.dashboard.lecture-video.index', compact('chapters', 'latest', 'course'));
    }

    public function details($course_slug, $chapter, $lesson = null)
    {
        $course = Course::select('id', 'name', 'slug')->where('slug', $course_slug)->firstOrFail();
        $chapter = Chapter::select('id')->where('slug', $chapter)->firstOrFail();
        $lesson = $lesson ? Lesson::select('id')->where('slug', $lesson)->firstOrFail() : null;

        $data = LectureVideo::whereHas('courses', fn($q) => $q->where('course_id', $course->id))
            ->where('chapter_id', $chapter->id);
        if ($lesson) {
            $data->where('lesson_id', $lesson->id);
        }
        $data->where('status', Status::ACTIVE());
        $videos = $data->latest()->get();

        if ($videos->isEmpty()) return redirect()->back()->with('error', 'This lesson is Empty!');

        return view('frontend.dashboard.lecture-video.lists', compact('videos', 'course'));
    }

    public function single_details($slug, $course_slug = null)
    {
        if(!$course_slug) return redirect()->back()->with('error', 'Course not found. try again!!');
        $video = LectureVideo::where('slug', $slug)->firstOrFail();

        // Check authentication
        // Check if user has access to this video
        // (Add your enrollment/payment check here)
        abort_unless(auth()->check(), 403, 'Please login to watch this video');

        // Generate signed URL with expiry
        $signedUrl = $this->generateSignedBunnyUrl($video);

        return view('frontend.dashboard.lecture-video.details', [
            'video' => $video,
            'course_slug' => $course_slug,
            'signedUrl' => $signedUrl,
            'userEmail' => auth()->user()->email
        ]);
    }

    private function generateSignedBunnyUrl($video)
    {
        $libraryId = env('BUNNY_VIDEO_LIBRARY_ID');
        $videoId = $video->uploaded_link;
        $expires = now()->addHours(2)->timestamp; // 2 ঘণ্টা পর expire হবে

        // Security token generate
        $token = hash('sha256', env('BUNNY_API_KEY') . $videoId . $expires);

        // Signed iframe URL
        return "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}?token={$token}&expires={$expires}";
    }

}
