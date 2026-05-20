<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Lesson;
use App\Models\SecurePdf;
use App\Services\SecurePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SecurePdfController extends Controller
{
    protected $user;

    public function __construct(private SecurePdfService $service)
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    private function isPaid($course)
    {
        return $enrolled = EnrollUser::where('user_id', Auth::user()->id)
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

        if (!$course) {
            abort(404, 'Course not found');
        }

        $isPaid   = $this->isPaid(course: $course->id);
        $isLocked = $isPaid == 0;

        $chapters = course_chapters($course, 'secure_pdf');

        $latest = SecurePdf::select('id', 'slug', 'title', 'is_active', 'isPaid')
            ->whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            });

        if ($isPaid == 0) {
            $latest = $latest->where('isPaid', $isPaid);
        }

        $latest = $latest->orderBy('id', 'desc')->where('is_active', 1)->take(5)->get();

        return view('frontend.dashboard.secure-pdfs.index',
            compact('chapters', 'latest', 'course', 'isLocked'));
    }

    public function details($course_slug, $chapter_slug, $lesson_slug = null)
    {
        $course  = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course_slug)
            ->firstOrFail();

        $chapter = Chapter::where('slug', $chapter_slug)->firstOrFail();
        $lesson  = Lesson::where('slug', $lesson_slug)->firstOrFail();

        $enrolled = EnrollUser::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        $isLocked = !$enrolled || $enrolled->status === Status::FREETRIAL()->value;

        $pdfsQuery = SecurePdf::select('id', 'chapter_id', 'lesson_id', 'title', 'slug', 'isPaid', 'total_pages', 'file_size')
            ->where('chapter_id', $chapter->id)
            ->where('is_active', 1)
            ->whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            });

        if ($lesson) {
            $pdfsQuery->where('lesson_id', $lesson->id);
        }

        $pdfs = $pdfsQuery->get();

        if ($pdfs->isEmpty()) {
            return redirect()->back()->with('error', 'This lesson is empty!');
        }

        return view('frontend.dashboard.secure-pdfs.details',
            compact('pdfs', 'course', 'course_slug', 'isLocked'));
    }

    public function view(Request $request, string $slug)
    {
        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $course   = $pdf->courses->first();
        $courseId = $course->id;
        return $isPaid   = $this->isPaid($courseId ?? 0);
        $isLocked = $isPaid == 0;

        if ($pdf->isPaid && $isLocked) {
            return redirect()
                ->route('courses.checkout', ['course' => $course?->slug])
                ->with('error', 'Upgrade to Premium to access this PDF.');
        }

        $token = $this->service->generateViewToken($pdf, $request);
        $this->service->logAccess($pdf->id, $request->user()->id, $request, 'viewed');

        return view('frontend.dashboard.secure-pdfs.viewer',
            compact('pdf', 'token'));
    }

    public function stream(Request $request, string $slug)
    {
        $ua = strtolower($request->userAgent() ?? '');
        if (empty($ua)) abort(403);

        $token     = $request->get('token');
        $viewToken = $this->service->validateToken($token, $request);

        if (!$viewToken) {
            return response('Unauthorized', 401)
                ->header('Content-Type', 'text/plain');
        }

        $sessionKey = 'pdf_sess_' . md5($token);
        if (session()->has($sessionKey)) {
            if (session($sessionKey) !== session()->getId()) {
                $this->service->logAccess(
                    $viewToken->secure_pdf_id, $request->user()->id,
                    $request, 'suspicious_activity'
                );
                return response('Forbidden', 403)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            session([$sessionKey => session()->getId()]);
        }

        $rateKey = 'pdf_rate_' . $request->user()->id . '_' . $viewToken->secure_pdf_id;
        $hits    = Cache::get($rateKey, 0);

        if ($hits >= 5) {
            $this->service->logAccess(
                $viewToken->secure_pdf_id, $request->user()->id,
                $request, 'suspicious_activity'
            );
            return response('Too Many Requests', 429)
                ->header('Content-Type', 'text/plain');
        }

        Cache::put($rateKey, $hits + 1, 60);

        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->where('id', $viewToken->secure_pdf_id)
            ->firstOrFail();

        $realPath = $this->service->getRealPath($pdf);
        if (!$realPath || !file_exists($realPath)) abort(404);

        $this->service->logAccess($pdf->id, $request->user()->id, $request, 'streamed');

        return response()->stream(function () use ($realPath) {
            $handle = fopen($realPath, 'rb');
            while (!feof($handle)) {
                echo fread($handle, 8192);
                if (ob_get_level() > 0) ob_flush();
                flush();
            }
            fclose($handle);
        }, 200, [
            'Content-Type'           => 'application/octet-stream',
            'Content-Disposition'    => 'inline; filename="data"',
            'Transfer-Encoding'      => 'chunked',
            'Cache-Control'          => 'no-store, no-cache, must-revalidate, private, max-age=0',
            'Pragma'                 => 'no-cache',
            'Expires'                => '0',
            'X-Accel-Buffering'      => 'no',
            'X-Frame-Options'        => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'X-Download-Options'     => 'noopen',
        ]);
    }

    public function refreshToken(Request $request, string $slug)
    {
        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $token = $this->service->generateViewToken($pdf, $request);

        return response()->json([
            'token'      => $token,
            'expires_in' => 300,
        ]);
    }
}
