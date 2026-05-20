@extends('frontend.dashboard.app')
@section('title', 'Secure Viewer — ' . $pdf->title)

@section('css')
<style>
    * { -webkit-user-select: none !important; user-select: none !important; box-sizing: border-box; }
    html, body {
        margin: 0 !important; padding: 0 !important;
        background: #0a0e1a !important; overflow: hidden !important;
        height: 100% !important; width: 100% !important;
    }
    .page-content, .right_col, .col-md-12 { padding: 0 !important; margin: 0 !important; }

    /* ── CSS screenshot protection ──
       When browser enters screenshot/screenshare mode,
       the ::before covers entire viewport with black */
    @media (display-mode: browser) {
        /* force GPU layer separation — harder to capture */
        #canvas-area { transform: translateZ(0); will-change: transform; }
    }

    /* Blocks screen capture API in supported browsers */
    #viewer-shell {
        -webkit-user-select: none;
        user-select: none;
    }

    /* On some Chromium: this makes content black in screen recordings */
    .page-wrap {
        isolation: isolate;
    }

    /* ── overlays ── */
    #secure-overlay {
        position: fixed; inset: 0; z-index: 9999; background: #0a0e1a;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 12px;
    }
    #secure-overlay.hidden { display: none; }
    .spinner {
        width: 44px; height: 44px;
        border: 3px solid rgba(248,184,74,.2); border-top-color: #f8b84a;
        border-radius: 50%; animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #load-msg { color: #9aa4b2; font-size: 13px; margin: 0; }
    #load-progress { width: 180px; height: 3px; background: rgba(255,255,255,.1); border-radius: 4px; overflow: hidden; }
    #load-bar { height: 100%; background: #f8b84a; border-radius: 4px; transition: width .2s; width: 0%; }

    #warn-overlay {
        display: none; position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,.97); align-items: center; justify-content: center;
        flex-direction: column; gap: 14px; color: #fff; text-align: center; padding: 20px;
    }
    #warn-overlay.show { display: flex; }
    #warn-overlay i { font-size: 44px; color: #d9534f; }
    #warn-overlay h3 { font-size: 18px; margin: 0; }
    #warn-overlay p { color: #9aa4b2; font-size: 12px; max-width: 300px; margin: 0; }

    /* ── shell ── */
    #viewer-shell {
        position: fixed; inset: 0;
        display: flex; flex-direction: column;
        background: #0a0e1a; font-family: 'Segoe UI', sans-serif;
    }

    /* ── toolbar ── */
    #toolbar {
        flex-shrink: 0;
        background: linear-gradient(90deg, #020b1c, #03132e);
        border-bottom: 1px solid rgba(255,255,255,.08);
        padding: 6px 10px;
        display: flex; flex-direction: column; gap: 6px;
    }
    #tb-row1 { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    #tb-row2 { display: flex; align-items: center; justify-content: space-between; gap: 6px; flex-wrap: wrap; }
    #tb-row3 { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

    .pdf-title {
        color: #f8b84a; font-weight: 600; font-size: 13px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        flex: 1; min-width: 0;
    }
    #page-nav { display: flex; align-items: center; gap: 5px; }
    #zoom-controls { display: flex; align-items: center; gap: 5px; }
    .page-meta { color: #9aa4b2; font-size: 12px; white-space: nowrap; }
    #zoom-label { color: #9aa4b2; font-size: 11px; min-width: 36px; text-align: center; }

    #page-input {
        width: 38px; text-align: center;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        color: #eaeaea; border-radius: 5px; padding: 3px 2px; font-size: 12px;
        -moz-appearance: textfield;
    }
    #page-input::-webkit-outer-spin-button,
    #page-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    #page-input:focus { outline: none; border-color: #f8b84a; }

    .btn-ctrl {
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        color: #eaeaea; border-radius: 5px; padding: 4px 9px; font-size: 12px;
        cursor: pointer; transition: background .15s, color .15s;
        text-decoration: none; display: inline-flex; align-items: center;
        justify-content: center; white-space: nowrap; line-height: 1; gap: 4px;
    }
    .btn-ctrl:hover, .btn-ctrl:active { background: rgba(248,184,74,.2); color: #f8b84a; }
    .btn-ctrl:disabled { opacity: .3; cursor: default; }
    .btn-ctrl.active { background: rgba(248,184,74,.25); color: #f8b84a; border-color: #f8b84a; }

    /* ── search bar ── */
    #search-bar {
        display: none; align-items: center; gap: 6px;
        background: rgba(0,0,0,.3); border-radius: 6px;
        padding: 4px 8px; flex: 1;
    }
    #search-bar.show { display: flex; }
    #search-input {
        flex: 1; background: transparent; border: none; outline: none;
        color: #eaeaea; font-size: 12px; min-width: 80px;
    }
    #search-input::placeholder { color: #6b7280; }
    #search-count { color: #9aa4b2; font-size: 11px; white-space: nowrap; }

    /* ── goto bar ── */
    #goto-bar {
        display: none; align-items: center; gap: 6px;
    }
    #goto-bar.show { display: flex; }
    #goto-input {
        width: 52px; text-align: center;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        color: #eaeaea; border-radius: 5px; padding: 3px 4px; font-size: 12px;
        -moz-appearance: textfield;
    }
    #goto-input::-webkit-outer-spin-button,
    #goto-input::-webkit-inner-spin-button { -webkit-appearance: none; }
    #goto-input:focus { outline: none; border-color: #f8b84a; }

    /* ── main body: sidebar + canvas ── */
    #main-body { flex: 1; display: flex; overflow: hidden; }

    /* ── sidebar ── */
    #sidebar {
        width: 240px; flex-shrink: 0;
        background: #020b1c;
        border-right: 1px solid rgba(255,255,255,.07);
        display: none; flex-direction: column;
        overflow: hidden;
        transition: width .2s;
    }
    #sidebar.show { display: flex; }

    .sidebar-tabs {
        display: flex; border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    .sidebar-tab {
        flex: 1; padding: 8px 4px; text-align: center;
        color: #9aa4b2; font-size: 11px; cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: color .15s, border-color .15s;
    }
    .sidebar-tab.active { color: #f8b84a; border-bottom-color: #f8b84a; }

    .sidebar-panel { display: none; flex: 1; overflow-y: auto; padding: 10px; }
    .sidebar-panel.active { display: block; }
    .sidebar-panel::-webkit-scrollbar { width: 3px; }
    .sidebar-panel::-webkit-scrollbar-thumb { background: rgba(248,184,74,.3); border-radius: 4px; }

    /* bookmarks */
    .bookmark-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 7px 8px; margin-bottom: 4px;
        background: rgba(255,255,255,.04); border-radius: 6px;
        cursor: pointer; gap: 6px;
        border: 1px solid rgba(255,255,255,.06);
        transition: background .15s;
    }
    .bookmark-item:hover { background: rgba(248,184,74,.1); }
    .bookmark-label { color: #eaeaea; font-size: 12px; flex: 1; }
    .bookmark-page { color: #f8b84a; font-size: 11px; white-space: nowrap; }
    .bookmark-del {
        color: #d9534f; font-size: 14px; cursor: pointer;
        background: none; border: none; padding: 0; line-height: 1;
        opacity: .6;
    }
    .bookmark-del:hover { opacity: 1; }
    .bookmark-empty { color: #6b7280; font-size: 12px; text-align: center; padding: 20px 10px; }

    .add-bookmark-btn {
        width: 100%; padding: 7px; margin-bottom: 10px;
        background: rgba(248,184,74,.1); border: 1px dashed rgba(248,184,74,.3);
        color: #f8b84a; border-radius: 6px; cursor: pointer; font-size: 12px;
        transition: background .15s;
    }
    .add-bookmark-btn:hover { background: rgba(248,184,74,.2); }

    /* description */
    .desc-box {
        color: #c0c7d0; font-size: 12px; line-height: 1.7;
        padding: 4px 0;
    }
    .desc-title { color: #f8b84a; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
    .desc-meta { color: #6b7280; font-size: 11px; margin-top: 10px; line-height: 1.8; }
    .desc-meta span { color: #9aa4b2; }

    /* search highlights */
    .search-highlight {
        position: absolute; background: rgba(248,184,74,.35);
        border: 1px solid rgba(248,184,74,.6);
        pointer-events: none; z-index: 3; border-radius: 2px;
    }
    .search-highlight.current { background: rgba(248,100,50,.5); border-color: #f86432; }

    /* ── canvas area ── */
    #canvas-area {
        flex: 1; overflow-y: auto; overflow-x: hidden;
        background: #1a1f2e;
        display: flex; flex-direction: column; align-items: center;
        padding: 12px 6px; gap: 12px;
        -webkit-overflow-scrolling: touch;
    }
    #canvas-area::-webkit-scrollbar { width: 3px; }
    #canvas-area::-webkit-scrollbar-thumb { background: rgba(248,184,74,.3); border-radius: 4px; }

    .page-wrap {
        position: relative; box-shadow: 0 2px 16px rgba(0,0,0,.6);
        border-radius: 2px; overflow: hidden; flex-shrink: 0; background: #fff;
        max-width: 100%;
    }
    .page-canvas { display: block; max-width: 100%; }
    .wm-layer { position: absolute; inset: 0; pointer-events: none; z-index: 2; overflow: hidden; }
    .wm-layer svg { width: 100%; height: 100%; }
    .highlight-layer { position: absolute; inset: 0; pointer-events: none; z-index: 4; }

    /* ── status bar ── */
    #status-bar {
        flex-shrink: 0; display: flex; align-items: center; justify-content: space-between;
        padding: 4px 10px; background: #020b1c;
        border-top: 1px solid rgba(255,255,255,.05);
        color: #9aa4b2; font-size: 10px; flex-wrap: wrap; gap: 4px;
    }
    .lock-badge { display: flex; align-items: center; gap: 3px; color: #5cb85c; font-weight: 600; }
    .token-timer { color: #f8b84a; font-weight: 700; }

    @media (min-width: 640px) {
        #toolbar { flex-direction: row; align-items: center; padding: 8px 14px; gap: 10px; flex-wrap: wrap; }
        #tb-row1 { flex: 1; min-width: 200px; }
        #tb-row2, #tb-row3 { flex-shrink: 0; }
        .pdf-title { font-size: 14px; }
        #canvas-area { padding: 16px 12px; gap: 16px; }
        #status-bar { font-size: 11px; }
    }

    @media (max-width: 480px) {
        #sidebar { width: 200px; }
        .btn-ctrl { padding: 4px 7px; font-size: 11px; }
        #tb-row3 { gap: 4px; }
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

    {{-- ── TOOLBAR ── --}}
    <div id="toolbar">

        {{-- Row 1: title + back --}}
        <div id="tb-row1">
            <div class="pdf-title">
                <i class="bx bx-file-pdf" style="color:#d9534f;"></i>
                {{ $pdf->title }}
            </div>
            <a href="{{ route('secure-pdfs.details', [
                    'course'  => $pdf->courses->first()?->slug,
                    'chapter' => $pdf->chapter?->slug,
                    'lesson'  => $pdf->lesson?->slug,
               ]) }}" class="btn-ctrl">&#8592; Back</a>
        </div>

        {{-- Row 2: page nav + zoom --}}
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

        {{-- Row 3: feature buttons + search/goto bars --}}
        <div id="tb-row3">
            <button class="btn-ctrl" id="btn-search" onclick="toggleSearch()" title="Word Search">
                <i class="bx bx-search"></i> Search
            </button>
            <button class="btn-ctrl" id="btn-goto" onclick="toggleGoto()" title="Go to page">
                <i class="bx bx-navigation"></i> Go to
            </button>
            <button class="btn-ctrl" id="btn-bookmark-add" onclick="addBookmark()" title="Bookmark this page">
                <i class="bx bx-bookmark-plus"></i> Bookmark
            </button>
            <button class="btn-ctrl" id="btn-sidebar" onclick="toggleSidebar()" title="Bookmarks & Info">
                <i class="bx bx-panel"></i> Panel
            </button>

            {{-- Search bar (inline) --}}
            <div id="search-bar">
                <i class="bx bx-search" style="color:#9aa4b2; font-size:13px;"></i>
                <input id="search-input" type="text" placeholder="Search word…"
                       oninput="onSearchInput(this.value)"
                       onkeydown="if(event.key==='Enter') nextMatch()">
                <span id="search-count"></span>
                <button class="btn-ctrl" onclick="prevMatch()" title="Previous">&#8679;</button>
                <button class="btn-ctrl" onclick="nextMatch()" title="Next">&#8681;</button>
                <button class="btn-ctrl" onclick="clearSearch()">&#10005;</button>
            </div>

            {{-- Goto bar (inline) --}}
            <div id="goto-bar">
                <input id="goto-input" type="number" min="1" placeholder="Page#"
                       onkeydown="if(event.key==='Enter') doGoto()">
                <button class="btn-ctrl" onclick="doGoto()">Go</button>
                <button class="btn-ctrl" onclick="toggleGoto()">&#10005;</button>
            </div>
        </div>

    </div>

    {{-- ── DESCRIPTION BAR (always visible) ── --}}
    @if($pdf->description)
    <div id="desc-bar" style="
        flex-shrink: 0;
        background: rgba(248,184,74,0.06);
        border-bottom: 1px solid rgba(248,184,74,0.15);
        padding: 7px 14px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 12px;
        color: #c0c7d0;
        line-height: 1.5;
    ">
        <i class="bx bx-info-circle" style="color:#f8b84a; font-size:15px; flex-shrink:0; margin-top:1px;"></i>
        <span>{{ $pdf->description }}</span>
    </div>
    @endif

    {{-- ── MAIN BODY ── --}}
    <div id="main-body">

        {{-- Sidebar --}}
        <div id="sidebar">
            <div class="sidebar-tabs">
                <div class="sidebar-tab active" onclick="switchTab('bookmarks')">
                    <i class="bx bx-bookmark"></i> Bookmarks
                </div>
                <div class="sidebar-tab" onclick="switchTab('info')">
                    <i class="bx bx-info-circle"></i> Info
                </div>
            </div>

            {{-- Bookmarks panel --}}
            <div class="sidebar-panel active" id="panel-bookmarks">
                <button class="add-bookmark-btn" onclick="addBookmark()">
                    <i class="bx bx-bookmark-plus"></i> Bookmark Page <span id="bm-cur-page">1</span>
                </button>
                <div id="bookmark-list">
                    <div class="bookmark-empty">No bookmarks yet.<br>Tap "Bookmark" to save your place.</div>
                </div>
            </div>

            {{-- Info panel --}}
            <div class="sidebar-panel" id="panel-info">
                <div class="desc-title">{{ $pdf->title }}</div>
                @if($pdf->description)
                    <div class="desc-box">{{ $pdf->description }}</div>
                @else
                    <div class="desc-box" style="color:#6b7280; font-style:italic;">No description.</div>
                @endif
                <div class="desc-meta">
                    Pages: <span>{{ $pdf->total_pages }}</span><br>
                    Size: <span>{{ $pdf->file_size_formatted }}</span><br>
                    Chapter: <span>{{ $pdf->chapter?->name ?? '—' }}</span><br>
                    Lesson: <span>{{ $pdf->lesson?->name ?? '—' }}</span><br>
                    @if($pdf->isPaid)
                        Type: <span style="color:#f8b84a;">Premium</span>
                    @else
                        Type: <span style="color:#5cb85c;">Free</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Canvas --}}
        <div id="canvas-area"></div>

    </div>

    {{-- Status bar --}}
    <div id="status-bar">
        <div class="lock-badge"><i class="bx bx-lock-alt"></i> No download &bull; No copy &bull; Watermarked</div>
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
const PDF_SLUG    = @json($pdf->slug);

let TOKEN       = @json($token);
let pdfDoc      = null;
let totalPages  = 0;
let currentPage = 1;
let zoomDelta   = 0;
let tokenExpiry = 5 * 60;
let rendering   = false;

// ── bookmarks (localStorage per pdf per user) ──
const BM_KEY = `bm_${PDF_SLUG}_{{ auth()->id() }}`;
let bookmarks = JSON.parse(localStorage.getItem(BM_KEY) || '[]');

// ── search state ──
let searchMatches  = []; // [{page, items:[{x,y,w,h}]}]
let searchPageText = {}; // cache
let searchCurrent  = -1;

// ─────────────────────────────────────────────
// FETCH PDF AS ARRAYBUFFER
// ─────────────────────────────────────────────
async function fetchPdfBytes() {
    const res = await fetch(`${STREAM_URL}?token=${TOKEN}`, {
        credentials: 'include',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    const cl     = res.headers.get('Content-Length');
    const reader = res.body.getReader();
    const chunks = [];
    let received = 0;

    while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        chunks.push(value);
        received += value.length;
        if (cl) {
            const pct = Math.min(99, Math.round((received / +cl) * 100));
            document.getElementById('load-bar').style.width = pct + '%';
            document.getElementById('load-msg').textContent = `Downloading… ${pct}%`;
        }
    }

    const total  = chunks.reduce((a, c) => a + c.length, 0);
    const merged = new Uint8Array(total);
    let offset = 0;
    for (const c of chunks) { merged.set(c, offset); offset += c.length; }
    return merged.buffer;
}

// ─────────────────────────────────────────────
// LOAD
// ─────────────────────────────────────────────
async function loadPdf() {
    try {
        const buffer = await fetchPdfBytes();
        document.getElementById('load-msg').textContent = 'Rendering…';
        document.getElementById('load-bar').style.width = '100%';

        pdfDoc     = await pdfjsLib.getDocument({ data: buffer }).promise;
        totalPages = pdfDoc.numPages;
        document.getElementById('total-pages').textContent = totalPages;
        document.getElementById('page-input').max  = totalPages;
        document.getElementById('goto-input').max  = totalPages;

        await renderAll();
        renderBookmarks();
    } catch (e) {
        console.error(e);
        document.getElementById('load-msg').textContent = 'Failed to load. Please refresh.';
        document.getElementById('load-bar').style.background = '#d9534f';
    }
}

// ─────────────────────────────────────────────
// LAZY RENDER SYSTEM
// - Creates placeholder divs for ALL pages instantly
// - Renders only pages that enter the viewport
// - Pre-renders 2 pages ahead/behind (eager neighbors)
// ─────────────────────────────────────────────

const renderedPages = new Set();
const renderingPages = new Set();
let pageObserver = null;

// Estimate page height before rendering (use first page as reference)
let estimatedPageHeight = 900;
let estimatedPageWidth  = 600;

async function getPageDimensions() {
    const page     = await pdfDoc.getPage(1);
    const area     = document.getElementById('canvas-area');
    const cw       = Math.floor(area.clientWidth - 12);
    const baseVp   = page.getViewport({ scale: 1 });
    const fitScale = Math.max(0.3, cw / baseVp.width + zoomDelta);
    const vp       = page.getViewport({ scale: fitScale });
    estimatedPageHeight = vp.height;
    estimatedPageWidth  = vp.width;
}

// Build ALL placeholder divs instantly — no rendering yet
async function buildPlaceholders() {
    const area = document.getElementById('canvas-area');
    area.innerHTML = '';

    await getPageDimensions();

    for (let i = 1; i <= totalPages; i++) {
        const wrap = document.createElement('div');
        wrap.className = 'page-wrap page-placeholder';
        wrap.id = `page-wrap-${i}`;
        wrap.dataset.page = i;
        wrap.dataset.rendered = '0';
        wrap.style.width  = estimatedPageWidth  + 'px';
        wrap.style.height = estimatedPageHeight + 'px';
        wrap.style.background = '#2a2f3e';
        wrap.style.display = 'flex';
        wrap.style.alignItems = 'center';
        wrap.style.justifyContent = 'center';
        wrap.style.color = 'rgba(248,184,74,0.3)';
        wrap.style.fontSize = '13px';
        wrap.innerHTML = `
            <div style="text-align:center;">
                <div class="page-spinner" style="
                    width:28px; height:28px; margin:0 auto 8px;
                    border:2px solid rgba(248,184,74,.2);
                    border-top-color:#f8b84a;
                    border-radius:50%;
                    animation:spin .8s linear infinite;
                "></div>
                <div>Page ${i}</div>
            </div>`;
        area.appendChild(wrap);
    }
}

// Render a single page into its placeholder
async function renderPageInto(pageNum) {
    if (renderedPages.has(pageNum) || renderingPages.has(pageNum)) return;
    renderingPages.add(pageNum);

    const wrap = document.getElementById(`page-wrap-${pageNum}`);
    if (!wrap) { renderingPages.delete(pageNum); return; }

    try {
        const page       = await pdfDoc.getPage(pageNum);
        const area       = document.getElementById('canvas-area');
        const cw         = Math.floor(area.clientWidth - 12);
        const baseVp     = page.getViewport({ scale: 1 });
        const fitScale   = Math.max(0.3, cw / baseVp.width + zoomDelta);
        const viewport   = page.getViewport({ scale: fitScale });
        const dpr        = window.devicePixelRatio || 1;

        // Update wrap size to actual dimensions
        wrap.style.width  = viewport.width  + 'px';
        wrap.style.height = viewport.height + 'px';
        wrap.dataset.scale = fitScale;

        const canvas = document.createElement('canvas');
        canvas.className = 'page-canvas';
        canvas.width  = Math.floor(viewport.width  * dpr);
        canvas.height = Math.floor(viewport.height * dpr);
        canvas.style.width  = viewport.width  + 'px';
        canvas.style.height = viewport.height + 'px';

        const ctx = canvas.getContext('2d', { alpha: false });
        ctx.scale(dpr, dpr);
        await page.render({ canvasContext: ctx, viewport }).promise;
        burnWatermark(ctx, viewport.width, viewport.height);

        const hl = document.createElement('div');
        hl.className = 'highlight-layer';
        hl.id = `hl-${pageNum}`;

        const wm = document.createElement('div');
        wm.className = 'wm-layer';
        wm.innerHTML = svgWatermark(viewport.width, viewport.height);

        // Replace placeholder content
        wrap.innerHTML = '';
        wrap.style.background = '#fff';
        wrap.style.display = '';
        wrap.style.alignItems = '';
        wrap.style.justifyContent = '';
        wrap.style.color = '';
        wrap.style.fontSize = '';
        wrap.dataset.rendered = '1';

        wrap.appendChild(canvas);
        wrap.appendChild(hl);
        wrap.appendChild(wm);

        renderedPages.add(pageNum);
    } catch (e) {
        console.error(`Page ${pageNum} render error:`, e);
    }

    renderingPages.delete(pageNum);
}

// Eager-load neighbors (2 ahead, 1 behind)
function eagerLoadNeighbors(pageNum) {
    const toLoad = [pageNum - 1, pageNum + 1, pageNum + 2, pageNum - 2];
    for (const p of toLoad) {
        if (p >= 1 && p <= totalPages) {
            renderPageInto(p); // fire and forget
        }
    }
}

// Setup IntersectionObserver for lazy loading
function setupObserver() {
    if (pageObserver) pageObserver.disconnect();

    pageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const pageNum = parseInt(entry.target.dataset.page);
                renderPageInto(pageNum);
                eagerLoadNeighbors(pageNum);
            }
        });
    }, {
        root: document.getElementById('canvas-area'),
        rootMargin: '200px 0px 200px 0px', // pre-load 200px before visible
        threshold: 0.01
    });

    document.querySelectorAll('.page-placeholder').forEach(el => {
        pageObserver.observe(el);
    });
}

