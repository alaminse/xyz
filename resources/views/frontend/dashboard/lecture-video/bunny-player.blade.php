<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
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
        }

        /* 🔒 Prevent context menu */
        * {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }
    </style>
</head>
<body>
    <div id="player-container">
        <iframe
            src="{{ $signedUrl }}"
            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
            allowfullscreen
            oncontextmenu="return false;">
        </iframe>

        <div class="watermark">
            🔒 {{ $userEmail }}
        </div>
    </div>

    <script>
        // 🔒 Disable right-click
        document.addEventListener('contextmenu', e => e.preventDefault());

        // 🔒 Disable F12, Ctrl+Shift+I, Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
                return false;
            }
        });

        // 🔒 Disable text selection
        document.onselectstart = () => false;
    </script>
</body>
</html>
