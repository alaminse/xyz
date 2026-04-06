@extends('frontend.dashboard.app')
@section('title', 'Written Assessment Details')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')

<div class="row">
    <div class="col-sm-12 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Written Assessment Details</h5>
                    <a class="btn btn-sm btn-warning"
                        href="{{ route('writtens.details', ['course' => $course_slug, 'chapter' => $assessment->chapter?->slug, 'lesson' => $assessment->lesson?->slug]) }}">
                        Back
                    </a>
                </div>

                <section id="info-utile" class="">
                    @isset($assessment)
                        <div class="mt-3 p-3">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">

                                    <h2 class="mb-4 note-title">
                                        <u>Question:</u> {!! $assessment->question !!}
                                    </h2>

                                    @if($isPaid && $isLocked)
                                        {{-- 🔒 Should not reach here due to controller redirect --}}
                                        <div class="text-center py-4 px-3"
                                            style="background:#fff8e1; border:2px dashed #ffc107; border-radius:12px;">
                                            <i class="bi bi-lock-fill text-warning" style="font-size:2.5rem;"></i>
                                            <h5 class="mt-3 mb-2">Answer is locked</h5>
                                            <p class="text-muted mb-3">
                                                This is Premium content. Please upgrade your plan to see the answer.
                                            </p>
                                            <a href="{{ route('courses.checkout', ['course' => $course_slug]) }}"
                                                class="btn btn-warning fw-bold">
                                                <i class="bi bi-unlock-fill"></i> Upgrade to Premium
                                            </a>
                                        </div>
                                    @else
                                        <div class="note-description">
                                            <h2>Answer</h2>
                                            {!! $assessment->answer !!}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endisset
                </section>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const query = @json($query ?? '');
            if (query) {
                const noteDescription = document.querySelector('.note-description');
                const noteTitle = document.querySelector('.note-title');

                if (noteDescription) {
                    const regex = new RegExp(query, 'gi');
                    noteDescription.innerHTML = noteDescription.innerHTML.replace(regex,
                        match => `<span style="background: #F1A909; color: white">${match}</span>`);
                }
                if (noteTitle) {
                    const regex = new RegExp(query, 'gi');
                    noteTitle.innerHTML = noteTitle.innerHTML.replace(regex,
                        match => `<span style="background: #F1A909; color: white">${match}</span>`);
                }
            }
        });
    </script>
@endpush
@endsection
