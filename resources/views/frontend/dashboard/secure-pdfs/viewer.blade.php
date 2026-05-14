@extends('frontend.dashboard.app')
@section('title', 'Secure Viewer — ' . $pdf->title)

@section('css')
<style>
    * { -webkit-user-select: none !important; user-select: none !important; box-sizing: border-box; }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #0a0e1a !important;
        overflow: hidden !important;
        height: 100% !important;
        width: 100% !important;
    }

    /* hide parent layout padding/margin that causes overflow */
    .page-content, .right_col, .col-md-12 {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* ── loading overlay ── */
    #secure-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: #0a0e1a;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 12px;
    }
    #secure-overlay.hidden { display: none; }
    .spinner {
        width: 44px; height: 44px;
        border: 3px solid rgba(248,184,74,.2);
        border-top-color: #f8b84a;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #load-msg { color: #9aa4b2; font-size: 13px; margin: 0; }
    #load-progress {
        width: 180px; height: 3px;
        background: rgba(255,255,255,.1); border-radius: 4px; overflow: hidden;
    }
    #load-bar {
        height: 100%; background: #f8b84a; border-radius: 4px;
        transition: width .2s; width: 0%;
    }

    /* ── warn overlay ── */
    #warn-overlay {
        display: none; position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,.97);
        align-items: center; justify-content: center;
        flex-direction: column; gap: 14px;
        color: #fff; text-align: center; padding: 20px;
    }
    #warn-overlay.show { display: flex; }
    #warn-overlay i { font-size: 44px; color: #d9534f; }
    #warn-overlay h3 { font-size: 18px; margin: 0; }
    #warn-overlay p { color: #9aa4b2; font-size: 12px; max-width: 300px; margin: 0; }

    /* ── viewer shell ── */
    #viewer-shell {
        position: fixed;
        inset: 0;
        display: flex;
        flex-direction: column;
        background: #0a0e1a;
        font-family: 'Segoe UI', sans-serif;
    }

    /* ── toolbar ── */
    #toolbar {
        flex-shrink: 0;
        background: linear-gradient(90deg, #020b1c, #03132e);
        border-bottom: 1px solid rgba(255,255,255,.08);
        padding: 6px 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* row 1: title + back */
    #tb-row1 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .pdf-title {
        color: #f8b84a; font-weight: 600; font-size: 13px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        flex: 1; min-width: 0;
    }

    /* row 2: page nav + zoom */
    #tb-row2 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
    }
    #page-nav { display: flex; align-items: center; gap: 5px; }
    #zoom-controls { display: flex; align-items: center; gap: 5px; }

    #page-input {
        width: 38px; text-align: center;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.12);
        color: #eaeaea; border-radius: 5px;
        padding: 3px 2px; font-size: 12px;
        -moz-appearance: textfield;
    }
    #page-input::-webkit-outer-spin-button,
    #page-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    #page-input:focus { outline: none; border-color: #f8b84a; }

    .page-meta { color: #9aa4b2; font-size: 12px; white-space: nowrap; }
    #zoom-label { color: #9aa4b2; font-size: 11px; min-width: 36px; text-align: center; }

    .btn-ctrl {
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.12);
        color: #eaeaea; border-radius: 5px;
        padding: 4px 9px; font-size: 13px; cursor: pointer;
        transition: background .15s, color .15s;
        text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center;
        white-space: nowrap; line-height: 1;
    }
    .btn-ctrl:hover, .btn-ctrl:active { background: rgba(248,184,74,.2); color: #f8b84a; }
    .btn-ctrl:disabled { opacity: .3; cursor: default; }
    .btn-ctrl.back { font-size: 12px; padding: 4px 10px; }

    /* ── canvas scroll area ── */
    #canvas-area {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        background: #1a1f2e;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 12px 6px;
        gap: 12px;
        width: 100%;
        -webkit-overflow-scrolling: touch;
    }
    #canvas-area::-webkit-scrollbar { width: 3px; }
    #canvas-area::-webkit-scrollbar-thumb { background: rgba(248,184,74,.3); border-radius: 4px; }

    .page-wrap {
        position: relative;
        box-shadow: 0 2px 16px rgba(0,0,0,.6);
        border-radius: 2px;
        overflow: hidden;
        flex-shrink: 0;
        background: #fff;
        /* max-width keeps pages from overflowing on wide screens */
        max-width: 100%;
    }
    .page-canvas {
        display: block;
        max-width: 100%;
    }
    .wm-layer {
        position: absolute; inset: 0;
        pointer-events: none; z-index: 2; overflow: hidden;
    }
    .wm-layer svg { width: 100%; height: 100%; }

    /* ── status bar ── */
    #status-bar {
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 4px 10px;
        background: #020b1c;
        border-top: 1px solid rgba(255,255,255,.05);
        color: #9aa4b2; font-size: 10px;
        flex-wrap: wrap; gap: 4px;
    }
    .lock-badge { display: flex; align-items: center; gap: 3px; color: #5cb85c; font-weight: 600; }
    .token-timer { color: #f8b84a; font-weight: 700; }

    /* ── desktop: single-row toolbar ── */
    @media (min-width: 640px) {
        #toolbar {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding: 8px 16px;
            gap: 12px;
        }
        #tb-row1 { flex: 1; min-width: 0; }
        #tb-row2 { flex-shrink: 0; }
        .pdf-title { font-size: 14px; }
        #status-bar { font-size: 11px; }
        #canvas-area { padding: 16px 12px; gap: 16px; }
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
    <button class="btn-ctrl" onclick="closeWarn()">Resume Reading</button>
</div>

<div id="viewer-shell">

    <div id="toolbar">
        <div id="tb-row1">
            <div class="pdf-title">
                <i class="bx bx-file-pdf" style="color:#d9534f;"></i>
                {{ $pdf->title }}
            </div>
            <a href="{{ route('secure-pdfs.details', [
                    'course'  => $pdf->courses->first()?->slug,
                    'chapter' => $pdf->chapter?->slug,
                    'lesson'  => $pdf->lesson?->slug,
               ]) }}"
               class="btn-ctrl back">&#8592; Back</a>
        </div>

        <div id="tb-row2">
            <div id="page-nav">
                <button class="btn-ctrl" id="btn-prev" onclick="goPage(currentPage-1)">&#8249;</button>
                <input id="page-input" type="number" min="1" value="1"
                       onchange="goPage(parseInt(this.value))">
                <span class="page-meta">/ <span id="total-pages">—</span></span>
                <button class="btn-ctrl" id="btn-next" onclick="goPage(currentPage+1)">&#8250;</button>
            </div>

            <div id="zoom-controls">
                <button class="btn-ctrl" onclick="changeZoom(-0.1)">&#8722;</button>
                <span id="zoom-label">Auto</span>
                <button class="btn-ctrl" onclick="changeZoom(+0.1)">&#43;</button>
            </div>
        </div>
    </div>

    <div id="canvas-area"></div>

    <div id="status-bar">
        <div class="lock-badge">
            <i class="bx bx-lock-alt"></i>
            No download &bull; No copy &bull; Watermarked
        </div>
        <div>Expires: <span class="token-timer" id="token-countdown">05:00</span></div>
        <div>{{ auth()->user()->name }}</div>
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

