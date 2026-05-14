@extends('frontend.dashboard.app')
@section('title', $pdf->title)

@section('css')
<style>
* {
    -webkit-user-select: none !important;
    -moz-user-select:    none !important;
    user-select:         none !important;
}
body { overflow: hidden; }

#sv {
    position: fixed; inset: 0;
    background: #111827;
    display: flex; flex-direction: column;
    z-index: 9000;
}

/* Toolbar */
#sv-bar {
    height: 50px; background: #1f2937;
    border-bottom: 1px solid #374151;
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 0 14px; flex-shrink: 0; z-index: 9001;
}
#sv-bar .tl, #sv-bar .tr {
    display: flex; align-items: center; gap: 6px;
}
.sv-badge {
    background: #dc2626; color: #fff;
    font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; letter-spacing: 1px;
}
.sv-title {
    color: #f9fafb; font-size: 14px; font-weight: 600;
    max-width: 260px; overflow: hidden;
    text-overflow: ellipsis; white-space: nowrap;
}
.sv-btn {
    background: #374151; color: #e5e7eb;
    border: none; border-radius: 6px;
    padding: 5px 11px; font-size: 13px; cursor: pointer;
}
.sv-btn:hover     { background: #4b5563; }
.sv-btn:disabled  { opacity: .35; cursor: not-allowed; }
#sv-pinfo {
    color: #9ca3af; font-size: 13px;
    padding: 0 4px; min-width: 70px; text-align: center;
}
#sv-goto {
    width: 44px; background: #374151;
    border: 1px solid #4b5563; color: #fff;
    border-radius: 6px; padding: 4px; text-align: center; font-size: 13px;
}
#sv-zlbl {
    color: #9ca3af; font-size: 13px;
    min-width: 38px; text-align: center;
}

/* Canvas area */
#sv-area {
    flex: 1; overflow: auto;
    display: flex; justify-content: center;
    align-items: flex-start; padding: 20px; position: relative;
}
#sv-canvas {
    display: none;
    box-shadow: 0 8px 40px rgba(0,0,0,.7);
    border-radius: 4px;
}

/* Shield blocks all mouse interaction on canvas */
#sv-shield {
    position: absolute; inset: 0;
    z-index: 10; pointer-events: all; cursor: default;
}

/* Loading */
#sv-load {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    color: #9ca3af; text-align: center; z-index: 5;
}
.sv-spin {
    width: 34px; height: 34px; margin: 0 auto 10px;
    border: 3px solid #374151; border-top-color: #3b82f6;
    border-radius: 50%;
    animation: svspin .8s linear infinite;
}
@keyframes svspin { to { transform: rotate(360deg); } }

/* DOM watermark */
#sv-wm {
    position: absolute; inset: 0;
    pointer-events: none; z-index: 8; overflow: hidden;
}
#sv-wm span {
    position: absolute; font-size: 12px;
    color: rgba(255,255,255,0.05);
    white-space: nowrap; transform: rotate(-25deg);
    font-family: monospace;
}