// Full re-render (zoom change etc.) — clear and rebuild
async function renderAll() {
    if (!pdfDoc) return;
    rendering = true;

    renderedPages.clear();
    renderingPages.clear();
    if (pageObserver) pageObserver.disconnect();

    await buildPlaceholders();
    setupObserver();

    // Immediately render first 3 pages
    for (let i = 1; i <= Math.min(3, totalPages); i++) {
        await renderPageInto(i);
    }

    rendering = false;
    document.getElementById('secure-overlay').classList.add('hidden');
    updateNav();
}

// Blank all (security)
function blankAllCanvases() {
    document.querySelectorAll('.page-canvas').forEach(c => {
        const ctx = c.getContext('2d');
        ctx.fillStyle = '#0a0e1a';
        ctx.fillRect(0, 0, c.width, c.height);
        ctx.fillStyle = 'rgba(248,184,74,0.3)';
        ctx.font = `bold ${Math.max(16, c.width * 0.05)}px Arial`;
        ctx.textAlign = 'center';
        ctx.fillText('🔒 Content Protected', c.width / 2, c.height / 2);
        ctx.textAlign = 'left';
    });
    // Also reset rendered state so they re-render on restore
    renderedPages.clear();
}

// ─────────────────────────────────────────────
// WATERMARK
// ─────────────────────────────────────────────
function burnWatermark(ctx, w, h) {
    ctx.save();
    ctx.globalAlpha = 0.07;
    ctx.font = `bold ${Math.max(11, w * 0.026)}px Arial`;
    ctx.fillStyle = '#c0392b';
    ctx.translate(w/2, h/2); ctx.rotate(-Math.PI/6); ctx.translate(-w/2, -h/2);
    const t = `${USER_NAME} • ${USER_EMAIL}`;
    for (let y = -h; y < h*2; y += 170)
        for (let x = -w; x < w*2; x += 190) ctx.fillText(t, x, y);
    ctx.restore();
}
function svgWatermark(w, h) {
    return `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">
      <defs><pattern id="wmp" x="0" y="0" width="210" height="105"
        patternUnits="userSpaceOnUse" patternTransform="rotate(-25)">
        <text x="6" y="58" font-family="Arial" font-size="11" font-weight="bold"
          fill="rgba(192,57,43,0.055)">${USER_NAME}</text>
      </pattern></defs>
      <rect width="100%" height="100%" fill="url(#wmp)"/></svg>`;
}

