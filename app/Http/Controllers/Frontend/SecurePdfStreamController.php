<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SecurePdf;
use App\Services\SecurePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SecurePdfStreamController extends Controller
{
    public function __construct(private SecurePdfService $service) {}

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

        $rateKey = 'pdf_rate_' . $request->user()->id . '_' . $viewToken->secure_pdf_id;
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
        $pdf   = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $token = $this->service->generateViewToken($pdf, $request);
        return response()->json(['token' => $token, 'expires_in' => 1800]);
    }
}
