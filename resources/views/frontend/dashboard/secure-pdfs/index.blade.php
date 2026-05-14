@extends('frontend.dashboard.app')
@section('title', 'Study Materials')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection

@section('content')
<div class="row">

    <div class="col-12 mb-3">
        <div class="card topic-card">
            <div class="card-body">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-file-pdf" style="color:#d9534f;"></i>
                        Study Materials — {{ $course->name }}
                    </h5>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size:13px;">
                    <i class="bx bx-lock-alt" style="color:#d9534f;"></i>
                    All PDFs are protected. Downloading and copying are disabled.
                </p>
            </div>
        </div>
    </div>

    @if($isLocked)
        <div class="col-12 mb-3">
            <div class="alert d-flex align-items-center justify-content-between gap-3"
                 style="background:#fff8e1; border:1px solid #f1a909;
                        border-radius:8px; padding:14px 18px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lock-fill text-warning fs-5"></i>
                    <span class="fw-semibold text-dark">
                        Premium content is locked.
                        Showing free PDFs only.
                    </span>
                </div>
                <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                   class="btn btn-sm btn-warning fw-bold flex-shrink-0">
                    <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                </a>
            </div>
        </div>
    @endif

    @forelse($pdfs as $pdf)
    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
        <div class="card topic-card h-100">
            <div class="card-body d-flex flex-column">

                <div class="d-flex align-items-start mb-2">
                    <div style="background:rgba(217,83,79,0.1); border-radius:8px;
                                padding:8px; margin-right:10px; flex-shrink:0;">
                        <i class="fa fa-file-pdf-o fa-2x"
                           style="color:#d9534f;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1" style="font-weight:600; line-height:1.4;">
                            {{ $pdf->title }}
                        </h6>
                        <div style="font-size:11px; color:#9aa4b2;">
                            {{ $pdf->chapter?->name }}
                            @if($pdf->lesson)
                                &bull; {{ $pdf->lesson?->name }}
                            @endif
                        </div>
                    </div>
                </div>

                @if($pdf->description)
                    <p style="font-size:13px; color:#9aa4b2; flex-grow:1;">
                        {{ Str::limit($pdf->description, 80) }}
                    </p>
                @endif

                <div style="font-size:12px; color:#9aa4b2; margin-bottom:10px;">
                    <i class="fa fa-file-o"></i> {{ $pdf->total_pages }} pages
                    &bull;
                    <i class="fa fa-hdd-o"></i> {{ $pdf->file_size_formatted }}
                    &bull;
                    @if($pdf->isPaid)
                        <span style="color:#f1a909;">
                            <i class="bi bi-lock-fill"></i> Premium
                        </span>
                    @else
                        <span style="color:#5cb85c;">
                            <i class="bi bi-unlock-fill"></i> Free
                        </span>
                    @endif
                </div>

                {{-- Button logic same as Notes --}}
                @if($pdf->isPaid && $isLocked)
                    <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}"
                       class="btn btn-sm btn-warning fw-bold w-100">
                        <i class="bi bi-star-fill me-1"></i> Upgrade to Premium
                    </a>
                @else
                    <a href="{{ route('secure-pdfs.view', $pdf->slug) }}"
                       class="btn btn-sm button-yellow w-100">
                        <i class="fa fa-eye"></i> Open Secure Viewer
                    </a>
                @endif

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center" style="border-radius:10px; padding:30px;">
            <i class="fa fa-info-circle fa-3x mb-2"></i>
            <p class="mb-0">No study materials available yet.</p>
        </div>
    </div>
    @endforelse

</div>

@if($pdfs->hasPages())
    <div class="d-flex justify-content-center mt-2">
        {{ $pdfs->links() }}
    </div>
@endif
@endsection
