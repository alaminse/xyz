@extends('frontend.dashboard.app')
@section('title', 'Study Materials')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card topic-card">
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Study Materials Topic</h5>
                        <a class="btn btn-sm btn-warning"
                           href="{{ route('secure-pdfs.index', ['course' => $course->slug]) }}">
                            Back
                        </a>
                    </div>

                    <section id="info-utile" class="">
                        <h3 class="text-center text-uppercase mb-3 lead-h-text text-white">Topic</h3>

                        @isset($pdfs)

                            {{-- Premium lock banner --}}
                            @if ($isLocked)
                                <div class="alert d-flex align-items-center justify-content-between gap-3 mb-3"
                                    style="background-color:#fff8e1; border:1px solid #f1a909;
                                           border-radius:8px; padding:14px 18px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-lock-fill text-warning fs-5"></i>
                                        <span class="fw-semibold text-dark">
                                            This content is available for Premium members only.
                                        </span>
                                    </div>
                                    <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                        class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                        <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                    </a>
                                </div>
                            @endif

                            <div id="pdfs-container">
                                @foreach ($pdfs as $pdf)
                                    <div class="bg-light mb-1 d-flex flex-column flex-md-row
                                                align-items-start align-items-md-center gap-2
                                                pdf-item rounded p-2">

                                        <h5 class="flex-grow-1 pdf-title mb-0">
                                            <i class="fa fa-file-pdf-o text-danger me-1"></i>
                                            {{ $pdf->title }}
                                            @if ($pdf->isPaid)
                                                <i class="bi bi-lock-fill text-warning ms-1 small"></i>
                                            @else
                                                <i class="bi bi-unlock-fill text-success ms-1 small"></i>
                                            @endif
                                        </h5>

                                        <small class="text-muted me-2" style="white-space:nowrap;">
                                            {{ $pdf->total_pages }} pages
                                            &bull; {{ $pdf->file_size_formatted }}
                                        </small>

                                        @if ($pdf->isPaid && $isLocked)
                                            <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                                class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                                                <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                                            </a>

                                        @elseif (!$pdf->isPaid)
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ route('secure-pdfs.view', ['slug' => $pdf->slug, 'course' => $course->slug]) }}"
                                                    class="btn btn-sm button-yellow">
                                                    <i class="fa fa-eye"></i> Open
                                                </a>
                                                @if ($isLocked)
                                                    <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                                                        class="btn btn-sm btn-outline-warning fw-bold">
                                                        <i class="bi bi-star-fill me-1"></i> Go Premium
                                                    </a>
                                                @endif
                                            </div>

                                        @else
                                            <a href="{{ route('secure-pdfs.view', ['slug' => $pdf->slug, 'course' => $course->slug]) }}"
                                                class="btn btn-sm button-yellow flex-shrink-0">
                                                <i class="fa fa-eye"></i> Open
                                            </a>
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
@endsection
