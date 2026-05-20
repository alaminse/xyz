@extends('frontend.dashboard.app')
@section('title', 'Study Materials')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
    <style>
        .list-group {
            background-color: transparent !important;
        }
        .list-group .list-group-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-yellow:hover,
        .button-white:hover {
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        {{-- Left: Chapters accordion --}}
        <div class="col-sm-12 col-md-6 col-lg-5 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Study Materials Topic</h5>
                    </div>
                    <section id="info-utile">
                        <div class="container">
                            <div class="accordion" id="accordionExample">
                                @foreach ($chapters as $key => $chapter)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $key }}">
                                            <a class="accordion-button collapsed" href="#"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse-{{ $key }}"
                                                aria-expanded="false"
                                                aria-controls="collapse-{{ $key }}">
                                                {{ $chapter->name }}
                                            </a>
                                        </h2>
                                        <div id="collapse-{{ $key }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="heading-{{ $key }}"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                                    @foreach ($chapter->lessons as $lesson)
                                                        <li class="list-group-item">
                                                            <a href="{{ route('secure-pdfs.details', [
                                                                'course'   => $course->slug,
                                                                'chapter'  => $chapter->slug,
                                                                'lesson'   => $lesson->slug,
                                                            ]) }}">
                                                                {{ $lesson->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        {{-- Right: Recent PDFs --}}
        <div class="col-sm-12 col-md-6 col-lg-7 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Recent</h5>
                    </div>

                    @if ($isLocked)
                        <div class="alert d-flex align-items-center justify-content-between gap-3 mt-3"
                            style="background:#fff8e1; border:1px solid #f1a909;
                                   border-radius:8px; padding:14px 18px;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-lock-fill text-warning fs-5"></i>
                                <span class="fw-semibold text-dark">
                                    Premium content is locked. Showing free PDFs only.
                                </span>
                            </div>
                            <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                            </a>
                        </div>
                    @endif

                    @foreach ($latest as $item)
                        <div class="mt-3 p-3 topic-card">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                                <h5 class="flex-grow-1 mb-0">
                                    {{ $item->title }}
                                    @if ($item->isPaid)
                                        <i class="bi bi-lock-fill text-warning ms-1 small"></i>
                                    @else
                                        <i class="bi bi-unlock-fill text-success ms-1 small"></i>
                                    @endif
                                </h5>
                                @if ($item->isPaid && $isLocked)
                                    <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                        class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                        <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                    </a>
                                @else
                                    <a href="{{ route('secure-pdfs.view', ['course_slug' => $course->slug, 'slug' => $item->slug]) }}"
                                        class="btn btn-sm button-yellow flex-shrink-0">
                                        <i class="fa fa-eye"></i> Open
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
