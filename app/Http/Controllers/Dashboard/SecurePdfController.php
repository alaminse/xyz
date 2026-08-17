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

    private function isPaid($courseId)
    {
        if (! Auth::check()) {
            return 9;
        }

        $courseIds = collect([$courseId]);

        $courseModel = Course::find($courseId);
        if ($courseModel?->parent_id) {
            $courseIds->push($courseModel->parent_id);
        }

        $enrolled = EnrollUser::where('user_id', Auth::user()->id)
            ->whereIn('course_id', $courseIds)
            ->first();

        if (! $enrolled) {
            return 9;
        }
        if ($enrolled->status == Status::ACTIVE()->value) {
            return 1;
        }
        if ($enrolled->status == Status::FREETRIAL()->value) {
            return 0;
        }

        return 9;
    }

    public function index($course)
    {
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        if (! $course) {
            abort(404, 'Course not found');
        }

        $isPaid = $this->isPaid($course->id);
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
        $course = Course::select('id', 'parent_id', 'slug', 'name')
            ->where('slug', $course_slug)
            ->firstOrFail();

        $chapter = Chapter::where('slug', $chapter_slug)->firstOrFail();
        $lesson = Lesson::where('slug', $lesson_slug)->firstOrFail();

        $enrolled = EnrollUser::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        $isLocked = ! $enrolled || $enrolled->status === Status::FREETRIAL()->value;

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

    // controller
    public function view(Request $request, string $course_slug, string $slug)
    {
        $pdf = SecurePdf::with('courses')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $course = $pdf->courses->where('slug', $course_slug)->first()
                ?? $pdf->courses->first();

        // fallback
        $course = $course ?? $pdf->courses->first();

        if (! $course) {
            abort(404, 'No course linked to this PDF.');
        }

        $isPaid = $this->isPaid($course->id);
        $isLocked = $isPaid == 0;

        if ($pdf->isPaid && $isPaid === 9) {
            return redirect()
                ->route('courses.checkout', ['course' => $course->slug])
                ->with('error', 'Upgrade to Premium to access this PDF.');
        }

        if ($pdf->isPaid && $isLocked) {
            return redirect()
                ->route('courses.checkout', ['course' => $course->slug])
                ->with('error', 'Upgrade to Premium to access this PDF.');
        }

        $token = $this->service->generateViewToken($pdf, $request);
        $this->service->logAccess($pdf->id, $request->user()->id, $request, 'viewed');

        return view('frontend.dashboard.secure-pdfs.viewer', compact('pdf', 'token'));
    }

    public function stream(Request $request, string $slug)
    {
        $ua = strtolower($request->userAgent() ?? '');

        if (empty(trim($ua))) {
            abort(403);
        }

        $token = $request->query('token');

        if (! $token) {
            return response('Unauthorized', 401);
        }

        $viewToken = $this->service->validateToken($token, $request);

        if (! $viewToken) {
            return response('Unauthorized', 401)
                ->header('Content-Type', 'text/plain');
        }

        /*
        |--------------------------------------------------------------------------
        | Session binding
        |--------------------------------------------------------------------------
        */

        $sessionKey = 'pdf_sess_'.hash('sha256', $token);

        if (session()->has($sessionKey)) {

            if (session($sessionKey) !== session()->getId()) {

                $this->service->logAccess(
                    $viewToken->secure_pdf_id,
                    $request->user()->id,
                    $request,
                    'suspicious_activity'
                );

                return response('Forbidden', 403);
            }

        } else {
            session([$sessionKey => session()->getId()]);
        }

        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->where('id', $viewToken->secure_pdf_id)
            ->firstOrFail();

        $realPath = $this->service->getRealPath($pdf);

        if (! $realPath || ! is_file($realPath)) {
            abort(404);
        }

        $fileSize = filesize($realPath);

        /*
        |--------------------------------------------------------------------------
        | Range Request
        |--------------------------------------------------------------------------
        */

        $range = $request->header('Range');

        $start = 0;
        $end = $fileSize - 1;

        if ($range && preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {

            if ($matches[1] !== '') {
                $start = (int) $matches[1];
            }

            if ($matches[2] !== '') {
                $end = (int) $matches[2];
            } else {
                $end = $fileSize - 1;
            }

            /*
            |--------------------------------------------------------------------------
            | Validate range
            |--------------------------------------------------------------------------
            */

            if ($start > $end || $start >= $fileSize) {
                return response('', 416)
                    ->header('Content-Range', "bytes */{$fileSize}");
            }

            $end = min($end, $fileSize - 1);
        }

        $length = $end - $start + 1;

        /*
        |--------------------------------------------------------------------------
        | Log only initial stream request
        |--------------------------------------------------------------------------
        */

        if ($start === 0) {
            $this->service->logAccess(
                $pdf->id,
                $request->user()->id,
                $request,
                'streamed'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stream requested bytes
        |--------------------------------------------------------------------------
        */

        $status = $range ? 206 : 200;

        return response()->stream(function () use ($realPath, $start, $length) {

            $handle = fopen($realPath, 'rb');

            if ($handle === false) {
                return;
            }

            fseek($handle, $start);

            $remaining = $length;

            while ($remaining > 0 && ! feof($handle)) {

                $chunkSize = min(1024 * 1024, $remaining);

                $buffer = fread($handle, $chunkSize);

                if ($buffer === false) {
                    break;
                }

                echo $buffer;

                $remaining -= strlen($buffer);

                if (ob_get_level() > 0) {
                    ob_flush();
                }

                flush();
            }

            fclose($handle);

        }, $status, [

            'Content-Type' => 'application/pdf',
            'Content-Length' => $length,
            'Accept-Ranges' => 'bytes',
            'Content-Disposition' => 'inline',

            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',

            'Pragma' => 'no-cache',
            'Expires' => '0',

            'X-Accel-Buffering' => 'no',

            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',

        ] + ($range ? [
            'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
        ] : []));
    }

    public function refreshToken(Request $request, string $slug)
    {
        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $token = $this->service->generateViewToken($pdf, $request);

        return response()->json([
            'token' => $token,
            'expires_in' => 300,
        ]);
    }
}
