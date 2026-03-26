@extends('frontend.dashboard.app')
@section('title', 'Note Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Notes Topic</h5>
                    <a class="btn btn-sm btn-warning" href="{{ route('notes.index', ['course' => $course_slug]) }}">Back</a>
                </div>

                <section id="info-utile" class="">
                    <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>

                    @isset($notes)
                        {{-- 🔒 Premium Banner (shown once at top when locked) --}}
                        @if($isLocked)
                            <div class="alert d-flex align-items-center justify-content-between gap-3 mb-3"
                                style="background-color: #fff8e1; border: 1px solid #f1a909; border-radius: 8px; padding: 14px 18px;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-lock-fill text-warning fs-5"></i>
                                    <span class="fw-semibold text-dark">This content is available for Premium members only.</span>
                                </div>
                                <a href="{{ route('courses.checkout', ['course' => $course_slug]) }}"
                                    class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                    <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                </a>
                            </div>
                        @endif

                        <div id="notes-container">
                            @foreach ($notes as $key => $note)
                                <div class="bg-light mb-1 d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 note-item rounded">
                                    <h5 class="flex-grow-1 note-title mb-0">
                                        {{ $note->title }}
                                        @if($note->isPaid)
                                            <i class="bi bi-lock-fill text-warning ms-1 small"></i>
                                        @else
                                            <i class="bi bi-unlock-fill text-success ms-1 small"></i>
                                        @endif
                                    </h5>

                                    @if($note->isPaid && $isLocked)
                                        {{-- Paid note + user not enrolled/freetrial → Upgrade --}}
                                        <a href="{{ route('courses.checkout', ['course' => $course_slug]) }}"
                                            class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                            <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                        </a>

                                    @elseif(!$note->isPaid)
                                        {{-- Free note → show Details + nudge to upgrade if locked --}}
                                        <div class="d-flex gap-2 flex-shrink-0">
                                            <a href="{{ route('notes.single.details', ['slug' => $note->slug, 'query' => $query ?? null]) }}"
                                                class="btn btn-sm button-yellow">Details</a>
                                            @if($isLocked)
                                                <a href="{{ route('courses.checkout', ['course' => $course_slug]) }}"
                                                    class="btn btn-sm btn-outline-warning fw-bold">
                                                    <i class="bi bi-star-fill me-1"></i> Go Premium
                                                </a>
                                            @endif
                                        </div>

                                    @else
                                        {{-- Paid note + fully enrolled → Details --}}
                                        <a href="{{ route('notes.single.details', ['slug' => $note->slug, 'query' => $query ?? null]) }}"
                                            class="btn btn-sm button-yellow flex-shrink-0">Details</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endisset
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const query = @json($query ?? '');
        if (query) {
            const notes = document.querySelectorAll('.note-item .note-title');
            notes.forEach(note => {
                const regex = new RegExp(query, 'gi');
                note.innerHTML = note.textContent.replace(regex, match => `<span style="color: #F1A909;">${match}</span>`);
            });
        }
    });
</script>

@endsection