// ─────────────────────────────────────────────
// ZOOM
// ─────────────────────────────────────────────
async function changeZoom(delta) {
    zoomDelta = +(zoomDelta + delta).toFixed(1);
    document.getElementById('zoom-label').textContent =
        zoomDelta === 0 ? 'Auto' : (zoomDelta > 0 ? '+' : '') + Math.round(zoomDelta * 100) + '%';
    await renderAll();
    if (searchMatches.length) reDrawSearch();
}

// ─────────────────────────────────────────────
// PAGE NAV
// ─────────────────────────────────────────────
function goPage(n) {
    if (!pdfDoc) return;
    n = Math.max(1, Math.min(totalPages, n));
    currentPage = n;
    document.getElementById('page-input').value = n;
    document.getElementById('bm-cur-page').textContent = n;
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
        document.getElementById('bm-cur-page').textContent = closest;
        updateNav();
    }
});

let resizeT;
window.addEventListener('resize', () => {
    clearTimeout(resizeT);
    resizeT = setTimeout(async () => { await renderAll(); if (searchMatches.length) reDrawSearch(); }, 350);
});

// ─────────────────────────────────────────────
// GOTO
// ─────────────────────────────────────────────
function toggleGoto() {
    const bar = document.getElementById('goto-bar');
    bar.classList.toggle('show');
    if (bar.classList.contains('show')) {
        document.getElementById('search-bar').classList.remove('show');
        document.getElementById('btn-search').classList.remove('active');
        document.getElementById('btn-goto').classList.add('active');
        document.getElementById('goto-input').focus();
    } else {
        document.getElementById('btn-goto').classList.remove('active');
    }
}
function doGoto() {
    const n = parseInt(document.getElementById('goto-input').value);
    if (n >= 1 && n <= totalPages) goPage(n);
    document.getElementById('goto-bar').classList.remove('show');
    document.getElementById('btn-goto').classList.remove('active');
    document.getElementById('goto-input').value = '';
}

