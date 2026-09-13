@if (isset($activeNotices) && $activeNotices->isNotEmpty())
    @php
        $topNotice = $activeNotices->first();
        $typeClass = 'floating-notice-' . $topNotice->type;
    @endphp

    <div id="floating-notice-bar" class="floating-notice-bar {{ $typeClass }}" data-notice-id="{{ $topNotice->id }}">
        <div class="floating-notice-track-wrap">
            <div class="floating-notice-track">
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($activeNotices as $notice)
                        <a href="{{ route('notices.show', $notice->slug) }}" class="floating-notice-item">
                            <i class="bi bi-megaphone-fill me-2"></i>{{ $notice->title ?? strip_tags($notice->message) }}
                        </a>
                        <span class="floating-notice-sep">&bull;</span>
                    @endforeach
                @endfor
            </div>
        </div>

        <a href="{{ route('notices.index') }}" class="floating-notice-viewall">View all</a>

        <button type="button" class="floating-notice-close" id="floating-notice-close-btn">&times;</button>
    </div>

    <script>
        (function () {
            const STORAGE_KEY = 'dismissed_notices';
            const bar = document.getElementById('floating-notice-bar');
            if (!bar) return;

            const noticeId = parseInt(bar.getAttribute('data-notice-id'), 10);

            function getDismissed() {
                try {
                    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
                } catch (e) {
                    return [];
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const dismissed = getDismissed();
                if (dismissed.includes(noticeId)) {
                    bar.style.display = 'none';
                }
            });

            document.getElementById('floating-notice-close-btn').addEventListener('click', function () {
                const dismissed = getDismissed();
                if (!dismissed.includes(noticeId)) {
                    dismissed.push(noticeId);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(dismissed));
                }
                bar.style.display = 'none';
            });
        })();
    </script>
@endif