/* Block screens */
.sv-screen {
    display: none; position: fixed; inset: 0;
    background: #0f172a; z-index: 99999;
    justify-content: center; align-items: center;
    flex-direction: column; text-align: center; color: #fff;
}
.sv-screen.show { display: flex; }
.sv-screen .ic  { font-size: 56px; margin-bottom: 14px; }
.sv-screen h4   { font-size: 20px; font-weight: 700; color: #ef4444; margin-bottom: 8px; }
.sv-screen p    { font-size: 14px; color: #9ca3af; max-width: 320px; margin-bottom: 20px; }
.sv-screen button {
    background: #3b82f6; color: #fff; border: none;
    border-radius: 8px; padding: 10px 26px;
    font-size: 14px; cursor: pointer;
}

@media print {
    * { display: none !important; }
    body::before {
        display: block !important;
        content: "Printing is not allowed. This document is protected.";
        font-size: 20px; color: black; padding: 40px;
    }
}
</style>
@endsection

@section('content')

{{-- DevTools Screen --}}
<div class="sv-screen" id="sv-devtools">
    <div class="ic">🔒</div>
    <h4>Developer Tools Detected</h4>
    <p>Close developer tools to continue viewing this protected document.</p>
    <button onclick="location.reload()">Reload Viewer</button>
</div>

{{-- Expired Screen --}}
<div class="sv-screen" id="sv-expired">
    <div class="ic">⏱</div>
    <h4>Session Expired</h4>
    <p>Your secure session has timed out. Please reload to continue.</p>
    <button onclick="location.reload()">Reload</button>
</div>

{{-- Main Viewer --}}
<div id="sv">
    <div id="sv-bar">
        <div class="tl">
            <span class="sv-badge">SECURED</span>
            <span class="sv-title">{{ $pdf->title }}</span>
        </div>
        <div class="tr">
            <button class="sv-btn" onclick="svZoom(-0.15)">−</button>
            <span id="sv-zlbl">100%</span>
            <button class="sv-btn" onclick="svZoom(+0.15)">+</button>
            <div style="width:1px;height:22px;background:#374151;margin:0 3px;"></div>
            <button class="sv-btn" id="sv-prev"
                    onclick="svGoto(svPage-1)" disabled>&#8592;</button>
            <span id="sv-pinfo">— / —</span>
            <button class="sv-btn" id="sv-next"
                    onclick="svGoto(svPage+1)" disabled>&#8594;</button>
            <input id="sv-goto" type="number" min="1" placeholder="#"
                   onkeydown="if(event.key==='Enter') svGoto(parseInt(this.value))">
            <button class="sv-btn"
                    onclick="svGoto(parseInt(document.getElementById('sv-goto').value))">
                Go
            </button>
        </div>
    </div>

    <div id="sv-area">
        <div id="sv-load">
            <div class="sv-spin"></div>
            <div style="font-size:14px;">Loading secure document...</div>
        </div>
        <canvas id="sv-canvas"></canvas>
        <div id="sv-wm"></div>
        <div id="sv-shield"
             oncontextmenu="return false;"
             ondragstart="return false;"
             onselectstart="return false;"></div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
var C = {
    token:   @json($token),
    email:   @json(auth()->user()->email),
    print:   @json($pdf->allow_print),
    stream:  '{{ route("secure-pdfs.stream", $pdf->slug) }}',
    refresh: '{{ route("secure-pdfs.token.refresh", $pdf->slug) }}',
    csrf:    document.querySelector('meta[name="csrf-token"]')?.content ?? '',
};

var svPage = 1, svTotal = 0, svZoomLvl = 1.0;
var svToken = C.token, svRendering = false;
var svDoc = null, svDevOpen = false, svTimer = null;

document.addEventListener('DOMContentLoaded', function () {
    buildWatermark();
    lockdown();
    detectDevTools();
    loadPdf();
    scheduleRefresh();
});

// Load PDF via PDF.js
function loadPdf() {
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    pdfjsLib.getDocument({
        url: C.stream + '?token=' + svToken + '&t=' + Date.now(),
        httpHeaders: { 'X-Viewer-Token': svToken },
    }).promise.then(function (doc) {
        svDoc  = doc;
        svTotal = doc.numPages;
        svGoto(1);
    }).catch(function (e) {
        if (e && (e.status === 401 || e.status === 403)) {
            showExpired();
        } else {
            document.getElementById('sv-load').innerHTML =
                '<p style="color:#ef4444">Failed to load. Please reload.</p>';
        }
    });
}

// Render page to canvas
function renderPage(num) {
    if (!svDoc || svRendering) return;
    svRendering = true;

    document.getElementById('sv-load').style.display   = 'block';
    document.getElementById('sv-canvas').style.display = 'none';

    svDoc.getPage(num).then(function (page) {
        var vp  = page.getViewport({ scale: svZoomLvl * 1.5 });
        var cvs = document.getElementById('sv-canvas');
        var ctx = cvs.getContext('2d');

        cvs.width        = vp.width;
        cvs.height       = vp.height;
        cvs.style.width  = (vp.width  / 1.5) + 'px';
        cvs.style.height = (vp.height / 1.5) + 'px';

        page.render({
            canvasContext: ctx,
            viewport: vp,
            enableScripting: false,
        }).promise.then(function () {
            burnWatermark(ctx, vp.width, vp.height);
            document.getElementById('sv-load').style.display   = 'none';
            document.getElementById('sv-canvas').style.display = 'block';
            svRendering = false;
            svPage      = num;
            updateUI();
        });
    }).catch(function () { svRendering = false; });
}

// Burn watermark into canvas pixels
function burnWatermark(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.07;
    ctx.fillStyle   = '#1e3a8a';
    ctx.font        = Math.max(12, Math.round(w * 0.022)) + 'px monospace';
    ctx.translate(w / 2, h / 2);
    ctx.rotate(-Math.PI / 6);
    ctx.textAlign = 'center';
    var t = C.email + '   |   PROTECTED DOCUMENT';
    for (var i = -8; i <= 8; i++) {
        var y = i * (h / 8);
        ctx.fillText(t,        0, y);
        ctx.fillText(t, -w * 0.6, y);
        ctx.fillText(t,  w * 0.6, y);
    }
    ctx.restore();
}

// Navigation
function svGoto(num) {
    num = parseInt(num);
    if (!num || num < 1 || (svTotal > 0 && num > svTotal)) return;
    renderPage(num);
    document.getElementById('sv-goto').value = '';
}

function svZoom(delta) {
    svZoomLvl = Math.max(0.5, Math.min(3.0, +(svZoomLvl + delta).toFixed(2)));
    document.getElementById('sv-zlbl').textContent =
        Math.round(svZoomLvl * 100) + '%';
    if (svDoc) renderPage(svPage);
}

function updateUI() {
    document.getElementById('sv-pinfo').textContent = svPage + ' / ' + svTotal;
    document.getElementById('sv-prev').disabled = (svPage <= 1);
    document.getElementById('sv-next').disabled = (svPage >= svTotal);
}

// DOM watermark
function buildWatermark() {
    var wm   = document.getElementById('sv-wm');
    var text = C.email + ' | PROTECTED';
    var html = '';
    for (var y = 0; y < 120; y += 14) {
        for (var x = -20; x < 120; x += 35) {
            html += '<span style="top:' + y + '%;left:' + x + '%">'
                  + text + '</span>';
        }
    }
    wm.innerHTML = html;
}

// Full lockdown
function lockdown() {
    ['contextmenu','dragstart','drop','selectstart','copy','cut']
        .forEach(function (ev) {
            document.addEventListener(ev, function (e) {
                e.preventDefault();
            }, true);
        });

    document.addEventListener('keydown', function (e) {
        var ctrl  = e.ctrlKey || e.metaKey;
        var shift = e.shiftKey;
        var k     = e.key.toLowerCase();

        var blocked =
            (ctrl && k === 's') ||
            (ctrl && k === 'p') ||
            (ctrl && k === 'c') ||
            (ctrl && k === 'a') ||
            (ctrl && k === 'u') ||
            (ctrl && shift && k === 'i') ||
            (ctrl && shift && k === 'j') ||
            (ctrl && shift && k === 'c') ||
            (ctrl && shift && k === 'k') ||
            (e.key === 'F12') ||
            (e.key === 'PrintScreen');

        if (blocked) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }

        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') svGoto(svPage + 1);
        if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   svGoto(svPage - 1);
    }, true);

    if (!C.print) {
        window.addEventListener('beforeprint', function (e) {
            e.preventDefault();
        });
    }

    var blurCanvas = function () {
        var c = document.getElementById('sv-canvas');
        if (c) c.style.filter = 'blur(24px)';
    };
    var unblurCanvas = function () {
        var c = document.getElementById('sv-canvas');
        if (c && !svDevOpen) c.style.filter = 'none';
    };

    window.addEventListener('blur', blurCanvas);
    window.addEventListener('focus', unblurCanvas);
    document.addEventListener('visibilitychange', function () {
        document.hidden ? blurCanvas() : unblurCanvas();
    });
}

// DevTools detection — 3 methods
function detectDevTools() {
    setInterval(function () {
        // Method 1: Window size gap
        if (window.outerWidth  - window.innerWidth  > 160 ||
            window.outerHeight - window.innerHeight > 160) {
            openDevTools(); return;
        }
        // Method 2: Console trap
        var open = false;
        var trap = /./;
        trap.toString = function () { open = true; return ''; };
        console.log('%c', trap);
        if (open) { openDevTools(); return; }
        // Method 3: Debugger timing
        var t = performance.now();
        debugger;
        if (performance.now() - t > 150) { openDevTools(); return; }

        closeDevTools();
    }, 1000);
}

function openDevTools() {
    if (svDevOpen) return;
    svDevOpen = true;
    document.getElementById('sv-devtools').classList.add('show');
    var c = document.getElementById('sv-canvas');
    if (c) c.style.filter = 'blur(30px)';
}

function closeDevTools() {
    if (!svDevOpen) return;
    svDevOpen = false;
    document.getElementById('sv-devtools').classList.remove('show');
    var c = document.getElementById('sv-canvas');
    if (c) c.style.filter = 'none';
}

// Token auto-refresh every 25 min
function scheduleRefresh() {
    svTimer = setTimeout(doRefresh, 25 * 60 * 1000);
}

function doRefresh() {
    fetch(C.refresh, {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  C.csrf,
        },
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (d.token) {
            svToken = d.token;
            scheduleRefresh();
        } else {
            showExpired();
        }
    })
    .catch(showExpired);
}

function showExpired() {
    clearTimeout(svTimer);
    document.getElementById('sv-expired').classList.add('show');
}
</script>
@endpush
