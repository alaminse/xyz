@extends('layouts.frontend')
@section('title', 'Course Details')
@section('content')
    <section id="thehero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ getImageUrl($course->banner) }}');">
    {{-- <section id="thehero"> --}}
        <div class="the-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h1 class="active">Course Details</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-9">
                    <h2 class="fs-2 mb-5">{{ $course->name }}</h2>
                    <div class="row d-flex justify-content-center row-cols-1 row-cols-md-3 g-3 load-course mb-4">
                        @include('frontend.includes.course', ['courses' => $course->courses])
                    </div>

                    {!! $course->details !!}
                </div>
                <div class="col-sm-12 col-md-3">
                    <div class="course-card p-4">
                        <h4 class="pe-5">2024 Multi-Specialty Recruitment Assessment (MSRA) subscription options</h4>
                        <div class="my-3">
                            <h3 class="text-success">{{ $course->name }}</h3>
                            @if ($course->is_pricing == 1)
                            <p class="card-text text-muted">Duration: {{ $course->detail?->duration }} {{ $course->detail?->type }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($course->detail?->sell_price)
                                    <p class="card-text text-danger fw-bold mb-0">
                                        Price: <span style="text-decoration: line-through;">{{ $course->detail?->price }} <strong>৳
                                            </strong></span>
                                        <span class="card-text text-success fw-bold mb-0 ms-4">{{ $course->detail?->sell_price }}
                                            <strong>৳ </strong></span>
                                    </p>
                                @else
                                    <p class="card-text text-dark fw-bold mb-0">Price: {{ $course->detail?->price }}</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('courses.checkout', ['course' => $course->slug]) }}" class="btn enroll-btn {{ $course->is_pricing == 1 ? '' : 'disabled' }}">ENROLL</a>
                        <a href="{{ route('courses.checkout', ['course' => $course->slug, 'isTrial' => 'free-trial']) }}" class="btn enroll-btn mt-2" style="background: #0dcaf0">Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
