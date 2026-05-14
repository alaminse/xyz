@extends('frontend.dashboard.app')
@section('title', 'Study Materials')

@section('content')
<div class="row">

    <div class="col-12 mb-4">
        <div class="card"
             style="border-radius:12px;
                    background:linear-gradient(135deg,#020b1c,#03132e);
                    color:white; padding:20px;">
            <div class="d-flex align-items-center">
                <i class="bx bx-file"
                   style="font-size:36px; color:#f8b84a; margin-right:14px;"></i>
                <div>
                    <h5 class="mb-1">Study Materials</h5>
                    <small style="color:#9aa4b2;">
                        All PDFs are protected.
                        Downloading, copying and screenshots are disabled.
                    </small>
                </div>
            </div>
        </div>
    </div>

    @forelse($pdfs as $pdf)
    <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
        <div class="card h-100"
             style="border-radius:10px;
                    border:1px solid #e8e8e8;
                    box-shadow:0 2px 10px rgba(0,0,0,.06);">
            <div class="card-body d-flex flex-column">

                <div class="d-flex align-items-start mb-3">
                    <div style="background:#fff0f0; border-radius:8px;
                                padding:10px; margin-right:12px; flex-shrink:0;">
                        <i class="bx bxs-file-pdf"
                           style="font-size:28px; color:#d9534f;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1"
                            style="font-weight:600; line-height:1.4;">
                            {{ $pdf->title }}
                        </h6>
                        @if($pdf->category)
                            <span style="background:#eef2ff; color:#4f46e5;
                                         font-size:11px; padding:2px 8px;
                                         border-radius:20px; font-weight:600;">
                                {{ $pdf->category }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($pdf->description)
                    <p class="text-muted"
                       style="font-size:13px; flex-grow:1;">
                        {{ Str::limit($pdf->description, 90) }}
                    </p>
                @endif

                <div style="font-size:12px; color:#9aa4b2; margin-bottom:12px;">
                    <i class="bx bx-file-blank"></i>
                    {{ $pdf->total_pages }} pages &bull;
                    <i class="bx bx-data"></i>
                    {{ $pdf->file_size_formatted }} &bull;
                    <i class="bx bx-lock-alt" style="color:#d9534f;"></i>
                    Protected
                </div>

                <a href="{{ route('secure-pdfs.view', $pdf->slug) }}"
                   class="btn btn-sm w-100"
                   style="background:linear-gradient(135deg,#020b1c,#03132e);
                          color:#f8b84a; border:none; border-radius:8px;
                          font-weight:600; padding:9px;">
                    <i class="bx bx-lock-open-alt"></i>
                    Open Secure Viewer
                </a>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center"
             style="border-radius:10px; padding:30px;">
            <i class="bx bx-info-circle" style="font-size:36px;"></i>
            <p class="mt-2 mb-0">No study materials available yet.</p>
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