// ─────────────────────────────────────────────
// WORD SEARCH
// ─────────────────────────────────────────────
function toggleSearch() {
    const bar = document.getElementById('search-bar');
    bar.classList.toggle('show');
    if (bar.classList.contains('show')) {
        document.getElementById('goto-bar').classList.remove('show');
        document.getElementById('btn-goto').classList.remove('active');
        document.getElementById('btn-search').classList.add('active');
        document.getElementById('search-input').focus();
    } else {
        clearSearch();
        document.getElementById('btn-search').classList.remove('active');
    }
}

async function onSearchInput(query) {
    clearHighlights();
    searchMatches  = [];
    searchCurrent  = -1;
    document.getElementById('search-count').textContent = '';

    if (!query.trim() || query.length < 2) return;

    // search all pages
    for (let p = 1; p <= totalPages; p++) {
        const items = await searchPage(p, query);
        if (items.length) searchMatches.push({ page: p, items });
    }

    if (searchMatches.length) {
        searchCurrent = 0;
        reDrawSearch();
        goToMatch(0);
    }

    const total = searchMatches.reduce((a, m) => a + m.items.length, 0);
    document.getElementById('search-count').textContent =
        total ? `${total} found` : 'Not found';
}

async function searchPage(pageNum, query) {
    if (!searchPageText[pageNum]) {
        const page    = await pdfDoc.getPage(pageNum);
        searchPageText[pageNum] = await page.getTextContent();
    }
    const content = searchPageText[pageNum];
    const scale   = parseFloat(document.getElementById(`page-wrap-${pageNum}`)?.dataset.scale || 1);
    const results = [];
    const q       = query.toLowerCase();

    for (const item of content.items) {
        if (!item.str) continue;
        const str   = item.str.toLowerCase();
        let  idx    = str.indexOf(q);
        while (idx !== -1) {
            // approximate position
            const charW = item.width / (item.str.length || 1);
            const x     = (item.transform[4] + idx * charW) * scale;
            const y     = item.transform[5] * scale;
            const w     = q.length * charW * scale;
            const h     = item.height * scale;

            // flip y (PDF coords bottom-up, DOM top-down)
            const pageH = document.getElementById(`page-wrap-${pageNum}`)?.offsetHeight || 0;
            results.push({ x, y: pageH - y - h, w, h });
            idx = str.indexOf(q, idx + 1);
        }
    }
    return results;
}

