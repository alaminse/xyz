@extends('frontend.dashboard.app')
@section('title', 'Secure Viewer — ' . $pdf->title)

@section('css')
<style>
    * { -webkit-user-select: none !important; user-select: none !important; }

    html, body { margin: 0; padding: 0; background: #0a0e1a; overflow: hidden; }

    #secure-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: #0a0e1a;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 16px;
    }
    #secure-overlay.hidden { display: none; }
    #secure-overlay .spinner {
        width: 48px; height: 48px;
        border: 3px solid rgba(248,184,74,.2);
        border-top-color: #f8b84a;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #secure-overlay p { color: #9aa4b2; font-size: 13px; }
    #load-progress {
        width: 200px; height: 4px;
        background: rgba(255,255,255,.1);
        border-radius: 4px; overflow: hidden;
    }
    #load-bar {
        height: 100%; background: #f8b84a;
        border-radius: 4px;
        transition: width .3s;
        width: 0%;
    }

    #warn-overlay {
        display: none; position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,.97);
        align-items: center; justify-content: center;
        flex-direction: column; gap: 16px;
        color: #fff; text-align: center; padding: 20px;
    }
    #warn-overlay.show { display: flex; }
    #warn-overlay i { font-size: 48px; color: #d9534f; }
    #warn-overlay h3 { font-size: 20px; margin: 0; }
    #warn-overlay p { color: #9aa4b2; font-size: 13px; max-width: 340px; margin: 0; }

    #viewer-shell {
        display: flex; flex-direction: column;
        height: 100vh; width: 100vw;
        background: #0a0e1a;
        font-family: 'Segoe UI', sans-serif;
        overflow: hidden;
    }

    #top-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 8px 12px;
        background: linear-gradient(90deg, #020b1c, #03132e);
        border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0; gap: 8px; flex-wrap: wrap; min-height: 48px;
    }
    .pdf-title {
        color: #f8b84a; font-weight: 600; font-size: 13px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        max-width: 180px; flex-shrink: 1;
    }
    #page-nav { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    #page-input {
        width: 42px; text-align: center;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        color: #eaeaea; border-radius: 6px;
        padding: 4px; font-size: 12px;
    }
    #page-input:focus { outline: none; border-color: #f8b84a; }
    .page-meta { color: #9aa4b2; font-size: 12px; }
    #zoom-controls { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    #zoom-label { color: #9aa4b2; font-size: 12px; min-width: 38px; text-align: center; }

    .ctrl-btn {
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        color: #eaeaea; border-radius: 6px;
        padding: 5px 10px; font-size: 13px; cursor: pointer;
        transition: background .2s, color .2s;
        text-decoration: none;
        display: inline-flex; align-items: center; white-space: nowrap;
    }
    .ctrl-btn:hover { background: rgba(248,184,74,.15); color: #f8b84a; }
    .ctrl-btn:disabled { opacity: .35; cursor: default; }

    #canvas-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        background: #1a1f2e;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 16px 8px;
        gap: 16px;
        box-sizing: border-box;
        width: 100%;
        -webkit-overflow-scrolling: touch; /* smooth scroll on iOS */
    }
    #canvas-area::-webkit-scrollbar { width: 4px; }
    #canvas-area::-webkit-scrollbar-thumb { background: rgba(248,184,74,.3); border-radius: 10px; }

    .page-wrap {
        position: relative;
        box-shadow: 0 4px 24px rgba(0,0,0,.7);
        border-radius: 3px;
        overflow: hidden;
        flex-shrink: 0;
        background: #fff; /* white bg while rendering */
    }
    .page-canvas { display: block; }
    .wm-layer {
        position: absolute; inset: 0;
        pointer-events: none; z-index: 2; overflow: hidden;
    }
    .wm-layer svg { width: 100%; height: 100%; }

    #status-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 5px 12px;
        background: #020b1c;
        border-top: 1px solid rgba(255,255,255,.05);
        color: #9aa4b2; font-size: 11px; flex-shrink: 0;
        flex-wrap: wrap; gap: 4px;
    }
    .lock-badge { display: flex; align-items: center; gap: 4px; color: #5cb85c; font-weight: 600; }
    .token-timer { color: #f8b84a; font-weight: 700; }

    @media (max-width: 576px) {
        .pdf-title { max-width: 110px; font-size: 11px; }
        .ctrl-btn { padding: 4px 7px; font-size: 11px; }
        #status-bar { font-size: 10px; }
        #canvas-area { padding: 8px 4px; gap: 8px; }
    }
</style>
@endsection

@section('content')

<div id="secure-overlay">
    <div class="spinner"></div>
    <p id="load-msg">Downloading secure PDF…</p>
    <div id="load-progress"><div id="load-bar"></div></div>
</div>

<div id="warn-overlay">
    <i class="bx bx-shield-x"></i>
    <h3>Security Alert</h3>
    <p id="warn-msg">Suspicious activity detected. Viewer paused.</p>
    <button class="ctrl-btn" onclick="closeWarn()">Resume Reading</button>
</div>

<div id="viewer-shell">

    <div id="top-bar">
        <div class="pdf-title">
            <i class="bx bx-file-pdf" style="color:#d9534f;"></i>
            {{ $pdf->title }}
        </div>

        <div id="page-nav">
            <button class="ctrl-btn" id="btn-prev" onclick="goPage(currentPage-1)">&#8249;</button>
            <input id="page-input" type="number" min="1" value="1"
                   onchange="goPage(parseInt(this.value))">
            <span class="page-meta">/ <span id="total-pages">—</span></span>
            <button class="ctrl-btn" id="btn-next" onclick="goPage(currentPage+1)">&#8250;</button>
        </div>

        <div id="zoom-controls">
            <button class="ctrl-btn" onclick="changeZoom(-0.1)">&#8722;</button>
            <span id="zoom-label">Auto</span>
            <button class="ctrl-btn" onclick="changeZoom(+0.1)">&#43;</button>
            <a href="{{ route('secure-pdfs.index', request()->segment(2)) }}"
               class="ctrl-btn">&#8592; Back</a>
        </div>
    </div>

    <div id="canvas-area"></div>

    <div id="status-bar">
        <div class="lock-badge">
            <i class="bx bx-lock-alt"></i>
            Protected &bull; No download &bull; No copy
        </div>
        <div>Expires: <span class="token-timer" id="token-countdown">05:00</span></div>
        <div class="d-none d-md-block">{{ auth()->user()->name }}</div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const STREAM_URL  = @json(route('secure-pdfs.stream', $pdf->slug));
const REFRESH_URL = @json(route('secure-pdfs.token.refresh', $pdf->slug));
const CSRF        = @json(csrf_token());
const USER_NAME   = @json(auth()->user()->name);
const USER_EMAIL  = @json(auth()->user()->email);

let TOKEN       = @json($token);
let pdfDoc      = null;
let totalPages  = 0;
let currentPage = 1;
let zoomDelta   = 0;
let tokenExpiry = 5 * 60;
let rendering   = false;

// ── FETCH PDF AS ARRAYBUFFER ─────────────────────────────
// This is the key fix — mobile browsers can't handle
// octet-stream URLs directly in PDF.js, so we fetch
// the raw bytes first then pass the ArrayBuffer to PDF.js
async function fetchPdfBytes() {
    const url = `${STREAM_URL}?token=${TOKEN}`;

    const res = await fetch(url, {
        method: 'GET',
        credentials: 'include',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    // Read with progress
    const contentLength = res.headers.get('Content-Length');
    const reader = res.body.getReader();
    const chunks = [];
    let received = 0;

    while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        chunks.push(value);
        received += value.length;

        if (contentLength) {
            const pct = Math.round((received / parseInt(contentLength)) * 100);
            document.getElementById('load-bar').style.width = pct + '%';
            document.getElementById('load-msg').textContent =
                `Downloading… ${pct}%`;
        }
    }

    // Merge chunks into single ArrayBuffer
    const total  = chunks.reduce((a, c) => a + c.length, 0);
    const buffer = new Uint8Array(total);
    let offset = 0;
    for (const chunk of chunks) {
        buffer.set(chunk, offset);
        offset += chunk.length;
    }

    return buffer.buffer;
}

// ── LOAD PDF ────────────────────────────────────
async function loadPdf() {
    try {
        document.getElementById('load-msg').textContent = 'Downloading secure PDF…';
        document.getElementById('load-bar').style.width = '0%';

        const arrayBuffer = await fetchPdfBytes();

        document.getElementById('load-msg').textContent = 'Rendering pages…';
        document.getElementById('load-bar').style.width = '100%';

        // Pass ArrayBuffer directly — works on all browsers including mobile
        pdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;

        totalPages = pdfDoc.numPages;
        document.getElementById('total-pages').textContent = totalPages;
        document.getElementById('page-input').max = totalPages;

        await renderAll();

    } catch (e) {
        console.error('PDF load error:', e);
        document.getElementById('load-msg').textContent =
            'Failed to load. Please refresh the page.';
        document.getElementById('load-bar').style.background = '#d9534f';
    }
}

// ── RENDER ALL PAGES ────────────────────────────
async function renderAll() {
    if (!pdfDoc || rendering) return;
    rendering = true;

    const area = document.getElementById('canvas-area');
    area.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        await renderPage(i, area);
    }

    rendering = false;
    document.getElementById('secure-overlay').classList.add('hidden');
    updateNav();
}

async function renderPage(pageNum, container) {
    const page = await pdfDoc.getPage(pageNum);

    // Fit to container width
    const area          = document.getElementById('canvas-area');
    const containerW    = area.clientWidth - 16;
    const baseViewport  = page.getViewport({ scale: 1 });
    const fitScale      = containerW / baseViewport.width;
    const finalScale    = Math.max(0.3, fitScale + zoomDelta);
    const viewport      = page.getViewport({ scale: finalScale });

    const wrap = document.createElement('div');
    wrap.className = 'page-wrap';
    wrap.id = `page-wrap-${pageNum}`;
    wrap.style.width  = viewport.width  + 'px';
    wrap.style.height = viewport.height + 'px';

    const canvas = document.createElement('canvas');
    canvas.className = 'page-canvas';

    // Use devicePixelRatio for sharp rendering on mobile retina screens
    const dpr = window.devicePixelRatio || 1;
    canvas.width  = viewport.width  * dpr;
    canvas.height = viewport.height * dpr;
    canvas.style.width  = viewport.width  + 'px';
    canvas.style.height = viewport.height + 'px';

    const ctx = canvas.getContext('2d', { alpha: false });
    ctx.scale(dpr, dpr);

    await page.render({ canvasContext: ctx, viewport }).promise;

    burnWatermark(ctx, viewport.width, viewport.height);

    wrap.appendChild(canvas);

    const wm = document.createElement('div');
    wm.className = 'wm-layer';
    wm.innerHTML = svgWatermark(viewport.width, viewport.height);
    wrap.appendChild(wm);

    container.appendChild(wrap);
}

// ── WATERMARK ───────────────────────────────────
function burnWatermark(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.07;
    ctx.font = `bold ${Math.max(12, w * 0.028)}px Arial`;
    ctx.fillStyle = '#c0392b';
    ctx.translate(w / 2, h / 2);
    ctx.rotate(-Math.PI / 6);
    ctx.translate(-w / 2, -h / 2);
    const text = `${USER_NAME} • ${USER_EMAIL}`;
    for (let y = -h; y < h * 2; y += 180) {
        for (let x = -w; x < w * 2; x += 200) {
            ctx.fillText(text, x, y);
        }
    }
    ctx.restore();
}

function svgWatermark(w, h) {
    return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
      <defs>
        <pattern id="wmp" x="0" y="0" width="220" height="110"
                 patternUnits="userSpaceOnUse" patternTransform="rotate(-25)">
          <text x="8" y="60" font-family="Arial" font-size="12" font-weight="bold"
                fill="rgba(192,57,43,0.06)">${USER_NAME}</text>
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#wmp)"/>
    </svg>`;
}

// ── ZOOM ────────────────────────────────────────
async function changeZoom(delta) {
    zoomDelta = +(zoomDelta + delta).toFixed(1);
    const pct = zoomDelta === 0 ? 'Auto'
        : (zoomDelta > 0 ? '+' : '') + Math.round(zoomDelta * 100) + '%';
    document.getElementById('zoom-label').textContent = pct;
    await renderAll();
}

// ── PAGE NAV ────────────────────────────────────
function goPage(n) {
    if (!pdfDoc) return;
    n = Math.max(1, Math.min(totalPages, n));
    currentPage = n;
    document.getElementById('page-input').value = n;
    document.getElementById(`page-wrap-${n}`)
        ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    updateNav();
}

function updateNav() {
    document.getElementById('btn-prev').disabled = currentPage <= 1;
    document.getElementById('btn-next').disabled = currentPage >= totalPages;
}

document.getElementById('canvas-area')?.addEventListener('scroll', function () {
    const areaTop = this.getBoundingClientRect().top;
    let closest = 1, minDist = Infinity;
    document.querySelectorAll('.page-wrap').forEach((w, i) => {
        const dist = Math.abs(w.getBoundingClientRect().top - areaTop);
        if (dist < minDist) { minDist = dist; closest = i + 1; }
    });
    if (closest !== currentPage) {
        currentPage = closest;
        document.getElementById('page-input').value = closest;
        updateNav();
    }
});

// Re-render on resize/rotation
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => renderAll(), 400);
});

// ── TOKEN REFRESH ───────────────────────────────
setInterval(async () => {
    try {
        const res  = await fetch(REFRESH_URL, {
            method: 'POST',
            credentials: 'include',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        });
        const data = await res.json();
        if (data.token) { TOKEN = data.token; tokenExpiry = data.expires_in; }
    } catch (e) { /* silent */ }
}, 4 * 60 * 1000);

// ── COUNTDOWN ───────────────────────────────────
function startCountdown() {
    const el = document.getElementById('token-countdown');
    setInterval(() => {
        if (tokenExpiry <= 0) return;
        tokenExpiry--;
        const m = String(Math.floor(tokenExpiry / 60)).padStart(2, '0');
        const s = String(tokenExpiry % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
        el.style.color = tokenExpiry < 60 ? '#d9534f' : '#f8b84a';
    }, 1000);
}

// ── SECURITY ────────────────────────────────────
document.addEventListener('contextmenu',  e => e.preventDefault());
document.addEventListener('copy',         e => e.preventDefault());
document.addEventListener('cut',          e => e.preventDefault());
document.addEventListener('selectstart',  e => e.preventDefault());
document.addEventListener('dragstart',    e => e.preventDefault());

window.addEventListener('beforeprint', e => {
    @if(!$pdf->allow_print)
    e.preventDefault();
    showWarn('Printing is disabled for this document.');
    @endif
});

document.addEventListener('keydown', e => {
    const k = e.key.toLowerCase();
    if ((e.ctrlKey || e.metaKey) && k === 'p') {
        @if(!$pdf->allow_print) e.preventDefault(); showWarn('Printing is disabled.'); @endif
    }
    if ((e.ctrlKey || e.metaKey) && k === 's') { e.preventDefault(); showWarn('Saving is disabled.'); }
    if (k === 'printscreen') { e.preventDefault(); navigator.clipboard?.writeText('').catch(() => {}); }
    if (k === 'f12') { e.preventDefault(); showWarn('DevTools disabled.'); }
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i','j','c'].includes(k)) {
        e.preventDefault(); showWarn('DevTools disabled.');
    }
    if ((e.ctrlKey || e.metaKey) && k === 'u') e.preventDefault();
});

(function () {
    let devOpen = false;
    setInterval(() => {
        const w = window.outerWidth  - window.innerWidth  > 160;
        const h = window.outerHeight - window.innerHeight > 160;
        if ((w || h) && !devOpen) { devOpen = true;  showWarn('DevTools detected. Viewer paused.'); }
        if (!w && !h  && devOpen) { devOpen = false; closeWarn(); }
    }, 1000);
})();

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        document.querySelectorAll('.page-canvas').forEach(c => {
            const ctx = c.getContext('2d');
            ctx.fillStyle = '#0a0e1a';
            ctx.fillRect(0, 0, c.width, c.height);
        });
    } else {
        renderAll();
    }
});

function showWarn(msg) {
    document.getElementById('warn-msg').textContent = msg || 'Security alert.';
    document.getElementById('warn-overlay').classList.add('show');
}
function closeWarn() {
    document.getElementById('warn-overlay').classList.remove('show');
}

// ── BOOT ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadPdf();
    startCountdown();
});
</script>
@endpush