// ── FETCH AS ARRAYBUFFER (fixes mobile blank rendering) ──
async function fetchPdfBytes() {
    const res = await fetch(`${STREAM_URL}?token=${TOKEN}`, {
        method: 'GET',
        credentials: 'include',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);

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
            const pct = Math.min(99, Math.round((received / +contentLength) * 100));
            document.getElementById('load-bar').style.width  = pct + '%';
            document.getElementById('load-msg').textContent  = `Downloading… ${pct}%`;
        }
    }

    const total  = chunks.reduce((a, c) => a + c.length, 0);
    const merged = new Uint8Array(total);
    let offset   = 0;
    for (const chunk of chunks) { merged.set(chunk, offset); offset += chunk.length; }
    return merged.buffer;
}

// ── LOAD ────────────────────────────────────────
async function loadPdf() {
    try {
        const buffer = await fetchPdfBytes();
        document.getElementById('load-msg').textContent = 'Rendering…';
        document.getElementById('load-bar').style.width = '100%';

        pdfDoc = await pdfjsLib.getDocument({ data: buffer }).promise;
        totalPages = pdfDoc.numPages;
        document.getElementById('total-pages').textContent = totalPages;
        document.getElementById('page-input').max = totalPages;

        await renderAll();
    } catch (e) {
        console.error(e);
        document.getElementById('load-msg').textContent = 'Failed to load. Please refresh.';
        document.getElementById('load-bar').style.background = '#d9534f';
    }
}

// ── RENDER ALL ──────────────────────────────────
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
    const page         = await pdfDoc.getPage(pageNum);
    const area         = document.getElementById('canvas-area');
    const containerW   = Math.floor(area.clientWidth - 12); // 6px padding each side
    const baseVp       = page.getViewport({ scale: 1 });
    const fitScale     = containerW / baseVp.width;
    const finalScale   = Math.max(0.3, fitScale + zoomDelta);
    const viewport     = page.getViewport({ scale: finalScale });

    const dpr = window.devicePixelRatio || 1;

    const wrap = document.createElement('div');
    wrap.className = 'page-wrap';
    wrap.id = `page-wrap-${pageNum}`;
    wrap.style.width  = viewport.width + 'px';
    wrap.style.height = viewport.height + 'px';

    const canvas = document.createElement('canvas');
    canvas.className = 'page-canvas';
    // Physical pixels (sharp on retina)
    canvas.width  = Math.floor(viewport.width  * dpr);
    canvas.height = Math.floor(viewport.height * dpr);
    // CSS pixels (layout size)
    canvas.style.width  = viewport.width  + 'px';
    canvas.style.height = viewport.height + 'px';

    const ctx = canvas.getContext('2d', { alpha: false });
    ctx.scale(dpr, dpr);

    await page.render({ canvasContext: ctx, viewport }).promise;
    burnWatermark(ctx, viewport.width, viewport.height);

    const wm = document.createElement('div');
    wm.className = 'wm-layer';
    wm.innerHTML = svgWatermark(viewport.width, viewport.height);

    wrap.appendChild(canvas);
    wrap.appendChild(wm);
    container.appendChild(wrap);
}