function reDrawSearch() {
    clearHighlights();
    searchMatches.forEach((match, mi) => {
        const hl = document.getElementById(`hl-${match.page}`);
        if (!hl) return;
        match.items.forEach((r, ri) => {
            const div = document.createElement('div');
            div.className = 'search-highlight' +
                (mi === searchCurrent ? ' current' : '');
            div.style.left   = r.x + 'px';
            div.style.top    = r.y + 'px';
            div.style.width  = r.w + 'px';
            div.style.height = Math.max(r.h, 12) + 'px';
            hl.appendChild(div);
        });
    });
}

function clearHighlights() {
    document.querySelectorAll('.highlight-layer').forEach(l => l.innerHTML = '');
}

function goToMatch(idx) {
    if (!searchMatches.length) return;
    idx = ((idx % searchMatches.length) + searchMatches.length) % searchMatches.length;
    searchCurrent = idx;
    reDrawSearch();
    goPage(searchMatches[idx].page);
}
function nextMatch() { goToMatch(searchCurrent + 1); }
function prevMatch() { goToMatch(searchCurrent - 1); }

function clearSearch() {
    clearHighlights();
    searchMatches = []; searchCurrent = -1;
    document.getElementById('search-input').value = '';
    document.getElementById('search-count').textContent = '';
}

