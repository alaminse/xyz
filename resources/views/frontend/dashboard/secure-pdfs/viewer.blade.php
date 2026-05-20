@extends('frontend.dashboard.app')
@section('title', 'Secure Viewer')

@section('css')
<style>
* { -webkit-user-select:none!important; user-select:none!important; box-sizing:border-box; }
html,body { margin:0!important; padding:0!important; background:#0a0e1a!important; overflow:hidden!important; height:100%!important; width:100%!important; }
.page-content,.right_col,.col-md-12 { padding:0!important; margin:0!important; }

/* loading */
#ov-load {
    position:fixed; inset:0; z-index:9999; background:#0a0e1a;
    display:flex; align-items:center; justify-content:center; flex-direction:column; gap:12px;
}
#ov-load.gone { display:none; }
.spin { width:42px; height:42px; border:3px solid rgba(248,184,74,.2); border-top-color:#f8b84a; border-radius:50%; animation:spin .8s linear infinite; }
@keyframes spin { to{transform:rotate(360deg);} }
#ov-load p { color:#9aa4b2; font-size:13px; margin:0; }
#prog-wrap { width:180px; height:3px; background:rgba(255,255,255,.1); border-radius:4px; overflow:hidden; }
#prog-bar  { height:100%; background:#f8b84a; border-radius:4px; transition:width .2s; width:0; }

/* warn */
#ov-warn {
    display:none; position:fixed; inset:0; z-index:99999; background:rgba(0,0,0,.97);
    align-items:center; justify-content:center; flex-direction:column; gap:14px;
    color:#fff; text-align:center; padding:20px;
}
#ov-warn.on { display:flex; }
#ov-warn i  { font-size:44px; color:#d9534f; }
#ov-warn h3 { font-size:18px; margin:0; }
#ov-warn p  { color:#9aa4b2; font-size:12px; max-width:300px; margin:0; }

/* shell */
#shell {
    position:fixed; inset:0; display:flex; flex-direction:column;
    background:#0a0e1a; font-family:'Segoe UI',sans-serif;
}

/* toolbar */
#toolbar {
    flex-shrink:0; background:linear-gradient(90deg,#020b1c,#03132e);
    border-bottom:1px solid rgba(255,255,255,.08);
    padding:6px 10px; display:flex; flex-direction:column; gap:5px;
}
#tb1 { display:flex; align-items:center; justify-content:space-between; gap:8px; }
#tb2 { display:flex; align-items:center; justify-content:space-between; gap:6px; flex-wrap:wrap; }
#tb3 { display:flex; align-items:center; gap:5px; flex-wrap:wrap; }

.pdf-ttl {
    color:#f8b84a; font-weight:600; font-size:13px;
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1; min-width:0;
}
#page-nav   { display:flex; align-items:center; gap:5px; }
#zoom-ctrl  { display:flex; align-items:center; gap:5px; }
.pmeta      { color:#9aa4b2; font-size:12px; white-space:nowrap; }
#zlabel     { color:#9aa4b2; font-size:11px; min-width:36px; text-align:center; }

#pg-in,#gt-in {
    width:38px; text-align:center;
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);
    color:#eaeaea; border-radius:5px; padding:3px 2px; font-size:12px;
    -moz-appearance:textfield;
}
#pg-in::-webkit-inner-spin-button,#pg-in::-webkit-outer-spin-button,
#gt-in::-webkit-inner-spin-button,#gt-in::-webkit-outer-spin-button { -webkit-appearance:none; }
#pg-in:focus,#gt-in:focus { outline:none; border-color:#f8b84a; }

.btn {
    background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);
    color:#eaeaea; border-radius:5px; padding:4px 9px; font-size:12px;
    cursor:pointer; transition:background .15s,color .15s;
    text-decoration:none; display:inline-flex; align-items:center;
    justify-content:center; white-space:nowrap; line-height:1; gap:4px;
}
.btn:hover,.btn:active { background:rgba(248,184,74,.2); color:#f8b84a; }
.btn:disabled { opacity:.3; cursor:default; }
.btn.on { background:rgba(248,184,74,.2); color:#f8b84a; border-color:#f8b84a; }

/* search bar */
#srch-bar {
    display:none; align-items:center; gap:5px;
    background:rgba(0,0,0,.3); border-radius:6px; padding:3px 7px; flex:1; max-width:340px;
}
#srch-bar.on { display:flex; }
#srch-in {
    flex:1; background:transparent; border:none; outline:none;
    color:#eaeaea; font-size:12px; min-width:60px;
}
#srch-in::placeholder { color:#6b7280; }
#srch-cnt { color:#9aa4b2; font-size:11px; white-space:nowrap; }

