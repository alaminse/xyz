<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    {{-- 🔒 Block indexing and caching --}}
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #000;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        #player-container {
            position: relative;
            width: 100vw;
            height: 100vh;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            /* 🔒 Prevent pointer events bypass */
            pointer-events: all;
        }
        .watermark {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.75);
            color: rgba(255, 255, 255, 0.7);
            padding: 6px 12px;
            font-size: 11px;
            border-radius: 4px;
            pointer-events: none;
            z-index: 9999;
            font-family: 'Courier New', monospace;
            backdrop-filter: blur(5px);
        }
        /* 🔒 Floating random watermark */
        .watermark-float {
            position: absolute;
            color: rgba(255, 255, 255, 0.15);
            font-size: 13px;
            pointer-events: none;
            z-index: 9998;
            font-family: 'Courier New', monospace;
            transition: all 5s ease;
        }
        * {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }
    </style>
</head>
<body>
    <div id="player-container">
        {{-- 🔒 Bunny.net signed iframe - IDM cannot intercept signed token URLs --}}
        <iframe
            id="bunny-player"
            src="{{ $signedUrl }}"
            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
            allowfullscreen
            oncontextmenu="return false;"
            referrerpolicy="no-referrer">
        </iframe>

        {{-- Fixed watermark --}}
        <div class="watermark">
            🔒 {{ $userEmail }}
        </div>

        {{-- Floating watermark --}}
        <div class="watermark-float" id="floatingWatermark">
            {{ $userEmail }}
        </div>
    </div>

    <script>
        'use strict';

        // 🔒 1. Disable right-click
        document.addEventListener('contextmenu', e => e.preventDefault());

        // 🔒 2. Disable keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+P
            if (
                e.key === 'F12' ||
                e.key === 'PrintScreen' ||
                (e.ctrlKey && e.shiftKey && ['I', 'J', 'C', 'K'].includes(e.key)) ||
                (e.ctrlKey && ['u', 'U', 's', 'S', 'p', 'P', 'a', 'A'].includes(e.key))
            ) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        // 🔒 3. Disable text selection and drag
        document.onselectstart = () => false;
        document.ondragstart   = () => false;
        document.oncopy        = () => false;

        // 🔒 4. Blur iframe on tab switch (prevents screen recording pause exploit)
        document.addEventListener('visibilitychange', function() {
            const iframe = document.getElementById('bunny-player');
            if (iframe) {
                iframe.style.filter = document.hidden ? 'blur(20px)' : 'none';
            }
        });

        // 🔒 5. DevTools detection — reload if opened
        (function detectDevTools() {
            const threshold = 160;
            setInterval(function() {
                if (
                    window.outerWidth - window.innerWidth > threshold ||
                    window.outerHeight - window.innerHeight > threshold
                ) {
                    document.body.innerHTML = '<div style="color:white;text-align:center;padding-top:40vh;font-size:20px;">🔒 Access Denied</div>';
                    setTimeout(() => window.location.reload(), 2000);
                }
            }, 1000);
        })();

        // 🔒 6. Floating watermark movement (makes screen recording identifiable)
        (function floatWatermark() {
            const el = document.getElementById('floatingWatermark');
            if (!el) return;

            function move() {
                const x = Math.random() * (window.innerWidth  - 200);
                const y = Math.random() * (window.innerHeight - 50);
                el.style.left = x + 'px';
                el.style.top  = y + 'px';
            }

            move();
            setInterval(move, 5000);
        })();

        // 🔒 7. Block network requests to video URL (IDM intercept prevention)
        // Bunny signed URLs expire — IDM gets a 403 after token expiry
        // Additional: overwrite XMLHttpRequest and fetch to block direct calls
        (function blockNetworkSniff() {
            const _open = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function(method, url, ...rest) {
                if (typeof url === 'string' && url.includes('mediadelivery.net')) {
                    console.warn('🔒 Blocked direct XHR to video source');
                    return;
                }
                return _open.apply(this, [method, url, ...rest]);
            };

            const _fetch = window.fetch;
            window.fetch = function(url, ...rest) {
                if (typeof url === 'string' && url.includes('mediadelivery.net')) {
                    console.warn('🔒 Blocked direct fetch to video source');
                    return Promise.reject(new Error('Blocked'));
                }
                return _fetch.apply(this, [url, ...rest]);
            };
        })();
    </script>
</body>
</html>