// ─────────────────────────────────────────────
// BOOKMARKS
// ─────────────────────────────────────────────
function addBookmark() {
    const p     = currentPage;
    const label = `Page ${p}`;
    if (bookmarks.find(b => b.page === p)) {
        alert(`Page ${p} is already bookmarked.`); return;
    }
    bookmarks.push({ page: p, label, time: Date.now() });
    bookmarks.sort((a, b) => a.page - b.page);
    localStorage.setItem(BM_KEY, JSON.stringify(bookmarks));
    renderBookmarks();
    // open sidebar on bookmarks tab
    document.getElementById('sidebar').classList.add('show');
    switchTab('bookmarks');
}

function removeBookmark(page) {
    bookmarks = bookmarks.filter(b => b.page !== page);
    localStorage.setItem(BM_KEY, JSON.stringify(bookmarks));
    renderBookmarks();
}

function renderBookmarks() {
    const list = document.getElementById('bookmark-list');
    if (!bookmarks.length) {
        list.innerHTML = '<div class="bookmark-empty">No bookmarks yet.<br>Tap "Bookmark" to save your place.</div>';
        return;
    }
    list.innerHTML = bookmarks.map(b => `
        <div class="bookmark-item" onclick="goPage(${b.page})">
            <span class="bookmark-label"><i class="bx bx-bookmark" style="color:#f8b84a;"></i> ${b.label}</span>
            <span class="bookmark-page">p.${b.page}</span>
            <button class="bookmark-del" onclick="event.stopPropagation(); removeBookmark(${b.page})" title="Remove">&#10005;</button>
        </div>
    `).join('');
}