// ── WATERMARK ───────────────────────────────────
function burnWatermark(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.07;
    ctx.font = `bold ${Math.max(11, w * 0.026)}px Arial`;
    ctx.fillStyle = '#c0392b';
    ctx.translate(w / 2, h / 2);
    ctx.rotate(-Math.PI / 6);
    ctx.translate(-w / 2, -h / 2);
    const text = `${USER_NAME} • ${USER_EMAIL}`;
    for (let y = -h; y < h * 2; y += 170) {
        for (let x = -w; x < w * 2; x += 190) ctx.fillText(text, x, y);
    }
    ctx.restore();
}

function svgWatermark(w, h) {
    return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
      <defs>
        <pattern id="wmp" x="0" y="0" width="210" height="105"
          patternUnits="userSpaceOnUse" patternTransform="rotate(-25)">
          <text x="6" y="58" font-family="Arial" font-size="11" font-weight="bold"
            fill="rgba(192,57,43,0.055)">${USER_NAME}</text>
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#wmp)"/>
    </svg>`;
}

// ── ZOOM ────────────────────────────────────────
async function changeZoom(delta) {
    zoomDelta = +(zoomDelta + delta).toFixed(1);
    document.getElementById('zoom-label').textContent =
        zoomDelta === 0 ? 'Auto' :
        (zoomDelta > 0 ? '+' : '') + Math.round(zoomDelta * 100) + '%';
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

document.getElementById('canvas-area').addEventListener('scroll', function () {
    const top = this.getBoundingClientRect().top;
    let closest = 1, minD = Infinity;
    document.querySelectorAll('.page-wrap').forEach((w, i) => {
        const d = Math.abs(w.getBoundingClientRect().top - top);
        if (d < minD) { minD = d; closest = i + 1; }
    });
    if (closest !== currentPage) {
        currentPage = closest;
        document.getElementById('page-input').value = closest;
        updateNav();
    }
});

// Re-render on orientation change / resize
let resizeT;
window.addEventListener('resize', () => {
    clearTimeout(resizeT);
    resizeT = setTimeout(renderAll, 350);
});

// ── TOKEN REFRESH ───────────────────────────────
setInterval(async () => {
    try {
        const r = await fetch(REFRESH_URL, {
            method: 'POST', credentials: 'include',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
        });
        const d = await r.json();
        if (d.token) { TOKEN = d.token; tokenExpiry = d.expires_in; }
    } catch (_) {}
}, 4 * 60 * 1000);

// ── COUNTDOWN ───────────────────────────────────
setInterval(() => {
    if (tokenExpiry <= 0) return;
    tokenExpiry--;
    const m = String(Math.floor(tokenExpiry / 60)).padStart(2, '0');
    const s = String(tokenExpiry % 60).padStart(2, '0');
    const el = document.getElementById('token-countdown');
    el.textContent = `${m}:${s}`;
    el.style.color  = tokenExpiry < 60 ? '#d9534f' : '#f8b84a';
}, 1000);

// ── SECURITY ────────────────────────────────────
document.addEventListener('contextmenu',  e => e.preventDefault());
document.addEventListener('copy',         e => e.preventDefault());
document.addEventListener('cut',          e => e.preventDefault());
document.addEventListener('selectstart',  e => e.preventDefault());
document.addEventListener('dragstart',    e => e.preventDefault());

window.addEventListener('beforeprint', e => {
    @if(!$pdf->allow_print)
    e.preventDefault(); showWarn('Printing is disabled.');
    @endif
});

document.addEventListener('keydown', e => {
    const k = e.key.toLowerCase();
    const cm = e.ctrlKey || e.metaKey;
    if (cm && k === 'p') { @if(!$pdf->allow_print) e.preventDefault(); showWarn('Printing disabled.'); @endif }
    if (cm && k === 's') { e.preventDefault(); showWarn('Saving disabled.'); }
    if (k === 'printscreen') { e.preventDefault(); navigator.clipboard?.writeText('').catch(() => {}); }
    if (k === 'f12') { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && e.shiftKey && ['i','j','c'].includes(k)) { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && k === 'u') e.preventDefault();
});

(function devDetect() {
    let open = false;
    setInterval(() => {
        const w = window.outerWidth  - window.innerWidth  > 160;
        const h = window.outerHeight - window.innerHeight > 160;
        if ((w || h) && !open) { open = true;  showWarn('DevTools detected.'); }
        if (!w && !h  && open) { open = false; closeWarn(); }
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
document.addEventListener('DOMContentLoaded', loadPdf);
</script>
@endpush
