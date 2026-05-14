<?php $__env->startSection('title', 'Secure Viewer — ' . $pdf->title); ?>

<?php $__env->startSection('css'); ?>
<style>
    * { -webkit-user-select: none !important; user-select: none !important; }

    body { background: #0a0e1a; }

    #secure-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: #0a0e1a;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 16px;
        transition: opacity .4s;
    }
    #secure-overlay.hidden { opacity: 0; pointer-events: none; }
    #secure-overlay .spinner {
        width: 48px; height: 48px;
        border: 3px solid rgba(248,184,74,.2);
        border-top-color: #f8b84a;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #secure-overlay p { color: #9aa4b2; font-size: 13px; letter-spacing: .5px; }

    /* ── viewer shell ── */
    #viewer-shell {
        display: flex; flex-direction: column;
        height: 100vh; background: #0a0e1a;
        font-family: 'Segoe UI', sans-serif;
    }

    /* ── top bar ── */
    #top-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 20px;
        background: linear-gradient(90deg, #020b1c, #03132e);
        border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0; gap: 12px; flex-wrap: wrap;
    }
    #top-bar .title {
        color: #f8b84a; font-weight: 600; font-size: 14px;
        max-width: 320px; overflow: hidden;
        text-overflow: ellipsis; white-space: nowrap;
    }
    #top-bar .meta {
        color: #9aa4b2; font-size: 12px; flex-shrink: 0;
    }
    #top-bar .controls { display: flex; gap: 8px; align-items: center; }

    .ctrl-btn {
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        color: #eaeaea; border-radius: 6px;
        padding: 5px 12px; font-size: 13px; cursor: pointer;
        transition: background .2s, color .2s;
    }
    .ctrl-btn:hover { background: rgba(248,184,74,.15); color: #f8b84a; }
    .ctrl-btn:disabled { opacity: .35; cursor: default; }

    #zoom-label {
        color: #9aa4b2; font-size: 12px; min-width: 42px; text-align: center;
    }

    /* ── canvas area ── */
    #canvas-area {
        flex: 1; overflow-y: auto; overflow-x: hidden;
        background: #111827;
        display: flex; flex-direction: column; align-items: center;
        padding: 20px 10px; gap: 12px;
        scrollbar-width: thin;
        scrollbar-color: rgba(248,184,74,.3) transparent;
    }
    #canvas-area::-webkit-scrollbar { width: 6px; }
    #canvas-area::-webkit-scrollbar-thumb {
        background: rgba(248,184,74,.3); border-radius: 10px;
    }

    .page-wrap {
        position: relative;
        box-shadow: 0 4px 30px rgba(0,0,0,.6);
        border-radius: 4px; overflow: hidden;
        display: inline-block;
    }
    .page-canvas { display: block; }

    /* watermark overlay per page */
    .wm-layer {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        pointer-events: none; z-index: 2;
    }
    .wm-layer svg { width: 100%; height: 100%; }

    /* ── status bar ── */
    #status-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 6px 20px;
        background: #020b1c;
        border-top: 1px solid rgba(255,255,255,.05);
        color: #9aa4b2; font-size: 11px; flex-shrink: 0;
        flex-wrap: wrap; gap: 6px;
    }
    #status-bar .lock-badge {
        display: flex; align-items: center; gap: 5px;
        color: #5cb85c; font-weight: 600;
    }
    #status-bar .token-timer { color: #f8b84a; font-weight: 600; }

    /* ── warning overlay ── */
    #warn-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,.97);
        align-items: center; justify-content: center;
        flex-direction: column; gap: 16px;
        color: #fff; text-align: center;
    }
    #warn-overlay.show { display: flex; }
    #warn-overlay i { font-size: 48px; color: #d9534f; }
    #warn-overlay h3 { font-size: 20px; margin: 0; }
    #warn-overlay p { color: #9aa4b2; font-size: 13px; max-width: 340px; }

    /* ── page thumb nav ── */
    #page-nav {
        display: flex; align-items: center; gap: 8px;
    }
    #page-input {
        width: 46px; text-align: center;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.1);
        color: #eaeaea; border-radius: 6px;
        padding: 4px 6px; font-size: 13px;
    }
    #page-input:focus { outline: none; border-color: #f8b84a; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div id="secure-overlay">
    <div class="spinner"></div>
    <p>Initialising secure viewer…</p>
</div>