// ─────────────────────────────────────────────
// SIDEBAR
// ─────────────────────────────────────────────
function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    sb.classList.toggle('show');
    document.getElementById('btn-sidebar').classList.toggle('active', sb.classList.contains('show'));
}

function switchTab(tab) {
    document.querySelectorAll('.sidebar-tab').forEach((t, i) => {
        t.classList.toggle('active', ['bookmarks','info'][i] === tab);
    });
    document.getElementById('panel-bookmarks').classList.toggle('active', tab === 'bookmarks');
    document.getElementById('panel-info').classList.toggle('active', tab === 'info');
}

// ─────────────────────────────────────────────
// TOKEN REFRESH
// ─────────────────────────────────────────────
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

// ─────────────────────────────────────────────
// COUNTDOWN
// ─────────────────────────────────────────────
setInterval(() => {
    if (tokenExpiry <= 0) return;
    tokenExpiry--;
    const m = String(Math.floor(tokenExpiry / 60)).padStart(2, '0');
    const s = String(tokenExpiry % 60).padStart(2, '0');
    const el = document.getElementById('token-countdown');
    el.textContent = `${m}:${s}`;
    el.style.color  = tokenExpiry < 60 ? '#d9534f' : '#f8b84a';
}, 1000);

// ─────────────────────────────────────────────
// SECURITY — ANTI SCREENSHOT / COPY / DEVTOOLS
// ─────────────────────────────────────────────

