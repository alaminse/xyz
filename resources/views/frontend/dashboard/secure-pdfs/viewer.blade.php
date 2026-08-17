@extends('frontend.dashboard.app')
@section('title', 'Secure Viewer')

@section('css')
    <style>
        * {
            -webkit-user-select: none !important;
            user-select: none !important;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #0a0e1a !important;
            overflow: hidden !important;
            height: 100% !important;
            width: 100% !important;
        }

        .page-content,
        .right_col,
        .col-md-12 {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* loading */
        #ov-load {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #0a0e1a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 12px;
        }

        #ov-load.gone {
            display: none;
        }

        .spin {
            width: 42px;
            height: 42px;
            border: 3px solid rgba(248, 184, 74, .2);
            border-top-color: #f8b84a;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #ov-load p {
            color: #9aa4b2;
            font-size: 13px;
            margin: 0;
            text-align: center;
        }

        #ov-load p a {
            color: #f8b84a;
        }

        #prog-wrap {
            width: 180px;
            height: 3px;
            background: rgba(255, 255, 255, .1);
            border-radius: 4px;
            overflow: hidden;
        }

        #prog-bar {
            height: 100%;
            background: #f8b84a;
            border-radius: 4px;
            transition: width .2s;
            width: 0;
        }

        /* warn */
        #ov-warn {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0, 0, 0, .97);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 14px;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        #ov-warn.on {
            display: flex;
        }

        #ov-warn i {
            font-size: 44px;
            color: #d9534f;
        }

        #ov-warn h3 {
            font-size: 18px;
            margin: 0;
        }

        #ov-warn p {
            color: #9aa4b2;
            font-size: 12px;
            max-width: 300px;
            margin: 0;
        }

        /* shell */
        #shell {
            position: fixed;
            inset: 0;
            display: flex;
            flex-direction: column;
            background: #0a0e1a;
            font-family: 'Segoe UI', sans-serif;
        }

        /* toolbar */
        #toolbar {
            flex-shrink: 0;
            background: linear-gradient(90deg, #020b1c, #03132e);
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            padding: 6px 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        #tb1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        #tb2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            flex-wrap: wrap;
        }

        #tb3 {
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pdf-ttl {
            color: #f8b84a;
            font-weight: 600;
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
            min-width: 0;
        }

        #page-nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        #zoom-ctrl {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pmeta {
            color: #9aa4b2;
            font-size: 12px;
            white-space: nowrap;
        }

        #zlabel {
            color: #9aa4b2;
            font-size: 11px;
            min-width: 36px;
            text-align: center;
        }

        #pg-in,
        #gt-in {
            width: 38px;
            text-align: center;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #eaeaea;
            border-radius: 5px;
            padding: 3px 2px;
            font-size: 12px;
            -moz-appearance: textfield;
        }

        #pg-in::-webkit-inner-spin-button,
        #pg-in::-webkit-outer-spin-button,
        #gt-in::-webkit-inner-spin-button,
        #gt-in::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        #pg-in:focus,
        #gt-in:focus {
            outline: none;
            border-color: #f8b84a;
        }

        .btn {
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #eaeaea;
            border-radius: 5px;
            padding: 4px 9px;
            font-size: 12px;
            cursor: pointer;
            transition: background .15s, color .15s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            line-height: 1;
            gap: 4px;
        }

        .btn:hover,
        .btn:active {
            background: rgba(248, 184, 74, .2);
            color: #f8b84a;
        }

        .btn:disabled {
            opacity: .3;
            cursor: default;
        }

        .btn.on {
            background: rgba(248, 184, 74, .2);
            color: #f8b84a;
            border-color: #f8b84a;
        }

        /* search bar */
        #srch-bar {
            display: none;
            align-items: center;
            gap: 5px;
            background: rgba(0, 0, 0, .3);
            border-radius: 6px;
            padding: 3px 7px;
            flex: 1;
            max-width: 340px;
        }

        #srch-bar.on {
            display: flex;
        }

        #srch-in {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #eaeaea;
            font-size: 12px;
            min-width: 60px;
        }

        #srch-in::placeholder {
            color: #6b7280;
        }

        #srch-cnt {
            color: #9aa4b2;
            font-size: 11px;
            white-space: nowrap;
        }

        /* goto bar */
        #gt-bar {
            display: none;
            align-items: center;
            gap: 5px;
        }

        #gt-bar.on {
            display: flex;
        }

        /* description bar */
        #desc-bar {
            flex-shrink: 0;
            background: rgba(248, 184, 74, .06);
            border-bottom: 1px solid rgba(248, 184, 74, .14);
            padding: 6px 14px;
            display: flex;
            align-items: flex-start;
            gap: 7px;
            font-size: 12px;
            color: #c0c7d0;
            line-height: 1.5;
        }

        /* main body */
        #body {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        /* page overview — full-screen thumbnail grid */
        #pg-overview {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 20000;
            background: #0a0e1a;
            flex-direction: column;
        }

        #pg-overview.on {
            display: flex;
        }

        #pgo-header {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: linear-gradient(90deg, #020b1c, #03132e);
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        #pgo-tabs {
            display: flex;
            gap: 6px;
        }

        .pgo-tab {
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 13px;
            color: #9aa4b2;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .pgo-tab.on {
            background: rgba(248, 184, 74, .16);
            color: #f8b84a;
            font-weight: 600;
        }

        #pgo-close {
            background: none;
            border: none;
            color: #eaeaea;
            font-size: 26px;
            line-height: 1;
            cursor: pointer;
            padding: 0 4px;
        }

        #pgo-close:hover {
            color: #f8b84a;
        }

        .pgo-body {
            display: none;
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .pgo-body.on {
            display: block;
        }

        .pgo-body::-webkit-scrollbar {
            width: 4px;
        }

        .pgo-body::-webkit-scrollbar-thumb {
            background: rgba(248, 184, 74, .3);
            border-radius: 4px;
        }

        #pg-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .pgl-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .pgl-thumb {
            width: 100%;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, .1);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .5);
            transition: border-color .15s, box-shadow .15s;
        }

        .pgl-item:hover .pgl-thumb {
            border-color: rgba(248, 184, 74, .5);
        }

        .pgl-item.active .pgl-thumb {
            border-color: #4a9eff;
            box-shadow: 0 0 0 2px #4a9eff, 0 2px 10px rgba(0, 0, 0, .6);
        }

        .pgl-canvas {
            width: 100%;
            height: 100%;
            display: block;
        }

        .pgl-num {
            color: #4a5568;
            font-size: 20px;
            font-weight: 700;
        }

        .pgl-lbl {
            color: #9aa4b2;
            font-size: 12px;
        }

        .pgl-item.active .pgl-lbl {
            color: #4a9eff;
            font-weight: 600;
        }

        @media (min-width:640px) {
            #pg-list {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width:900px) {
            #pg-list {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        .info-ttl {
            color: #f8b84a;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-desc {
            color: #c0c7d0;
            font-size: 13px;
            line-height: 1.7;
            max-width: 560px;
        }

        .info-meta {
            color: #6b7280;
            font-size: 12px;
            margin-top: 12px;
            line-height: 2;
        }

        .info-meta span {
            color: #9aa4b2;
        }

        /* canvas area */
        #cv-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            background: #1a1f2e;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 6px;
            gap: 12px;
            -webkit-overflow-scrolling: touch;
        }

        #cv-area::-webkit-scrollbar {
            width: 3px;
        }

        #cv-area::-webkit-scrollbar-thumb {
            background: rgba(248, 184, 74, .3);
            border-radius: 4px;
        }

        .pg-wrap {
            position: relative;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .6);
            border-radius: 2px;
            overflow: hidden;
            flex-shrink: 0;
            background: #2a2f3e;
            max-width: 100%;
        }

        .pg-canvas {
            display: block;
            max-width: 100%;
        }

        .wm-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
            overflow: hidden;
        }

        .wm-layer svg {
            width: 100%;
            height: 100%;
        }

        .hl-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 4;
        }

        .srch-hl {
            position: absolute;
            background: rgba(248, 184, 74, .35);
            border: 1px solid rgba(248, 184, 74, .6);
            pointer-events: none;
            z-index: 3;
            border-radius: 2px;
        }

        .srch-hl.cur {
            background: rgba(248, 100, 50, .5);
            border-color: #f86432;
        }

        .pg-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(248, 184, 74, .3);
            font-size: 12px;
            gap: 8px;
            width: 100%;
            height: 100%;
        }

        /* status bar */
        #status-bar {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 10px;
            background: #020b1c;
            border-top: 1px solid rgba(255, 255, 255, .05);
            color: #9aa4b2;
            font-size: 10px;
            flex-wrap: wrap;
            gap: 4px;
        }

        .lk-badge {
            display: flex;
            align-items: center;
            gap: 3px;
            color: #5cb85c;
            font-weight: 600;
        }

        .timer {
            color: #f8b84a;
            font-weight: 700;
        }

        @media (min-width:640px) {
            #toolbar {
                flex-direction: row;
                align-items: center;
                padding: 8px 14px;
                gap: 10px;
                flex-wrap: wrap;
            }

            #tb1 {
                flex: 1;
                min-width: 180px;
            }

            #tb2,
            #tb3 {
                flex-shrink: 0;
            }

            .pdf-ttl {
                font-size: 14px;
            }

            #cv-area {
                padding: 16px 12px;
                gap: 16px;
            }

            #status-bar {
                font-size: 11px;
            }
        }

        @media (max-width:480px) {
            .btn {
                padding: 4px 7px;
                font-size: 11px;
            }

            #pg-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@section('content')

    {{-- Loading --}}
    <div id="ov-load">
        <div class="spin"></div>
        <p id="ov-msg">Downloading secure PDF…</p>
        <div id="prog-wrap">
            <div id="prog-bar"></div>
        </div>
    </div>

    {{-- Warn --}}
    <div id="ov-warn">
        <i class="bx bx-shield-x"></i>
        <h3>Security Alert</h3>
        <p id="warn-msg">Suspicious activity detected.</p>
        <button class="btn" onclick="closeWarn()">Resume Reading</button>
    </div>

    {{-- Page overview (full-screen thumbnail grid) --}}
    <div id="pg-overview">
        <div id="pgo-header">
            <div id="pgo-tabs">
                <div class="pgo-tab on" onclick="pgoTab('pages')">Pages</div>
                <div class="pgo-tab" onclick="pgoTab('info')">Info</div>
            </div>
            <button id="pgo-close" onclick="closePageOverview()">&times;</button>
        </div>
        <div id="pgo-body-pages" class="pgo-body on">
            <div id="pg-list"></div>
        </div>
        <div id="pgo-body-info" class="pgo-body">
            <div class="info-ttl">{{ $pdf->title }}</div>
            <div class="info-desc">{{ $pdf->description ?? 'No description.' }}</div>
            <div class="info-meta">
                Pages: <span>{{ $pdf->total_pages }}</span><br>
                Size: <span>{{ $pdf->file_size_formatted }}</span><br>
                Chapter: <span>{{ $pdf->chapter?->name ?? '—' }}</span><br>
                Lesson: <span>{{ $pdf->lesson?->name ?? '—' }}</span><br>
                Type: <span
                    style="color:{{ $pdf->isPaid ? '#f8b84a' : '#5cb85c' }}">{{ $pdf->isPaid ? 'Premium' : 'Free' }}</span>
            </div>
        </div>
    </div>

    <div id="shell">

        {{-- Toolbar --}}
        <div id="toolbar">
            <div id="tb1">
                <div class="pdf-ttl"><i class="bx bx-file-pdf" style="color:#d9534f"></i> {{ $pdf->title }}</div>
                <a href="{{ route('secure-pdfs.details', ['course' => $pdf->courses->first()?->slug, 'chapter' => $pdf->chapter?->slug, 'lesson' => $pdf->lesson?->slug]) }}"
                    class="btn">&#8592; Back</a>
            </div>
            <div id="tb2">
                <div id="page-nav">
                    <button class="btn" id="btn-prev" onclick="goPage(curPage-1)">&#8249;</button>
                    <input id="pg-in" type="number" min="1" value="1" onchange="goPage(+this.value)">
                    <span class="pmeta">/ <span id="tot-pages">—</span></span>
                    <button class="btn" id="btn-next" onclick="goPage(curPage+1)">&#8250;</button>
                </div>
                <div id="zoom-ctrl">
                    <button class="btn" onclick="zoom(-0.1)">&#8722;</button>
                    <span id="zlabel">Auto</span>
                    <button class="btn" onclick="zoom(+0.1)">&#43;</button>
                </div>
            </div>
            <div id="tb3">
                <button class="btn" id="btn-srch" onclick="toggleSearch()"><i class="bx bx-search"></i> Search</button>
                <button class="btn" id="btn-gt" onclick="toggleGoto()"><i class="bx bx-navigation"></i> Go to</button>
                <button class="btn" id="btn-sb" onclick="openPageOverview()"><i class="bx bx-list-ul"></i>
                    Pages</button>

                <div id="srch-bar">
                    <i class="bx bx-search" style="color:#9aa4b2;font-size:13px"></i>
                    <input id="srch-in" type="text" placeholder="Search word…" oninput="doSearch(this.value)"
                        onkeydown="if(event.key==='Enter')nextMatch()">
                    <span id="srch-cnt"></span>
                    <button class="btn" onclick="prevMatch()">&#8679;</button>
                    <button class="btn" onclick="nextMatch()">&#8681;</button>
                    <button class="btn" onclick="clearSearch()">&#10005;</button>
                </div>

                <div id="gt-bar">
                    <input id="gt-in" type="number" min="1" placeholder="Page#"
                        onkeydown="if(event.key==='Enter')doGoto()">
                    <button class="btn" onclick="doGoto()">Go</button>
                    <button class="btn" onclick="toggleGoto()">&#10005;</button>
                </div>
            </div>
        </div>

        @if ($pdf->description)
            <div id="desc-bar">
                <i class="bx bx-info-circle" style="color:#f8b84a;font-size:15px;flex-shrink:0;margin-top:1px"></i>
                <span>{{ $pdf->description }}</span>
            </div>
        @endif

        <div id="body">
            <div id="cv-area"></div>
        </div>


        <div id="status-bar">
            <div class="lk-badge"><i class="bx bx-lock-alt"></i> No download &bull; No copy &bull; Watermarked</div>
            <div>Expires: <span class="timer" id="countdown">05:00</span></div>
            <div>{{ auth()->user()->name }}</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // ── CONFIG ──────────────────────────────────────────────────────────────
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const STREAM_URL = @json(route('secure-pdfs.stream', $pdf->slug));
        const REFRESH_URL = @json(route('secure-pdfs.token.refresh', $pdf->slug));
        const CSRF = @json(csrf_token());
        const U_NAME = @json(auth()->user()->name);
        const U_EMAIL = @json(auth()->user()->email);
        const PDF_SLUG = @json($pdf->slug);
        const UID = @json(auth()->id());
        const WM_TEXT = 'MediManiac \u2022 ' + U_NAME + ' \u2022 ' + U_EMAIL;

        let TOKEN = @json($token);
        let pdfDoc = null;
        let totPages = 0;
        let curPage = 1;
        let zDelta = 0;
        let expiry = 5 * 60;
        let rendering = false;

        // ── DEBUG MODE ─────────────────────────────────────────────────────────
        // Add ?debug=1 to the URL to see JS errors as an on-screen alert, useful
        // when DevTools access is restricted. Remove once the issue is diagnosed.
        var DEBUG_MODE = new URLSearchParams(location.search).get('debug') === '1';
        if (DEBUG_MODE) {
            window.onerror = function(msg, url, line, col) {
                alert('JS Error: ' + msg + '\nAt line ' + line + ':' + col);
                return false;
            };
            window.addEventListener('unhandledrejection', function(e) {
                alert('Promise error: ' + (e.reason && e.reason.message ? e.reason.message : e.reason));
            });
        }

        // ── LOAD PDF (progressive/range-request streaming, with retry) ───────────
        // Instead of downloading the whole file into memory first, we hand pdf.js
        // the URL directly. pdf.js then uses HTTP Range requests to pull in only
        // the bytes it needs, so early pages can start rendering before the full
        // file has finished downloading. This requires the stream endpoint to
        // support Range requests (Accept-Ranges + 206 Partial Content responses) —
        // see the note at the bottom of this file if that isn't already the case.
        async function loadPdf(attempt) {
            attempt = attempt || 1;
            var maxAttempts = 3;

            document.getElementById('ov-load').classList.remove('gone');
            document.getElementById('prog-bar').style.background = '#f8b84a';
            document.getElementById('prog-bar').style.width = '0%';
            document.getElementById('ov-msg').textContent = 'Loading secure PDF\u2026';

            // On any retry (auto or manual), the token may already be stale/expired
            // — especially on slow connections where the first attempt alone can
            // eat into the token's short lifetime. Get a fresh one before trying
            // again so a retry isn't doomed to repeat the same failure.
            if (attempt > 1) {
                await refreshToken();
            }

            try {
                var loadingTask = pdfjsLib.getDocument({
                    url: STREAM_URL + '?token=' + encodeURIComponent(TOKEN),
                    withCredentials: true,
                    httpHeaders: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    rangeChunkSize: 1 << 18,
                    disableAutoFetch: false,
                    disableStream: false
                });

                // Stall watchdog: genuinely slow connections that are still making
                // progress are left alone (no overall time limit), but if 25s pass
                // with zero progress — the connection likely died silently — we
                // destroy the task so the catch block below can trigger a retry
                // instead of hanging forever with no feedback.
                var lastProgress = Date.now();
                var stalled = false;
                var stallTimer = setInterval(function() {
                    if (Date.now() - lastProgress > 60000) {
                        stalled = true;
                        clearInterval(stallTimer);
                        loadingTask.destroy();
                    }
                }, 2000);

                loadingTask.onProgress = function(p) {
                    lastProgress = Date.now();
                    if (p.total) {
                        var pct = Math.min(99, Math.round(p.loaded / p.total * 100));
                        document.getElementById('prog-bar').style.width = pct + '%';
                        document.getElementById('ov-msg').textContent = 'Loading\u2026 ' + pct + '%';
                    }
                };

                pdfDoc = await loadingTask.promise;
                clearInterval(stallTimer);
                totPages = pdfDoc.numPages;
                document.getElementById('tot-pages').textContent = totPages;
                document.getElementById('pg-in').max = totPages;
                document.getElementById('gt-in').max = totPages;

                await initPages();
            } catch (e) {

                console.error('PDF loading error:', e);

                if (typeof stallTimer !== 'undefined') {
                    clearInterval(stallTimer);
                }

                console.error({
                    name: e?.name,
                    message: e?.message,
                    status: e?.status
                });

                if (attempt < maxAttempts) {

                    document.getElementById('ov-msg').textContent =
                        'PDF connection interrupted. Retrying… (' +
                        attempt + '/' + (maxAttempts - 1) + ')';

                    await new Promise(function(resolve) {
                        setTimeout(resolve, 1000 * attempt);
                    });

                    return loadPdf(attempt + 1);
                }

                document.getElementById('ov-msg').innerHTML =
                    'Unable to load the PDF. ' +
                    '<a href="#" onclick="manualRetry();return false;">Tap to retry</a>';

                document.getElementById('prog-bar').style.background = '#d9534f';
            }
        }

        function manualRetry() {
            loadPdf(2); // start at attempt 2 so it refreshes the token before trying again
        }
        // NOTE ON SERVER SUPPORT: if your `secure-pdfs.stream` route reads the file
        // with something like `readfile()` or a plain streamed response, it likely
        // ignores the `Range` header and always sends the whole file — in that case
        // pdf.js automatically falls back to downloading the full file (same speed
        // as before, just no regression). To get the actual speed-up, the route
        // needs to honor `Range` requests and respond with `206 Partial Content` +
        // `Content-Range` + `Accept-Ranges: bytes`. In Laravel this is easiest via
        // `response()->file($path)` (Symfony's BinaryFileResponse supports Range
        // out of the box) or `Storage::response()` if the disk driver supports it —
        // rather than manually streaming bytes yourself.

        // ── LAZY RENDER SYSTEM ──────────────────────────────────────────────────
        var rendered = {};
        var inRender = {};
        var pgObserver = null;
        var estH = 900,
            estW = 600;

        async function initPages() {
            // Get first page size for placeholders
            var p1 = await pdfDoc.getPage(1);
            var cw = Math.floor(document.getElementById('cv-area').clientWidth - 12);
            var bvp = p1.getViewport({
                scale: 1
            });
            var fs = Math.max(0.3, cw / bvp.width + zDelta);
            var vp = p1.getViewport({
                scale: fs
            });
            estH = vp.height;
            estW = vp.width;

            var area = document.getElementById('cv-area');
            area.innerHTML = '';
            rendered = {};
            inRender = {};

            for (var i = 1; i <= totPages; i++) {
                var wrap = document.createElement('div');
                wrap.className = 'pg-wrap';
                wrap.id = 'pw-' + i;
                wrap.dataset.page = i;
                wrap.style.width = estW + 'px';
                wrap.style.height = estH + 'px';
                wrap.innerHTML = '<div class="pg-loader">' +
                    '<div class="spin"></div>' +
                    '<div>Page ' + i + '</div>' +
                    '</div>';
                area.appendChild(wrap);
            }

            setupObserver();

            // Render only page 1 before hiding the spinner, so the user sees
            // content as soon as possible. Pages 2/3 render right after in the
            // background — this can shave several seconds off perceived load time
            // versus waiting for all 3 to finish first.
            await renderPage(1);
            document.getElementById('ov-load').classList.add('gone');
            updateNav();

            for (var j = 2; j <= Math.min(3, totPages); j++) {
                renderPage(j); // not awaited — runs in background
            }
        }

        async function renderPage(n) {
            if (rendered[n] || inRender[n]) return;
            inRender[n] = true;

            var wrap = document.getElementById('pw-' + n);
            if (!wrap) {
                inRender[n] = false;
                return;
            }

            try {
                var page = await pdfDoc.getPage(n);
                var cw = Math.floor(document.getElementById('cv-area').clientWidth - 12);
                var bvp = page.getViewport({
                    scale: 1
                });
                var fs = Math.max(0.3, cw / bvp.width + zDelta);
                var vp = page.getViewport({
                    scale: fs
                });
                // Only cap the pixel ratio for large documents (many pages), where
                // rendering cost adds up fast. Short documents render at full
                // devicePixelRatio for maximum sharpness since the cost is small.
                var dpr = totPages > 50 ?
                    Math.min(window.devicePixelRatio || 1, 2) :
                    Math.min(window.devicePixelRatio || 1, 3);

                wrap.style.width = vp.width + 'px';
                wrap.style.height = vp.height + 'px';
                wrap.dataset.scale = fs;

                var canvas = document.createElement('canvas');
                canvas.className = 'pg-canvas';
                canvas.width = Math.floor(vp.width * dpr);
                canvas.height = Math.floor(vp.height * dpr);
                canvas.style.width = vp.width + 'px';
                canvas.style.height = vp.height + 'px';

                var ctx = canvas.getContext('2d', {
                    alpha: false
                });
                ctx.scale(dpr, dpr);
                await page.render({
                    canvasContext: ctx,
                    viewport: vp
                }).promise;
                burnWm(ctx, vp.width, vp.height);

                var hl = document.createElement('div');
                hl.className = 'hl-layer';
                hl.id = 'hl-' + n;

                var wm = document.createElement('div');
                wm.className = 'wm-layer';
                wm.innerHTML = svgWm(vp.width, vp.height);

                wrap.innerHTML = '';
                wrap.style.background = '#fff';
                wrap.appendChild(canvas);
                wrap.appendChild(hl);
                wrap.appendChild(wm);

                rendered[n] = true;
            } catch (e) {
                console.error('Page ' + n + ' error:', e);
            }
            inRender[n] = false;
        }

        function eagerLoad(n) {
            [n - 1, n + 1, n + 2, n - 2].forEach(function(p) {
                if (p >= 1 && p <= totPages) renderPage(p);
            });
        }

        function setupObserver() {
            if (pgObserver) pgObserver.disconnect();
            pgObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        var n = +e.target.dataset.page;
                        renderPage(n);
                        eagerLoad(n);
                    }
                });
            }, {
                root: document.getElementById('cv-area'),
                rootMargin: '200px 0px 200px 0px',
                threshold: 0.01
            });
            document.querySelectorAll('.pg-wrap').forEach(function(el) {
                pgObserver.observe(el);
            });
        }

        async function reRenderAll() {
            if (!pdfDoc || rendering) return;
            rendering = true;
            await initPages();
            rendering = false;
            if (srchMatches.length) reDrawSearch();
        }

        // ── WATERMARK (single instance per page) ─────────────────────────────────
        function burnWm(ctx, w, h) {
            ctx.save();
            ctx.globalAlpha = 0.12;
            ctx.font = 'bold ' + Math.max(13, Math.floor(w * 0.032)) + 'px Arial';
            ctx.fillStyle = '#c0392b';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.translate(w / 2, h / 2);
            ctx.rotate(-Math.PI / 6);
            ctx.fillText(WM_TEXT, 0, 0);
            ctx.restore();
        }

        function svgWm(w, h) {
            var cx = w / 2,
                cy = h / 2;
            var fs = Math.max(11, Math.floor(w * 0.018));
            return '<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '">' +
                '<text x="' + cx + '" y="' + cy + '" font-family="Arial" font-size="' + fs + '" font-weight="bold"' +
                ' fill="rgba(192,57,43,0.06)" text-anchor="middle"' +
                ' transform="rotate(-25 ' + cx + ' ' + cy + ')">' + WM_TEXT + '</text>' +
                '</svg>';
        }

        // ── BLANK ALL (security) ─────────────────────────────────────────────────
        function blankAll() {
            document.querySelectorAll('.pg-canvas').forEach(function(c) {
                var ctx = c.getContext('2d');
                ctx.fillStyle = '#0a0e1a';
                ctx.fillRect(0, 0, c.width, c.height);
                ctx.fillStyle = 'rgba(248,184,74,0.25)';
                ctx.font = 'bold ' + Math.max(14, Math.floor(c.width * 0.04)) + 'px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('\uD83D\uDD12 Content Protected', c.width / 2, c.height / 2);
                ctx.textAlign = 'left';
            });
            rendered = {};
        }

        // ── ZOOM ─────────────────────────────────────────────────────────────────
        async function zoom(d) {
            zDelta = +(zDelta + d).toFixed(1);
            document.getElementById('zlabel').textContent =
                zDelta === 0 ? 'Auto' : (zDelta > 0 ? '+' : '') + Math.round(zDelta * 100) + '%';
            await reRenderAll();
        }

        // ── PAGE NAV ─────────────────────────────────────────────────────────────
        function goPage(n) {
            if (!pdfDoc) return;
            n = Math.max(1, Math.min(totPages, n));
            curPage = n;
            document.getElementById('pg-in').value = n;
            updateActivePageInList();
            var el = document.getElementById('pw-' + n);
            if (el) el.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            updateNav();
        }

        function updateNav() {
            document.getElementById('btn-prev').disabled = curPage <= 1;
            document.getElementById('btn-next').disabled = curPage >= totPages;
        }

        document.getElementById('cv-area').addEventListener('scroll', function() {
            var top = this.getBoundingClientRect().top;
            var closest = 1,
                minD = Infinity;
            document.querySelectorAll('.pg-wrap').forEach(function(w, i) {
                var d = Math.abs(w.getBoundingClientRect().top - top);
                if (d < minD) {
                    minD = d;
                    closest = i + 1;
                }
            });
            if (closest !== curPage) {
                curPage = closest;
                document.getElementById('pg-in').value = closest;
                updateActivePageInList();
                updateNav();
            }
        });

        var resT;
        window.addEventListener('resize', function() {
            clearTimeout(resT);
            resT = setTimeout(reRenderAll, 350);
        });

        // ── GOTO ──────────────────────────────────────────────────────────────────
        function toggleGoto() {
            var b = document.getElementById('gt-bar');
            b.classList.toggle('on');
            if (b.classList.contains('on')) {
                document.getElementById('srch-bar').classList.remove('on');
                document.getElementById('btn-srch').classList.remove('on');
                document.getElementById('btn-gt').classList.add('on');
                document.getElementById('gt-in').focus();
            } else {
                document.getElementById('btn-gt').classList.remove('on');
            }
        }

        function doGoto() {
            var n = +document.getElementById('gt-in').value;
            if (n >= 1 && n <= totPages) goPage(n);
            document.getElementById('gt-bar').classList.remove('on');
            document.getElementById('btn-gt').classList.remove('on');
            document.getElementById('gt-in').value = '';
        }

        // ── SEARCH ────────────────────────────────────────────────────────────────
        var srchMatches = [],
            srchCurrent = -1,
            srchCache = {};

        function toggleSearch() {
            var b = document.getElementById('srch-bar');
            b.classList.toggle('on');
            if (b.classList.contains('on')) {
                document.getElementById('gt-bar').classList.remove('on');
                document.getElementById('btn-gt').classList.remove('on');
                document.getElementById('btn-srch').classList.add('on');
                document.getElementById('srch-in').focus();
            } else {
                clearSearch();
                document.getElementById('btn-srch').classList.remove('on');
            }
        }

        async function doSearch(q) {
            clearHighlights();
            srchMatches = [];
            srchCurrent = -1;
            document.getElementById('srch-cnt').textContent = '';
            if (!q || q.length < 2) return;

            for (var p = 1; p <= totPages; p++) {
                var items = await searchPage(p, q);
                if (items.length) srchMatches.push({
                    page: p,
                    items: items
                });
            }

            if (srchMatches.length) {
                srchCurrent = 0;
                reDrawSearch();
                goToMatch(0);
            }
            var tot = srchMatches.reduce(function(a, m) {
                return a + m.items.length;
            }, 0);
            document.getElementById('srch-cnt').textContent = tot ? tot + ' found' : 'Not found';
        }

        async function searchPage(n, q) {
            if (!srchCache[n]) {
                var pg = await pdfDoc.getPage(n);
                srchCache[n] = await pg.getTextContent();
            }
            var content = srchCache[n];
            var scale = parseFloat(document.getElementById('pw-' + n) && document.getElementById('pw-' + n).dataset
                .scale || 1);
            var results = [],
                ql = q.toLowerCase();

            content.items.forEach(function(item) {
                if (!item.str) return;
                var sl = item.str.toLowerCase(),
                    idx = sl.indexOf(ql);
                while (idx !== -1) {
                    var cw = item.width / (item.str.length || 1);
                    var x = (item.transform[4] + idx * cw) * scale;
                    var y = item.transform[5] * scale;
                    var iw = ql.length * cw * scale;
                    var ih = item.height * scale;
                    var pH = document.getElementById('pw-' + n) ? document.getElementById('pw-' + n)
                        .offsetHeight : 0;
                    results.push({
                        x: x,
                        y: pH - y - ih,
                        w: iw,
                        h: Math.max(ih, 12)
                    });
                    idx = sl.indexOf(ql, idx + 1);
                }
            });
            return results;
        }

        function reDrawSearch() {
            clearHighlights();
            srchMatches.forEach(function(match, mi) {
                var hl = document.getElementById('hl-' + match.page);
                if (!hl) return;
                match.items.forEach(function(r) {
                    var d = document.createElement('div');
                    d.className = 'srch-hl' + (mi === srchCurrent ? ' cur' : '');
                    d.style.cssText = 'left:' + r.x + 'px;top:' + r.y + 'px;width:' + r.w + 'px;height:' + r
                        .h + 'px';
                    hl.appendChild(d);
                });
            });
        }

        function clearHighlights() {
            document.querySelectorAll('.hl-layer').forEach(function(l) {
                l.innerHTML = '';
            });
        }

        function goToMatch(idx) {
            if (!srchMatches.length) return;
            idx = ((idx % srchMatches.length) + srchMatches.length) % srchMatches.length;
            srchCurrent = idx;
            reDrawSearch();
            goPage(srchMatches[idx].page);
        }

        function nextMatch() {
            goToMatch(srchCurrent + 1);
        }

        function prevMatch() {
            goToMatch(srchCurrent - 1);
        }

        function clearSearch() {
            clearHighlights();
            srchMatches = [];
            srchCurrent = -1;
            document.getElementById('srch-in').value = '';
            document.getElementById('srch-cnt').textContent = '';
        }

        // ── PAGE OVERVIEW (full-screen thumbnail grid, like a native PDF app) ────
        // Tapping any thumbnail jumps straight to that page and closes the overview.
        // Thumbnails render lazily as they scroll into view so opening this on a
        // long document (hundreds of pages) still stays fast.
        var thumbRendered = {};
        var thumbObserver = null;
        var pageListBuilt = false;

        function openPageOverview() {
            if (!pageListBuilt && totPages) {
                renderPageList();
                pageListBuilt = true;
            }
            document.getElementById('pg-overview').classList.add('on');
            updateActivePageInList();
            setTimeout(renderVisibleThumbs, 50); // fallback in case the observer hasn't caught up yet
        }

        function closePageOverview() {
            document.getElementById('pg-overview').classList.remove('on');
        }

        function pgoTab(tab) {
            document.querySelectorAll('.pgo-tab').forEach(function(t, i) {
                t.classList.toggle('on', ['pages', 'info'][i] === tab);
            });
            document.getElementById('pgo-body-pages').classList.toggle('on', tab === 'pages');
            document.getElementById('pgo-body-info').classList.toggle('on', tab === 'info');
        }

        function renderPageList() {
            var list = document.getElementById('pg-list');
            var ratio = estW ? (estH / estW) : 1.3; // fallback aspect if not known yet
            var html = '';
            for (var i = 1; i <= totPages; i++) {
                html += '<div class="pgl-item' + (i === curPage ? ' active' : '') + '" id="pgl-' + i + '" data-page="' + i +
                    '" onclick="goPage(' + i + ');closePageOverview();">' +
                    '<div class="pgl-thumb" id="pglt-' + i + '" style="aspect-ratio:' + (1 / ratio).toFixed(3) + '">' +
                    '<span class="pgl-num">' + i + '</span>' +
                    '</div>' +
                    '<span class="pgl-lbl">Page ' + i + '</span>' +
                    '</div>';
            }
            list.innerHTML = html;
            setupThumbObserver();
        }

        function setupThumbObserver() {
            if (thumbObserver) thumbObserver.disconnect();
            thumbObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) renderThumb(+e.target.dataset.page);
                });
            }, {
                root: document.getElementById('pgo-body-pages'), // the actual scrolling element
                rootMargin: '250px 0px 250px 0px',
                threshold: 0.01
            });
            document.querySelectorAll('.pgl-item').forEach(function(el) {
                thumbObserver.observe(el);
            });
        }

        // Fallback: explicitly render whatever thumbnails are currently visible.
        // Covers the moment the overview was just opened (display:none -> flex)
        // before the IntersectionObserver has re-checked geometry.
        function renderVisibleThumbs() {
            var panel = document.getElementById('pgo-body-pages');
            if (!panel || !panel.classList.contains('on')) return;
            var pRect = panel.getBoundingClientRect();
            document.querySelectorAll('.pgl-item').forEach(function(el) {
                var r = el.getBoundingClientRect();
                if (r.bottom > pRect.top - 250 && r.top < pRect.bottom + 250) {
                    renderThumb(+el.dataset.page);
                }
            });
        }

        async function renderThumb(n) {
            if (thumbRendered[n]) return;
            thumbRendered[n] = true; // reserve immediately so we don't double-render
            var box = document.getElementById('pglt-' + n);
            if (!box) return;

            try {
                var page = await pdfDoc.getPage(n);
                var bvp = page.getViewport({
                    scale: 1
                });
                var tw = box.clientWidth || 120;
                var vp = page.getViewport({
                    scale: tw / bvp.width
                });

                var canvas = document.createElement('canvas');
                canvas.className = 'pgl-canvas';
                canvas.width = Math.floor(vp.width);
                canvas.height = Math.floor(vp.height);

                var ctx = canvas.getContext('2d', {
                    alpha: false
                });
                await page.render({
                    canvasContext: ctx,
                    viewport: vp
                }).promise;

                box.innerHTML = '';
                box.appendChild(canvas);
            } catch (e) {
                console.error('Thumb ' + n + ' error:', e);
                thumbRendered[n] = false; // allow retry (e.g. via IntersectionObserver re-trigger)
            }
        }

        function updateActivePageInList() {
            document.querySelectorAll('.pgl-item').forEach(function(el) {
                el.classList.toggle('active', el.id === 'pgl-' + curPage);
            });
            var active = document.getElementById('pgl-' + curPage);
            if (active) active.scrollIntoView({
                block: 'center'
            });
        }

        document.getElementById('pgo-body-pages')?.addEventListener('scroll', function() {
            renderVisibleThumbs();
        });

        // ── TOKEN REFRESH ─────────────────────────────────────────────────────────
        // Shared by both the periodic background refresh and the load-retry flow,
        // so a retry never keeps hammering the server with an already-expired token.
        async function refreshToken() {
            var controller = new AbortController();
            var timeoutId = setTimeout(function() {
                controller.abort();
            }, 15000); // 15s cap
            try {
                var r = await fetch(REFRESH_URL, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Content-Type': 'application/json'
                    },
                    signal: controller.signal
                });
                var d = await r.json();
                if (d.token) {
                    TOKEN = d.token;
                    expiry = d.expires_in;
                    return true;
                }
            } catch (e) {
                console.warn('Token refresh failed:', e);
            } finally {
                clearTimeout(timeoutId);
            }
            return false;
        }

        setInterval(function() {
            refreshToken();
        }, 4 * 60 * 1000);

        // ── COUNTDOWN ─────────────────────────────────────────────────────────────
        setInterval(function() {
            if (expiry <= 0) return;
            expiry--;
            var m = String(Math.floor(expiry / 60)).padStart(2, '0');
            var s = String(expiry % 60).padStart(2, '0');
            var el = document.getElementById('countdown');
            el.textContent = m + ':' + s;
            el.style.color = expiry < 60 ? '#d9534f' : '#f8b84a';
        }, 1000);

        // ── SECURITY ──────────────────────────────────────────────────────────────
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
        document.addEventListener('copy', function(e) {
            e.preventDefault();
        });
        document.addEventListener('cut', function(e) {
            e.preventDefault();
        });
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
        });

        // print block via CSS
        var ps = document.createElement('style');
        ps.textContent =
            '@media print { * { display:none!important; } body::before { content:"This document is protected."; display:block!important; text-align:center; margin-top:100px; font-size:20px; } }';
        document.head.appendChild(ps);

        window.addEventListener('beforeprint', function(e) {
            @if (!$pdf->allow_print)
                e.preventDefault();
                showWarn('Printing is disabled.');
            @endif
        });

        document.addEventListener('keydown', function(e) {
            var k = e.key ? e.key.toLowerCase() : '';
            var cm = e.ctrlKey || e.metaKey;
            if (cm && k === 'p') {
                @if (!$pdf->allow_print)
                    e.preventDefault();
                    showWarn('Printing disabled.');
                @endif
            }
            if (cm && k === 's') {
                e.preventDefault();
                showWarn('Saving disabled.');
            }
            if (cm && k === 'u') {
                e.preventDefault();
            }
            if (cm && k === 'a') {
                e.preventDefault();
            }
            if (cm && k === 'c') {
                e.preventDefault();
            }
            if (k === 'f12') {
                e.preventDefault();
                showWarn('DevTools disabled.');
            }
            if (k === 'escape') {
                closePageOverview();
            }
            if (cm && e.shiftKey && (k === 'i' || k === 'j' || k === 'c' || k === 'k')) {
                e.preventDefault();
                showWarn('DevTools disabled.');
            }
            if (cm && k === 'f') {
                e.preventDefault();
                toggleSearch();
            }
            if (k === 'printscreen' || k === 'print screen') {
                e.preventDefault();
                blankAll();
                if (navigator.clipboard) navigator.clipboard.writeText('').catch(function() {});
                setTimeout(reRenderAll, 800);
            }
        });

        // DevTools size detection (desktop only — this heuristic is unreliable on
        // mobile where the browser chrome resizing the viewport can false-trigger it)
        (function() {
            var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
            if (isMobile) return;
            var open = false;
            setInterval(function() {
                var w = window.outerWidth - window.innerWidth > 160;
                var h = window.outerHeight - window.innerHeight > 160;
                if ((w || h) && !open) {
                    open = true;
                    blankAll();
                    showWarn('DevTools detected. Content hidden.');
                }
                if (!w && !h && open) {
                    open = false;
                    closeWarn();
                    reRenderAll();
                }
            }, 800);
        })();

        // Debugger trap (desktop only — negligible cost when devtools are closed,
        // but skip on mobile since some in-app browsers handle rapid intervals poorly)
        (function() {
            var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
            if (isMobile) return;
            setInterval(function() {
                debugger;
            }, 100);
        })();

        // Blur — blank when window loses focus (snipping tool, alt+tab)
        window.addEventListener('blur', function() {
            blankAll();
        });
        window.addEventListener('focus', function() {
            setTimeout(reRenderAll, 300);
        });

        // Tab visibility
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                blankAll();
            } else {
                setTimeout(reRenderAll, 200);
            }
        });

        function showWarn(msg) {
            document.getElementById('warn-msg').textContent = msg || 'Security alert.';
            document.getElementById('ov-warn').classList.add('on');
        }

        function closeWarn() {
            document.getElementById('ov-warn').classList.remove('on');
        }

        // ── BOOT ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', loadPdf);
    </script>
@endpush