<div id="warn-overlay">
    <i class="bx bx-shield-x"></i>
    <h3>Security Alert</h3>
    <p id="warn-msg">Suspicious activity detected. The viewer has been paused.</p>
    <button class="ctrl-btn" onclick="closeWarn()">Resume Reading</button>
</div>

<div id="viewer-shell">

    
    <div id="top-bar">
        <div class="title">
            <i class="bx bx-file-pdf" style="color:#d9534f;"></i>
            <?php echo e($pdf->title); ?>

        </div>

        <div id="page-nav">
            <button class="ctrl-btn" id="btn-prev" onclick="goPage(currentPage-1)">&#8249;</button>
            <input id="page-input" type="number" min="1" value="1"
                   onchange="goPage(parseInt(this.value))">
            <span class="meta">/ <span id="total-pages">—</span></span>
            <button class="ctrl-btn" id="btn-next" onclick="goPage(currentPage+1)">&#8250;</button>
        </div>

        <div class="controls">
            <button class="ctrl-btn" onclick="zoomOut()">&#8722;</button>
            <span id="zoom-label">100%</span>
            <button class="ctrl-btn" onclick="zoomIn()">&#43;</button>
            <a href="<?php echo e(route('secure-pdfs.index', request()->segment(2))); ?>"
               class="ctrl-btn" style="text-decoration:none;">&#8592; Back</a>
        </div>
    </div>

    
    <div id="canvas-area"></div>

    
    <div id="status-bar">
        <div class="lock-badge">
            <i class="bx bx-lock-alt"></i>
            Protected &bull; No download &bull; No copy &bull; Watermarked
        </div>
        <div>
            Session expires in: <span class="token-timer" id="token-countdown">30:00</span>
        </div>
        <div><?php echo e(auth()->user()->name); ?> &bull; <?php echo e(request()->ip()); ?></div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// ─────────────────────────────────────────────
// CONFIG
// ─────────────────────────────────────────────
const STREAM_URL   = <?php echo json_encode(route('secure-pdfs.stream', $pdf->slug), 512) ?>;
const REFRESH_URL  = <?php echo json_encode(route('secure-pdfs.token.refresh', $pdf->slug), 512) ?>;
const CSRF         = <?php echo json_encode(csrf_token(), 15, 512) ?>;
const USER_NAME    = <?php echo json_encode(auth()->user()->name, 15, 512) ?>;
const USER_EMAIL   = <?php echo json_encode(auth()->user()->email, 15, 512) ?>;
let   TOKEN        = <?php echo json_encode($token, 15, 512) ?>;
let   totalPages   = 0;
let   currentPage  = 1;
let   zoomLevel    = 1.2;
let   pdfDoc       = null;
let   renderTask   = null;
let   tokenExpiry  = 30 * 60; // seconds

pdfjsLib.GlobalWorkerOptions.workerSrc =
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// ─────────────────────────────────────────────
// LOAD PDF via stream
// ─────────────────────────────────────────────
async function loadPdf() {
    try {
        const url = `${STREAM_URL}?token=${TOKEN}`;
        const loadingTask = pdfjsLib.getDocument({
            url,
            withCredentials: true,
            disableAutoFetch: true,
            disableStream: false,
        });

        pdfDoc = await loadingTask.promise;
        totalPages = pdfDoc.numPages;

        document.getElementById('total-pages').textContent = totalPages;
        document.querySelector('#page-input').max = totalPages;

        // Render all pages sequentially
        const area = document.getElementById('canvas-area');
        area.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            await renderPage(i, area);
        }

        document.getElementById('secure-overlay').classList.add('hidden');
        updatePageNav();

    } catch (e) {
        console.error(e);
        document.querySelector('#secure-overlay p').textContent =
            'Failed to load. Please refresh.';
    }
}

// ─────────────────────────────────────────────
// RENDER single page with watermark
// ─────────────────────────────────────────────
async function renderPage(pageNum, container) {
    const page     = await pdfDoc.getPage(pageNum);
    const viewport = page.getViewport({ scale: zoomLevel });

    const wrap  = document.createElement('div');
    wrap.className = 'page-wrap';
    wrap.id = `page-wrap-${pageNum}`;
    wrap.style.width  = viewport.width  + 'px';
    wrap.style.height = viewport.height + 'px';

    const canvas  = document.createElement('canvas');
    canvas.className = 'page-canvas';
    canvas.width  = viewport.width;
    canvas.height = viewport.height;

    const ctx = canvas.getContext('2d');

    // Disable image smoothing — small anti-screenshot measure
    ctx.imageSmoothingEnabled = false;

    await page.render({ canvasContext: ctx, viewport }).promise;

    // Draw watermark directly on canvas (cannot be removed)
    drawCanvasWatermark(ctx, viewport.width, viewport.height);

    wrap.appendChild(canvas);

    // DOM watermark layer (secondary visual)
    const wm = document.createElement('div');
    wm.className = 'wm-layer';
    wm.innerHTML = buildSvgWatermark(viewport.width, viewport.height);
    wrap.appendChild(wm);

    container.appendChild(wrap);
}

