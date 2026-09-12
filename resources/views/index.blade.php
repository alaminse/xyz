@extends('layouts.frontend')
@section('title', 'MediManiac — From Aspirations to Achievements')

@section('css')
<style>
    /* ===================== HERO ===================== */
    .hero {
        padding: 64px 0 40px;
        overflow: hidden;
    }

    .hero-eyebrow {
        display: inline-block;
        font-family: var(--font-heading);
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--color-accent-dark);
        background: rgba(232, 150, 60, 0.12);
        padding: 6px 16px;
        border-radius: 999px;
        margin-bottom: 20px;
    }

    .hero h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1.15;
        margin-bottom: 20px;
        max-width: 16ch;
    }

    .hero-sub {
        font-size: 1.1rem;
        color: var(--color-body);
        max-width: 42ch;
        margin-bottom: 32px;
    }

    .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 40px; }

    .hero-stats {
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
        padding-top: 28px;
        border-top: 1px solid var(--color-border);
    }
    .hero-stat-num {
        font-family: var(--font-heading);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--color-ink);
        display: block;
    }
    .hero-stat-label { font-size: 0.85rem; color: var(--color-muted); }

    .hero-visual {
        position: relative;
    }
    .hero-visual .frame {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--color-border);
    }
    .hero-visual img { width: 100%; height: 100%; object-fit: cover; display: block; min-height: 320px; }

    .hero-float-card {
        position: absolute;
        bottom: -24px;
        left: -24px;
        background: #fff;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-soft);
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: 240px;
    }
    .hero-float-card .icon-wrap {
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--color-primary);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.2rem; flex-shrink: 0;
    }
    .hero-float-card .label { font-size: 0.8rem; color: var(--color-muted); }
    .hero-float-card .value { font-family: var(--font-heading); font-weight: 700; color: var(--color-ink); }

    @media (max-width: 991px) {
        .hero-float-card { position: static; margin-top: -40px; margin-left: 16px; }
    }

    /* ===================== COURSE SECTIONS ===================== */
    .section-heading-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .section-heading-row h2 { font-size: 1.6rem; margin: 0; }
    .section-heading-row .section-sub { color: var(--color-muted); font-size: 0.95rem; }

    .topic { padding: 40px 0; }
    .topic:not(:last-of-type) { border-bottom: 1px solid var(--color-border); }

    .course-card-slot {
        min-height: 180px;
    }
</style>
@endsection

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <span class="hero-eyebrow">FCPS &amp; postgraduate medical prep</span>
                <h1>From aspirations to achievements.</h1>
                <p class="hero-sub">
                    {{ $sliders->short_description1 ?? 'Structured courses, question banks, and mock exams built around the way medical students actually revise.' }}
                </p>
                <div class="hero-actions">
                    <a href="#courses" class="btn-primary-cta">
                        Explore courses <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-secondary-cta">
                        Talk to us
                    </a>
                </div>

                <div class="hero-stats">
                    <div>
                        <span class="hero-stat-num">10,000+</span>
                        <span class="hero-stat-label">Practice questions</span>
                    </div>
                    <div>
                        <span class="hero-stat-num">{{ $courses->count() ?? '20' }}+</span>
                        <span class="hero-stat-label">Structured courses</span>
                    </div>
                    <div>
                        <span class="hero-stat-num">24/7</span>
                        <span class="hero-stat-label">Access on any device</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="frame">
                        <img src="{{ getImageUrl($sliders->slider1 ?? '') }}" alt="MediManiac">
                    </div>
                    <div class="hero-float-card">
                        <div class="icon-wrap"><i class="bi bi-mortarboard-fill"></i></div>
                        <div>
                            <span class="value">{{ $sliders->heading1 ?? 'Rapid Fire' }}</span><br>
                            <span class="label">This week's focus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== COURSES ===================== --}}
<div id="courses">
    @foreach ($courses as $course)
        <section class="topic" id="{{ $course->slug }}">
            <div class="container">
                <div class="section-heading-row">
                    <div>
                        <h2>{{ $course->name }}</h2>
                        <span class="section-sub">Pick up where you left off, or start a new topic.</span>
                    </div>
                </div>
                <div class="row d-flex row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 load-course course-card-slot"></div>
            </div>
        </section>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const courseSlug = $(entry.target).attr('id');

                    $.ajax({
                        url: `/courses/${courseSlug}`,
                        type: 'GET',
                        success: function(data) {
                            const coursesContainer = $(entry.target).find('.load-course');
                            coursesContainer.html(data.html);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching courses:', error);
                        }
                    });
                    observer.unobserve(entry.target);
                }
            });
        });
        $('.topic').each(function() {
            observer.observe(this);
        });
    });
</script>
@endpush
