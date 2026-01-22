<div class="mt-3 p-3">
    <div class="row">
        {{-- Left Column: Info --}}
        <div class="col-md-6 mb-3">
            <h5><b>Title:</b> {{ $video->title }}</h5>
            <h5><b>Course:</b> {{ $video->courses->pluck('name')->join(', ') }}</h5>
            <h5><b>Chapter:</b> {{ $video->getChapterName() }}</h5>
            <h5><b>Lesson:</b> {{ $video->getLessonName() }}</h5>
        </div>

        {{-- Right Column: Video --}}
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height: 400px;">
            @if ($video->youtube_link)
                <iframe
                    width="100%"
                    height="100%"
                    src="{{ $video->youtube_link }}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            @elseif ($video->uploaded_link)
                <video width="100%" height="100%" controls autoplay controlsList="nodownload">
                    <source src="{{ asset($video->uploaded_link) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>
    </div>
</div>