// ─────────────────────────────────────────────
// Watermark burned into canvas pixels
// ─────────────────────────────────────────────
function drawCanvasWatermark(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.09;
    ctx.font = `bold ${Math.max(18, w * 0.035)}px Arial`;
    ctx.fillStyle = '#c0392b';

    const text  = `${USER_NAME} • ${USER_EMAIL}`;
    const angle = -Math.PI / 6;
    const step  = 220;

    ctx.translate(w / 2, h / 2);
    ctx.rotate(angle);
    ctx.translate(-w / 2, -h / 2);

    for (let y = -h; y < h * 2; y += step) {
        for (let x = -w; x < w * 2; x += step) {
            ctx.fillText(text, x, y);
        }
    }
    ctx.restore();
}

// SVG watermark (visual layer — catches screenshots visually)
function buildSvgWatermark(w, h) {
    const text = `${USER_NAME}`;
    return `
    <svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
      <defs>
        <pattern id="wm" x="0" y="0" width="260" height="130" patternUnits="userSpaceOnUse"
                 patternTransform="rotate(-25)">
          <text x="10" y="70" font-family="Arial" font-size="15" font-weight="bold"
                fill="rgba(192,57,43,0.07)">${text}</text>
        </pattern>
      </defs>
      <rect width="100%" height="100%" fill="url(#wm)"/>
    </svg>`;
}

// ─────────────────────────────────────────────
// ZOOM
// ─────────────────────────────────────────────
async function zoomIn()  { if (zoomLevel < 2.5) { zoomLevel = +(zoomLevel + 0.2).toFixed(1); await rerender(); } }
async function zoomOut() { if (zoomLevel > 0.6) { zoomLevel = +(zoomLevel - 0.2).toFixed(1); await rerender(); } }

async function rerender() {
    document.getElementById('zoom-label').textContent =
        Math.round(zoomLevel * 100) + '%';

    if (!pdfDoc) return;
    const area = document.getElementById('canvas-area');
    area.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        await renderPage(i, area);
    }
}

// ─────────────────────────────────────────────
// PAGE NAV
// ─────────────────────────────────────────────
function goPage(n) {
    if (!pdfDoc) return;
    n = Math.max(1, Math.min(totalPages, n));
    currentPage = n;
    document.getElementById('page-input').value = n;

    const wrap = document.getElementById(`page-wrap-${n}`);
    if (wrap) wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
    updatePageNav();
}

function updatePageNav() {
    document.getElementById('btn-prev').disabled = currentPage <= 1;
    document.getElementById('btn-next').disabled = currentPage >= totalPages;
}

// Track current page on scroll
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('canvas-area').addEventListener('scroll', function () {
        const wraps = document.querySelectorAll('.page-wrap');
        const areaTop = this.getBoundingClientRect().top;
        let closest = 1, minDist = Infinity;
        wraps.forEach((w, i) => {
            const dist = Math.abs(w.getBoundingClientRect().top - areaTop);
            if (dist < minDist) { minDist = dist; closest = i + 1; }
        });
        if (closest !== currentPage) {
            currentPage = closest;
            document.getElementById('page-input').value = closest;
            updatePageNav();
        }
    });
});

// ─────────────────────────────────────────────
// TOKEN REFRESH every 25 min
// ─────────────────────────────────────────────
function startTokenRefresh() {
    setInterval(async () => {
        try {
            const res = await fetch(REFRESH_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                }
            });
            const data = await res.json();
            if (data.token) {
                TOKEN = data.token;
                tokenExpiry = data.expires_in;
            }
        } catch (e) { /* silent */ }
    }, 25 * 60 * 1000);
}

