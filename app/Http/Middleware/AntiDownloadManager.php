<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AntiDownloadManager
{
    /**
     * Known download manager user-agent strings.
     * IDM, JDownloader, wget, curl etc.
     */
    private array $blockedAgents = [
        'internetdownloadmanager',
        'idm/',
        'jdownloader',
        'wget/',
        'aria2',
        'curl/',
        'libwww-perl',
        'python-requests',
        'python-urllib',
        'go-http-client',
        'okhttp',
        'download master',
        'flashget',
        'getright',
        'download accelerator',
        'fdm/',
        'eadownloader',
        'mass downloader',
        'leechget',
        'reget',
        'httrack',
        'scrapy',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $ua = strtolower($request->userAgent() ?? '');

        // Block empty user agents — download tools often send empty UA
        if (empty(trim($ua))) {
            abort(403);
        }

        // Block known download managers
        foreach ($this->blockedAgents as $agent) {
            if (str_contains($ua, $agent)) {
                abort(403);
            }
        }

        // Stream route: referer must be from our own domain
        if ($request->routeIs('secure-pdfs.stream')) {
            $referer = $request->headers->get('referer', '');
            $appUrl  = rtrim(config('app.url'), '/');

            // No referer = direct request from IDM or address bar
            if (empty($referer)) {
                abort(403);
            }

            // Referer must be from our domain
            if (!str_starts_with($referer, $appUrl)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