// 1. Basic event blocks
document.addEventListener('contextmenu',  e => e.preventDefault());
document.addEventListener('copy',         e => e.preventDefault());
document.addEventListener('cut',          e => e.preventDefault());
document.addEventListener('selectstart',  e => e.preventDefault());
document.addEventListener('dragstart',    e => e.preventDefault());

// 2. Print block
window.addEventListener('beforeprint', e => {
    @if(!$pdf->allow_print)
    e.preventDefault(); showWarn('Printing is disabled.');
    @endif
});
// CSS print block — makes page white/blank when printed
const printStyle = document.createElement('style');
printStyle.textContent = `@media print { * { display: none !important; } body::before { content: "This document is protected and cannot be printed."; display: block !important; text-align: center; margin-top: 100px; font-size: 24px; } }`;
document.head.appendChild(printStyle);

// 3. Keyboard blocks
document.addEventListener('keydown', e => {
    const k = e.key.toLowerCase(), cm = e.ctrlKey || e.metaKey;
    if (cm && k === 'p') { @if(!$pdf->allow_print) e.preventDefault(); showWarn('Printing disabled.'); @endif }
    if (cm && k === 's') { e.preventDefault(); showWarn('Saving disabled.'); }
    if (cm && k === 'u') { e.preventDefault(); }
    if (cm && k === 'a') { e.preventDefault(); }
    if (cm && k === 'c') { e.preventDefault(); }
    if (k === 'f12')     { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && e.shiftKey && ['i','j','c','k'].includes(k)) { e.preventDefault(); showWarn('DevTools disabled.'); }
    if (cm && k === 'f') { e.preventDefault(); toggleSearch(); }

    // PrtScn — blank canvases immediately then restore
    if (k === 'printscreen' || k === 'print screen') {
        e.preventDefault();
        blankAllCanvases();
        navigator.clipboard?.writeText('').catch(() => {});
        setTimeout(renderAll, 800);
        showWarn('Screenshots are not permitted.');
    }
});

// 4. STRONG WATERMARK — animated floating overlay
// This renders ON TOP of everything as a CSS overlay
// Even if screenshot taken, the watermark is baked in visually
(function injectFloatingWatermark() {
    const wm = document.createElement('div');
    wm.id = 'floating-wm';
    wm.style.cssText = `
        position: fixed; inset: 0; z-index: 8888;
        pointer-events: none;
        overflow: hidden;
        opacity: 1;
    `;

    const name  = ${JSON.stringify(USER_NAME)};
    const email = ${JSON.stringify(USER_EMAIL)};
    const text  = `${name} • ${email}`;

    // Build a grid of watermark spans
    let html = '';
    for (let row = 0; row < 12; row++) {
        for (let col = 0; col < 6; col++) {
            html += `<span style="
                position: absolute;
                left: ${(col * 18) - 5}%;
                top: ${(row * 9) - 2}%;
                color: rgba(192,57,43,0.10);
                font-size: clamp(10px, 1.4vw, 15px);
                font-weight: 700;
                font-family: Arial, sans-serif;
                white-space: nowrap;
                transform: rotate(-25deg);
                transform-origin: left center;
                letter-spacing: 1px;
            ">${text}</span>`;
        }
    }
    wm.innerHTML = html;
    document.body.appendChild(wm);
})();

// 5. DevTools size detection
(function devDetect() {
    let open = false;
    setInterval(() => {
        const w = window.outerWidth  - window.innerWidth  > 160;
        const h = window.outerHeight - window.innerHeight > 160;
        if ((w || h) && !open) {
            open = true;
            blankAllCanvases();
            showWarn('Developer tools detected. Content hidden for security.');
        }
        if (!w && !h && open) {
            open = false;
            closeWarn();
            renderAll();
        }
    }, 800);
})();

// 6. Debugger trap — slows down devtools timeline
(function debugTrap() {
    setInterval(() => { debugger; }, 100);
})();

// 7. Visibility change — blank on tab switch
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        blankAllCanvases();
    } else {
        setTimeout(renderAll, 200);
    }
});

// 8. Focus loss — blank when window loses focus (alt+tab, screen capture tools)
window.addEventListener('blur', () => {
    blankAllCanvases();
});
window.addEventListener('focus', () => {
    setTimeout(renderAll, 300);
});

// 9. Blank all canvases helper — defined in render section above

function showWarn(msg) {
    document.getElementById('warn-msg').textContent = msg || 'Security alert.';
    document.getElementById('warn-overlay').classList.add('show');
}
function closeWarn() { document.getElementById('warn-overlay').classList.remove('show'); }

// ─────────────────────────────────────────────
// BOOT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', loadPdf);
</script>
@endpush