// Token countdown display
function startCountdown() {
    const el = document.getElementById('token-countdown');
    setInterval(() => {
        if (tokenExpiry <= 0) return;
        tokenExpiry--;
        const m = String(Math.floor(tokenExpiry / 60)).padStart(2, '0');
        const s = String(tokenExpiry % 60).padStart(2, '0');
        el.textContent = `${m}:${s}`;
        if (tokenExpiry < 60) el.style.color = '#d9534f';
    }, 1000);
}

// ─────────────────────────────────────────────
// SECURITY — block copy / right-click
// ─────────────────────────────────────────────
document.addEventListener('contextmenu',  e => e.preventDefault());
document.addEventListener('copy',         e => e.preventDefault());
document.addEventListener('cut',          e => e.preventDefault());
document.addEventListener('selectstart',  e => e.preventDefault());
document.addEventListener('dragstart',    e => e.preventDefault());

// Block print
window.addEventListener('beforeprint', e => {
    <?php if(!$pdf->allow_print): ?>
    e.preventDefault();
    showWarn('Printing is disabled for this document.');
    <?php endif; ?>
});

// Block keyboard shortcuts
document.addEventListener('keydown', e => {
    const key = e.key.toLowerCase();

    // Print: Ctrl+P
    if ((e.ctrlKey || e.metaKey) && key === 'p') {
        <?php if(!$pdf->allow_print): ?>
        e.preventDefault();
        showWarn('Printing is disabled.');
        <?php endif; ?>
    }

    // Save: Ctrl+S
    if ((e.ctrlKey || e.metaKey) && key === 's') {
        e.preventDefault();
        showWarn('Saving is disabled for secure documents.');
    }

    // Screenshot helpers: PrtScn, F12
    if (key === 'printscreen') {
        e.preventDefault();
        // Blank clipboard
        navigator.clipboard?.writeText('').catch(() => {});
        showWarn('Screenshots are not allowed.');
    }

    if (key === 'f12') {
        e.preventDefault();
        showWarn('Developer tools are disabled.');
    }

    // Ctrl+Shift+I/J/C/U
    if ((e.ctrlKey || e.metaKey) && e.shiftKey &&
        ['i','j','c','u'].includes(key)) {
        e.preventDefault();
        showWarn('Developer tools are disabled.');
    }

    // Ctrl+U (view source)
    if ((e.ctrlKey || e.metaKey) && key === 'u') {
        e.preventDefault();
    }
});

// ─────────────────────────────────────────────
// DevTools detection
// ─────────────────────────────────────────────
(function detectDevTools() {
    const threshold = 160;
    let devOpen = false;

    setInterval(() => {
        const widthDiff  = window.outerWidth  - window.innerWidth  > threshold;
        const heightDiff = window.outerHeight - window.innerHeight > threshold;

        if ((widthDiff || heightDiff) && !devOpen) {
            devOpen = true;
            showWarn('Developer tools detected. Viewer paused for security.');
        } else if (!widthDiff && !heightDiff && devOpen) {
            devOpen = false;
            closeWarn();
        }
    }, 1000);

    // Debugger trap
    setInterval(() => {
        const start = Date.now();
        // eslint-disable-next-line no-debugger
        debugger;
        if (Date.now() - start > 100) {
            showWarn('Debugging detected. Viewer paused.');
        }
    }, 3000);
})();

// ─────────────────────────────────────────────
// Visibility change — blank when tab loses focus
// ─────────────────────────────────────────────
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Optionally blank canvases to prevent OS-level screenshot tools
        document.querySelectorAll('.page-canvas').forEach(c => {
            const ctx = c.getContext('2d');
            ctx.save();
            ctx.fillStyle = '#0a0e1a';
            ctx.fillRect(0, 0, c.width, c.height);
            ctx.restore();
        });
    } else {
        // Re-render when visible again
        rerender();
    }
});

// ─────────────────────────────────────────────
// Warn overlay helpers
// ─────────────────────────────────────────────
function showWarn(msg) {
    document.getElementById('warn-msg').textContent = msg || 'Security alert.';
    document.getElementById('warn-overlay').classList.add('show');
}
function closeWarn() {
    document.getElementById('warn-overlay').classList.remove('show');
}

// ─────────────────────────────────────────────
// BOOT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadPdf();
    startTokenRefresh();
    startCountdown();
    document.getElementById('zoom-label').textContent =
        Math.round(zoomLevel * 100) + '%';
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.dashboard.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/dashboard/secure-pdfs/viewer.blade.php ENDPATH**/ ?>