@extends('layouts.frontend')
@section('title', $course->name)

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="course-hero">
    <div class="container">
        <div class="course-breadcrumb">
            <a href="{{ url('/') }}">Home</a> / <span>{{ $course->name }}</span>
        </div>
        <h1>{{ $course->name }}</h1>

        @if ($course->is_pricing == 1)
            <div class="course-meta-row">
                <span class="course-meta-item"><i class="bi bi-clock"></i> {{ $course->detail?->duration }} {{ $course->detail?->type }}</span>
                @if ($isEnrolled)
                    <span class="course-meta-item"><i class="bi bi-check-circle-fill text-success"></i> You're enrolled</span>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- ===================== BODY ===================== --}}
<section class="course-body">
    <div class="container">
        <div class="row gy-5">

            {{-- LEFT: content --}}
            <div class="col-lg-8">
                @if ($course->banner)
                    <div class="course-thumb">
                        <img src="{{ getImageUrl($course->banner) }}" alt="{{ $course->name }}">
                    </div>
                @endif

                @if ($course->details)
                    <h2 class="section-title">About this course</h2>
                    <div class="course-description">{!! $course->details !!}</div>
                @endif

                @if ($includedFeatures->isNotEmpty())
                    <h2 class="section-title">What's included</h2>
                    <div class="feature-grid">
                        @foreach ($includedFeatures as $feature)
                            <div class="feature-chip">
                                <i class="bi {{ $feature['icon'] }}"></i> {{ $feature['label'] }}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($course->chapters->isNotEmpty())
                    <h2 class="section-title">Curriculum</h2>
                    <div class="curriculum-list">
                        @foreach ($course->chapters as $index => $chapter)
                            <div class="curriculum-item">
                                <button class="curriculum-header" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#curr-{{ $chapter->id }}">
                                    <span>{{ $chapter->name }}</span>
                                    <span class="lesson-count">{{ $chapter->lessons->count() }} lessons</span>
                                </button>
                                <div id="curr-{{ $chapter->id }}" class="collapse">
                                    <div class="curriculum-body">
                                        @forelse ($chapter->lessons as $lesson)
                                            <div class="curriculum-lesson">
                                                <i class="bi bi-play-circle"></i> {{ $lesson->name }}
                                            </div>
                                        @empty
                                            <div class="curriculum-lesson text-muted">No lessons yet.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($course->children->isNotEmpty())
                    <h2 class="section-title mt-5">Included courses</h2>
                    @foreach ($course->children as $child)
                        <a href="{{ route('courses.show', $child->slug) }}" class="subcourse-card">
                            <img src="{{ getImageUrl($child->banner) }}" alt="{{ $child->name }}">
                            <div>
                                <div class="name">{{ $child->name }}</div>
                                <span class="text-muted" style="font-size:0.85rem;">
                                    {{ $child->detail?->duration }} {{ $child->detail?->type }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

            {{-- RIGHT: sticky enroll card --}}
            <div class="col-lg-4">
                <div class="enroll-card">
                    @if ($course->is_pricing == 1)
                        <div class="enroll-price-row">
                            @if ($course->detail?->sell_price > 0)
                                <span class="enroll-price">৳{{ $course->detail->sell_price }}</span>
                                <span class="enroll-price-old">৳{{ $course->detail->price }}</span>
                            @else
                                <span class="enroll-price">৳{{ $course->detail?->price }}</span>
                            @endif
                        </div>
                        <div class="enroll-duration">{{ $course->detail?->duration }} {{ $course->detail?->type }} access</div>
                    @endif

                    @auth
                        @if ($isEnrolled)
                            <a href="{{ url('/dashboard') }}" class="btn-primary-cta">
                                <i class="bi bi-play-fill"></i> Go to course
                            </a>
                        @else
                            <a href="{{ route('courses.checkout', $course->slug) }}" class="btn-primary-cta">
                                Enroll now <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="{{ route('courses.checkout', ['course' => $course->slug, 'isTrial' => 'free-trial']) }}" class="btn-secondary-cta">
                                Start free trial
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-cta">
                            Log in to enroll <i class="bi bi-arrow-right"></i>
                        </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
