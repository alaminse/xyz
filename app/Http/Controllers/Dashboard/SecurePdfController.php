<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EnrollUser;
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

    private function isPaid(int $courseId): int
    {
        $enrolled = EnrollUser::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->first();

        if (!$enrolled) return 9;

        return $enrolled->status == Status::ACTIVE()->value
            ? 1
            : ($enrolled->status == Status::FREETRIAL()->value ? 0 : 9);
    }

    // PDF list page
    public function index(string $course)
    {
        $course   = Course::select('id', 'slug', 'name')
            ->where('slug', $course)
            ->firstOrFail();

        $isPaid   = $this->isPaid($course->id);
        $isLocked = $isPaid == 0;

        $query = SecurePdf::where('is_active', true)
            ->whereHas('courses', fn($q) =>
                $q->where('courses.id', $course->id)
            );

        if ($isPaid == 0) {
            $query->where('isPaid', 0);
        }

        $pdfs = $query->with(['chapter', 'lesson'])
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('frontend.dashboard.secure-pdfs.index',
            compact('pdfs', 'course', 'isLocked'));
    }

    // Open secure viewer
    public function view(Request $request, string $slug)
    {
        $pdf    = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $course   = $pdf->courses->first();
        $isPaid   = $this->isPaid($course?->id ?? 0);
        $isLocked = $isPaid == 0;

        // Paid PDF but free user — redirect to upgrade
        if ($pdf->isPaid && $isLocked) {
            return redirect()
                ->route('courses.checkout', ['course' => $course?->slug])
                ->with('error', 'Upgrade to Premium to access this PDF.');
        }

        $token = $this->service->generateViewToken($pdf, $request);
        $this->service->logAccess(
            $pdf->id, $request->user()->id, $request, 'viewed'
        );

        return view('frontend.dashboard.secure-pdfs.viewer',
            compact('pdf', 'token'));
    }

    // Stream PDF — anti-IDM protected
    public function stream(Request $request, string $slug)
    {
        // Block empty user agents
        $ua = strtolower($request->userAgent() ?? '');
        if (empty($ua)) abort(403);

        // Validate token
        $token     = $request->get('token');
        $viewToken = $this->service->validateToken($token, $request);

        if (!$viewToken) {
            return response('Unauthorized', 401)
                ->header('Content-Type', 'text/plain');
        }

        // Session binding — prevent token sharing
        $sessionKey = 'pdf_sess_' . md5($token);
        if (session()->has($sessionKey)) {
            if (session($sessionKey) !== session()->getId()) {
                $this->service->logAccess(
                    $viewToken->secure_pdf_id,
                    $request->user()->id,
                    $request,
                    'suspicious_activity'
                );
                return response('Forbidden', 403)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            session([$sessionKey => session()->getId()]);
        }

        // Rate limit — max 5 per minute per user
        $rateKey = 'pdf_rate_' . $request->user()->id
                 . '_' . $viewToken->secure_pdf_id;
        $hits    = Cache::get($rateKey, 0);

        if ($hits >= 5) {
            $this->service->logAccess(
                $viewToken->secure_pdf_id,
                $request->user()->id,
                $request,
                'suspicious_activity'
            );
            return response('Too Many Requests', 429)
                ->header('Content-Type', 'text/plain');
        }

        Cache::put($rateKey, $hits + 1, 60);

        // Load PDF
        $pdf = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->where('id', $viewToken->secure_pdf_id)
            ->firstOrFail();

        $realPath = $this->service->getRealPath($pdf);
        if (!$realPath || !file_exists($realPath)) abort(404);

        $this->service->logAccess(
            $pdf->id, $request->user()->id, $request, 'streamed'
        );

        // Stream with anti-IDM headers
        // Content-Type: application/octet-stream — NOT pdf
        // No Content-Length — IDM cannot determine file size
        // Chunked transfer — IDM cannot grab full file
        // Referer checked in middleware
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

    // Refresh token — called by JS every 25 min
    public function refreshToken(Request $request, string $slug)
    {
        $pdf   = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $token = $this->service->generateViewToken($pdf, $request);

        return response()->json([
            'token'      => $token,
            'expires_in' => 1800,
        ]);
    }
}