/* goto bar */
#gt-bar { display:none; align-items:center; gap:5px; }
#gt-bar.on { display:flex; }

/* description bar */
#desc-bar {
    flex-shrink:0; background:rgba(248,184,74,.06);
    border-bottom:1px solid rgba(248,184,74,.14);
    padding:6px 14px; display:flex; align-items:flex-start;
    gap:7px; font-size:12px; color:#c0c7d0; line-height:1.5;
}

/* main body */
#body { flex:1; display:flex; overflow:hidden; }

/* sidebar */
#sidebar {
    width:230px; flex-shrink:0; background:#020b1c;
    border-right:1px solid rgba(255,255,255,.07);
    display:none; flex-direction:column; overflow:hidden;
}
#sidebar.on { display:flex; }
.sb-tabs { display:flex; border-bottom:1px solid rgba(255,255,255,.07); flex-shrink:0; }
.sb-tab {
    flex:1; padding:8px 4px; text-align:center; color:#9aa4b2;
    font-size:11px; cursor:pointer; border-bottom:2px solid transparent;
    transition:color .15s,border-color .15s;
}
.sb-tab.on { color:#f8b84a; border-bottom-color:#f8b84a; }
.sb-panel { display:none; flex:1; overflow-y:auto; padding:10px; }
.sb-panel.on { display:block; }
.sb-panel::-webkit-scrollbar { width:3px; }
.sb-panel::-webkit-scrollbar-thumb { background:rgba(248,184,74,.3); border-radius:4px; }

.bm-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:7px 8px; margin-bottom:4px;
    background:rgba(255,255,255,.04); border-radius:6px;
    cursor:pointer; gap:6px; border:1px solid rgba(255,255,255,.06);
    transition:background .15s;
}
.bm-item:hover { background:rgba(248,184,74,.1); }
.bm-lbl { color:#eaeaea; font-size:12px; flex:1; }
.bm-pg  { color:#f8b84a; font-size:11px; white-space:nowrap; }
.bm-del { color:#d9534f; font-size:14px; cursor:pointer; background:none; border:none; padding:0; line-height:1; opacity:.6; }
.bm-del:hover { opacity:1; }
.bm-empty { color:#6b7280; font-size:12px; text-align:center; padding:20px 10px; }
.bm-add {
    width:100%; padding:7px; margin-bottom:10px;
    background:rgba(248,184,74,.08); border:1px dashed rgba(248,184,74,.3);
    color:#f8b84a; border-radius:6px; cursor:pointer; font-size:12px;
    transition:background .15s;
}
.bm-add:hover { background:rgba(248,184,74,.18); }

.info-ttl  { color:#f8b84a; font-size:13px; font-weight:600; margin-bottom:8px; }
.info-desc { color:#c0c7d0; font-size:12px; line-height:1.7; }
.info-meta { color:#6b7280; font-size:11px; margin-top:10px; line-height:1.9; }
.info-meta span { color:#9aa4b2; }

/* canvas area */
#cv-area {
    flex:1; overflow-y:auto; overflow-x:hidden;
    background:#1a1f2e; display:flex; flex-direction:column;
    align-items:center; padding:12px 6px; gap:12px;
    -webkit-overflow-scrolling:touch;
}
#cv-area::-webkit-scrollbar { width:3px; }
#cv-area::-webkit-scrollbar-thumb { background:rgba(248,184,74,.3); border-radius:4px; }

.pg-wrap {
    position:relative; box-shadow:0 2px 16px rgba(0,0,0,.6);
    border-radius:2px; overflow:hidden; flex-shrink:0;
    background:#2a2f3e; max-width:100%;
}
.pg-canvas { display:block; max-width:100%; }
.wm-layer  { position:absolute; inset:0; pointer-events:none; z-index:2; overflow:hidden; }
.wm-layer svg { width:100%; height:100%; }
.hl-layer  { position:absolute; inset:0; pointer-events:none; z-index:4; }
.srch-hl   { position:absolute; background:rgba(248,184,74,.35); border:1px solid rgba(248,184,74,.6); pointer-events:none; z-index:3; border-radius:2px; }
.srch-hl.cur { background:rgba(248,100,50,.5); border-color:#f86432; }

.pg-loader {
    display:flex; flex-direction:column; align-items:center;
    justify-content:center; color:rgba(248,184,74,.3); font-size:12px; gap:8px;
    width:100%; height:100%;
}

/* status bar */
#status-bar {
    flex-shrink:0; display:flex; align-items:center; justify-content:space-between;
    padding:4px 10px; background:#020b1c;
    border-top:1px solid rgba(255,255,255,.05);
    color:#9aa4b2; font-size:10px; flex-wrap:wrap; gap:4px;
}
.lk-badge { display:flex; align-items:center; gap:3px; color:#5cb85c; font-weight:600; }
.timer    { color:#f8b84a; font-weight:700; }

/* floating watermark overlay — always on top */
#float-wm {
    position:fixed; inset:0; z-index:8888;
    pointer-events:none; overflow:hidden;
}
#float-wm span {
    position:absolute; font-family:Arial,sans-serif;
    font-weight:700; white-space:nowrap;
    color:rgba(180,40,40,0.09);
    font-size:clamp(10px,1.3vw,14px);
    transform:rotate(-25deg); transform-origin:left center;
    letter-spacing:1px;
}

@media (min-width:640px) {
    #toolbar { flex-direction:row; align-items:center; padding:8px 14px; gap:10px; flex-wrap:wrap; }
    #tb1 { flex:1; min-width:180px; }
    #tb2,#tb3 { flex-shrink:0; }
    .pdf-ttl { font-size:14px; }
    #cv-area { padding:16px 12px; gap:16px; }
    #status-bar { font-size:11px; }
}
@media (max-width:480px) {
    #sidebar { width:200px; }
    .btn { padding:4px 7px; font-size:11px; }
}
</style>
@endsection

@section('content')

{{-- Loading --}}
<div id="ov-load">
    <div class="spin"></div>
    <p id="ov-msg">Downloading secure PDF…</p>
    <div id="prog-wrap"><div id="prog-bar"></div></div>
</div>

{{-- Warn --}}
<div id="ov-warn">
    <i class="bx bx-shield-x"></i>
    <h3>Security Alert</h3>
    <p id="warn-msg">Suspicious activity detected.</p>
    <button class="btn" onclick="closeWarn()">Resume Reading</button>
</div>

{{-- Floating watermark --}}
<div id="float-wm"></div>

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
            <button class="btn" id="btn-gt"   onclick="toggleGoto()"><i class="bx bx-navigation"></i> Go to</button>
            <button class="btn" id="btn-bm"   onclick="addBookmark()"><i class="bx bx-bookmark-plus"></i> Bookmark</button>
            <button class="btn" id="btn-sb"   onclick="toggleSidebar()"><i class="bx bx-panel"></i> Panel</button>

            <div id="srch-bar">
                <i class="bx bx-search" style="color:#9aa4b2;font-size:13px"></i>
                <input id="srch-in" type="text" placeholder="Search word…"
                       oninput="doSearch(this.value)"
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

    @if($pdf->description)
    <div id="desc-bar">
        <i class="bx bx-info-circle" style="color:#f8b84a;font-size:15px;flex-shrink:0;margin-top:1px"></i>
        <span>{{ $pdf->description }}</span>
    </div>
    @endif

    <div id="body">

        {{-- Sidebar --}}
        <div id="sidebar">
            <div class="sb-tabs">
                <div class="sb-tab on"  onclick="sbTab('bm')"><i class="bx bx-bookmark"></i> Bookmarks</div>
                <div class="sb-tab"     onclick="sbTab('info')"><i class="bx bx-info-circle"></i> Info</div>
            </div>
            <div class="sb-panel on" id="panel-bm">
                <button class="bm-add" onclick="addBookmark()">
                    <i class="bx bx-bookmark-plus"></i> Bookmark Page <span id="bm-cur">1</span>
                </button>
                <div id="bm-list"><div class="bm-empty">No bookmarks yet.</div></div>
            </div>
            <div class="sb-panel" id="panel-info">
                <div class="info-ttl">{{ $pdf->title }}</div>
                <div class="info-desc">{{ $pdf->description ?? 'No description.' }}</div>
                <div class="info-meta">
                    Pages: <span>{{ $pdf->total_pages }}</span><br>
                    Size: <span>{{ $pdf->file_size_formatted }}</span><br>
                    Chapter: <span>{{ $pdf->chapter?->name ?? '—' }}</span><br>
                    Lesson: <span>{{ $pdf->lesson?->name ?? '—' }}</span><br>
                    Type: <span style="color:{{ $pdf->isPaid ? '#f8b84a' : '#5cb85c' }}">{{ $pdf->isPaid ? 'Premium' : 'Free' }}</span>
                </div>
            </div>
        </div>

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

const STREAM_URL  = @json(route('secure-pdfs.stream',       $pdf->slug));
const REFRESH_URL = @json(route('secure-pdfs.token.refresh', $pdf->slug));
const CSRF        = @json(csrf_token());
const U_NAME      = @json(auth()->user()->name);
const U_EMAIL     = @json(auth()->user()->email);
const PDF_SLUG    = @json($pdf->slug);
const UID         = @json(auth()->id());

let TOKEN      = @json($token);
let pdfDoc     = null;
let totPages   = 0;
let curPage    = 1;
let zDelta     = 0;
let expiry     = 5 * 60;
let rendering  = false;

// ── FLOATING WATERMARK ─────────────────────────────────────────────────
(function buildFloatWm() {
    var wm   = document.getElementById('float-wm');
    var text = U_NAME + ' \u2022 ' + U_EMAIL;
    var html = '';
    for (var r = 0; r < 14; r++) {
        for (var c = 0; c < 7; c++) {
            html += '<span style="left:' + ((c * 16) - 4) + '%;top:' + ((r * 8) - 2) + '%">' + text + '</span>';
        }
    }
    wm.innerHTML = html;
})();

// ── FETCH PDF AS ARRAYBUFFER ────────────────────────────────────────────
async function fetchBytes() {
    var res = await fetch(STREAM_URL + '?token=' + TOKEN, {
        credentials: 'include',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);

    var cl     = res.headers.get('Content-Length');
    var reader = res.body.getReader();
    var chunks = [], got = 0;

    while (true) {
        var rd = await reader.read();
        if (rd.done) break;
        chunks.push(rd.value);
        got += rd.value.length;
        if (cl) {
            var pct = Math.min(99, Math.round(got / +cl * 100));
            document.getElementById('prog-bar').style.width = pct + '%';
            document.getElementById('ov-msg').textContent   = 'Downloading\u2026 ' + pct + '%';
        }
    }

    var total = chunks.reduce(function(a,c){ return a + c.length; }, 0);
    var buf   = new Uint8Array(total);
    var off   = 0;
    for (var i = 0; i < chunks.length; i++) { buf.set(chunks[i], off); off += chunks[i].length; }
    return buf.buffer;
}

// ── LOAD PDF ────────────────────────────────────────────────────────────
async function loadPdf() {
    try {
        var buffer = await fetchBytes();
        document.getElementById('ov-msg').textContent = 'Rendering\u2026';
        document.getElementById('prog-bar').style.width = '100%';

        pdfDoc   = await pdfjsLib.getDocument({ data: buffer }).promise;
        totPages = pdfDoc.numPages;
        document.getElementById('tot-pages').textContent = totPages;
        document.getElementById('pg-in').max = totPages;
        document.getElementById('gt-in').max = totPages;

        await initPages();
        renderBookmarks();
    } catch (e) {
        console.error(e);
        document.getElementById('ov-msg').textContent = 'Failed to load. Please refresh.';
        document.getElementById('prog-bar').style.background = '#d9534f';
    }
}

// ── LAZY RENDER SYSTEM ──────────────────────────────────────────────────
var rendered  = {};
var inRender  = {};
var pgObserver = null;
var estH = 900, estW = 600;

async function initPages() {
    // Get first page size for placeholders
    var p1  = await pdfDoc.getPage(1);
    var cw  = Math.floor(document.getElementById('cv-area').clientWidth - 12);
    var bvp = p1.getViewport({ scale: 1 });
    var fs  = Math.max(0.3, cw / bvp.width + zDelta);
    var vp  = p1.getViewport({ scale: fs });
    estH = vp.height; estW = vp.width;

    var area = document.getElementById('cv-area');
    area.innerHTML = '';
    rendered = {}; inRender = {};

    for (var i = 1; i <= totPages; i++) {
        var wrap = document.createElement('div');
        wrap.className = 'pg-wrap';
        wrap.id = 'pw-' + i;
        wrap.dataset.page = i;
        wrap.style.width  = estW + 'px';
        wrap.style.height = estH + 'px';
        wrap.innerHTML = '<div class="pg-loader">'
            + '<div class="spin"></div>'
            + '<div>Page ' + i + '</div>'
            + '</div>';
        area.appendChild(wrap);
    }

    setupObserver();

    // Render first 3 immediately
    for (var j = 1; j <= Math.min(3, totPages); j++) {
        await renderPage(j);
    }

    document.getElementById('ov-load').classList.add('gone');
    updateNav();
}

async function renderPage(n) {
    if (rendered[n] || inRender[n]) return;
    inRender[n] = true;

    var wrap = document.getElementById('pw-' + n);
    if (!wrap) { inRender[n] = false; return; }

    try {
        var page = await pdfDoc.getPage(n);
        var cw   = Math.floor(document.getElementById('cv-area').clientWidth - 12);
        var bvp  = page.getViewport({ scale: 1 });
        var fs   = Math.max(0.3, cw / bvp.width + zDelta);
        var vp   = page.getViewport({ scale: fs });
        var dpr  = window.devicePixelRatio || 1;

        wrap.style.width  = vp.width  + 'px';
        wrap.style.height = vp.height + 'px';
        wrap.dataset.scale = fs;

        var canvas       = document.createElement('canvas');
        canvas.className = 'pg-canvas';
        canvas.width     = Math.floor(vp.width  * dpr);
        canvas.height    = Math.floor(vp.height * dpr);
        canvas.style.width  = vp.width  + 'px';
        canvas.style.height = vp.height + 'px';

        var ctx = canvas.getContext('2d', { alpha: false });
        ctx.scale(dpr, dpr);
        await page.render({ canvasContext: ctx, viewport: vp }).promise;
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
    } catch(e) {
        console.error('Page ' + n + ' error:', e);
    }
    inRender[n] = false;
}

function eagerLoad(n) {
    [n-1, n+1, n+2, n-2].forEach(function(p) {
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

// ── WATERMARK ────────────────────────────────────────────────────────────
function burnWm(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.07;
    ctx.font = 'bold ' + Math.max(11, Math.floor(w * 0.026)) + 'px Arial';
    ctx.fillStyle = '#c0392b';
    ctx.translate(w/2, h/2); ctx.rotate(-Math.PI/6); ctx.translate(-w/2, -h/2);
    var t = U_NAME + ' \u2022 ' + U_EMAIL;
    for (var y = -h; y < h*2; y += 170)
        for (var x = -w; x < w*2; x += 190) ctx.fillText(t, x, y);
    ctx.restore();
}
function svgWm(w, h) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' + w + '" height="' + h + '">'
        + '<defs><pattern id="wmp" x="0" y="0" width="210" height="105"'
        + ' patternUnits="userSpaceOnUse" patternTransform="rotate(-25)">'
        + '<text x="6" y="58" font-family="Arial" font-size="11" font-weight="bold"'
        + ' fill="rgba(192,57,43,0.055)">' + U_NAME + '</text>'
        + '</pattern></defs>'
        + '<rect width="100%" height="100%" fill="url(#wmp)"/></svg>';
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
        ctx.fillText('\uD83D\uDD12 Content Protected', c.width/2, c.height/2);
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
    document.getElementById('bm-cur').textContent = n;
    var el = document.getElementById('pw-' + n);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    updateNav();
}
function updateNav() {
    document.getElementById('btn-prev').disabled = curPage <= 1;
    document.getElementById('btn-next').disabled = curPage >= totPages;
}

document.getElementById('cv-area').addEventListener('scroll', function() {
    var top = this.getBoundingClientRect().top;
    var closest = 1, minD = Infinity;
    document.querySelectorAll('.pg-wrap').forEach(function(w, i) {
        var d = Math.abs(w.getBoundingClientRect().top - top);
        if (d < minD) { minD = d; closest = i + 1; }
    });
    if (closest !== curPage) {
        curPage = closest;
        document.getElementById('pg-in').value = closest;
        document.getElementById('bm-cur').textContent = closest;
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
var srchMatches = [], srchCurrent = -1, srchCache = {};

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
    srchMatches = []; srchCurrent = -1;
    document.getElementById('srch-cnt').textContent = '';
    if (!q || q.length < 2) return;

    for (var p = 1; p <= totPages; p++) {
        var items = await searchPage(p, q);
        if (items.length) srchMatches.push({ page: p, items: items });
    }

    if (srchMatches.length) { srchCurrent = 0; reDrawSearch(); goToMatch(0); }
    var tot = srchMatches.reduce(function(a,m){ return a + m.items.length; }, 0);
    document.getElementById('srch-cnt').textContent = tot ? tot + ' found' : 'Not found';
}

async function searchPage(n, q) {
    if (!srchCache[n]) {
        var pg = await pdfDoc.getPage(n);
        srchCache[n] = await pg.getTextContent();
    }
    var content = srchCache[n];
    var scale   = parseFloat(document.getElementById('pw-' + n) && document.getElementById('pw-' + n).dataset.scale || 1);
    var results = [], ql = q.toLowerCase();

    content.items.forEach(function(item) {
        if (!item.str) return;
        var sl = item.str.toLowerCase(), idx = sl.indexOf(ql);
        while (idx !== -1) {
            var cw = item.width / (item.str.length || 1);
            var x  = (item.transform[4] + idx * cw) * scale;
            var y  = item.transform[5] * scale;
            var iw = ql.length * cw * scale;
            var ih = item.height * scale;
            var pH = document.getElementById('pw-' + n) ? document.getElementById('pw-' + n).offsetHeight : 0;
            results.push({ x: x, y: pH - y - ih, w: iw, h: Math.max(ih, 12) });
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
            d.style.cssText = 'left:' + r.x + 'px;top:' + r.y + 'px;width:' + r.w + 'px;height:' + r.h + 'px';
            hl.appendChild(d);
        });
    });
}

function clearHighlights() {
    document.querySelectorAll('.hl-layer').forEach(function(l){ l.innerHTML=''; });
}

function goToMatch(idx) {
    if (!srchMatches.length) return;
    idx = ((idx % srchMatches.length) + srchMatches.length) % srchMatches.length;
    srchCurrent = idx;
    reDrawSearch();
    goPage(srchMatches[idx].page);
}
function nextMatch() { goToMatch(srchCurrent + 1); }
function prevMatch() { goToMatch(srchCurrent - 1); }
function clearSearch() {
    clearHighlights();
    srchMatches = []; srchCurrent = -1;
    document.getElementById('srch-in').value = '';
    document.getElementById('srch-cnt').textContent = '';
}

// ── BOOKMARKS ─────────────────────────────────────────────────────────────
var BM_KEY    = 'bm_' + PDF_SLUG + '_' + UID;
var bookmarks = [];
try { bookmarks = JSON.parse(localStorage.getItem(BM_KEY) || '[]'); } catch(e) { bookmarks = []; }

function addBookmark() {
    var p = curPage;
    if (bookmarks.find(function(b){ return b.page === p; })) {
        alert('Page ' + p + ' is already bookmarked.'); return;
    }
    bookmarks.push({ page: p, label: 'Page ' + p });
    bookmarks.sort(function(a,b){ return a.page - b.page; });
    try { localStorage.setItem(BM_KEY, JSON.stringify(bookmarks)); } catch(e){}
    renderBookmarks();
    document.getElementById('sidebar').classList.add('on');
    sbTab('bm');
}

function removeBookmark(p) {
    bookmarks = bookmarks.filter(function(b){ return b.page !== p; });
    try { localStorage.setItem(BM_KEY, JSON.stringify(bookmarks)); } catch(e){}
    renderBookmarks();
}

function renderBookmarks() {
    var list = document.getElementById('bm-list');
    if (!bookmarks.length) {
        list.innerHTML = '<div class="bm-empty">No bookmarks yet.</div>'; return;
    }
    list.innerHTML = bookmarks.map(function(b) {
        return '<div class="bm-item" onclick="goPage(' + b.page + ')">'
            + '<span class="bm-lbl"><i class="bx bx-bookmark" style="color:#f8b84a"></i> ' + b.label + '</span>'
            + '<span class="bm-pg">p.' + b.page + '</span>'
            + '<button class="bm-del" onclick="event.stopPropagation();removeBookmark(' + b.page + ')">&#10005;</button>'
            + '</div>';
    }).join('');
}

// ── SIDEBAR ───────────────────────────────────────────────────────────────
function toggleSidebar() {
    var sb = document.getElementById('sidebar');
    sb.classList.toggle('on');
    document.getElementById('btn-sb').classList.toggle('on', sb.classList.contains('on'));
}
function sbTab(tab) {
    document.querySelectorAll('.sb-tab').forEach(function(t, i) {
        t.classList.toggle('on', ['bm','info'][i] === tab);
    });
    document.getElementById('panel-bm').classList.toggle('on',   tab === 'bm');
    document.getElementById('panel-info').classList.toggle('on', tab === 'info');
}

// ── TOKEN REFRESH ─────────────────────────────────────────────────────────
setInterval(function() {
    fetch(REFRESH_URL, {
        method: 'POST', credentials: 'include',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' }
    }).then(function(r){ return r.json(); })
      .then(function(d){ if (d.token) { TOKEN = d.token; expiry = d.expires_in; } })
      .catch(function(){});
}, 4 * 60 * 1000);

// ── COUNTDOWN ─────────────────────────────────────────────────────────────
setInterval(function() {
    if (expiry <= 0) return;
    expiry--;
    var m = String(Math.floor(expiry / 60)).padStart(2,'0');
    var s = String(expiry % 60).padStart(2,'0');
    var el = document.getElementById('countdown');
    el.textContent = m + ':' + s;
    el.style.color  = expiry < 60 ? '#d9534f' : '#f8b84a';
}, 1000);

// ── SECURITY ──────────────────────────────────────────────────────────────
document.addEventListener('contextmenu',  function(e){ e.preventDefault(); });
document.addEventListener('copy',         function(e){ e.preventDefault(); });
document.addEventListener('cut',          function(e){ e.preventDefault(); });
document.addEventListener('selectstart',  function(e){ e.preventDefault(); });
document.addEventListener('dragstart',    function(e){ e.preventDefault(); });

// print block via CSS
var ps = document.createElement('style');
ps.textContent = '@media print { * { display:none!important; } body::before { content:"This document is protected."; display:block!important; text-align:center; margin-top:100px; font-size:20px; } }';
document.head.appendChild(ps);

window.addEventListener('beforeprint', function(e) {
    @if(!$pdf->allow_print)
    e.preventDefault(); showWarn('Printing is disabled.');
    @endif
});

document.addEventListener('keydown', function(e) {
    var k = e.key ? e.key.toLowerCase() : '';
    var cm = e.ctrlKey || e.metaKey;
    if (cm && k === 'p') { @if(!$pdf->allow_print) e.preventDefault(); showWarn('Printing disabled.'); @endif }
    if (cm && k === 's') { e.preventDefault(); showWarn('Saving disabled.'); }
    if (cm && k === 'u') { e.preventDefault(); }
    if (cm && k === 'a') { e.preventDefault(); }
    if (cm && k === 'c') { e.preventDefault(); }
    if (k === 'f12') { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && e.shiftKey && (k==='i'||k==='j'||k==='c'||k==='k')) { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && k === 'f') { e.preventDefault(); toggleSearch(); }
    if (k === 'printscreen' || k === 'print screen') {
        e.preventDefault();
        blankAll();
        if (navigator.clipboard) navigator.clipboard.writeText('').catch(function(){});
        setTimeout(reRenderAll, 800);
    }
});

// DevTools size detection
(function() {
    var open = false;
    setInterval(function() {
        var w = window.outerWidth  - window.innerWidth  > 160;
        var h = window.outerHeight - window.innerHeight > 160;
        if ((w || h) && !open) { open = true;  blankAll(); showWarn('DevTools detected. Content hidden.'); }
        if (!w && !h  && open) { open = false; closeWarn(); reRenderAll(); }
    }, 800);
})();

// Debugger trap
(function() {
    setInterval(function() { debugger; }, 100);
})();

// Blur — blank when window loses focus (snipping tool, alt+tab)
window.addEventListener('blur', function() { blankAll(); });
window.addEventListener('focus', function() { setTimeout(reRenderAll, 300); });

// Tab visibility
document.addEventListener('visibilitychange', function() {
    if (document.hidden) { blankAll(); }
    else { setTimeout(reRenderAll, 200); }
});

function showWarn(msg) {
    document.getElementById('warn-msg').textContent = msg || 'Security alert.';
    document.getElementById('ov-warn').classList.add('on');
}
function closeWarn() { document.getElementById('ov-warn').classList.remove('on'); }

// ── BOOT ──────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', loadPdf);
</script>
@endpush
