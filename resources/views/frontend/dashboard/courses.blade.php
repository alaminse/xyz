@extends('frontend.dashboard.app')
@section('title', 'All Courses')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')
        <div class="border-0 d-flex justify-content-between align-items-center bg-white">
            <h5 class="mb-0 fw-semibold p-3">
                <i class="fa fa-book text-primary"></i> All Courses
            </h5>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-2">
            @forelse ($courses as $course)
                <div class="col">
                    <div class="card topic-card h-100 shadow-sm border-0 course-card-hover">
                        <!-- Course Banner -->
                        @if($course->banner)
                            <img src="{{ getImageUrl($course->banner) }}"
                                    class="card-img-top"
                                    alt="{{ $course->name }}"
                                    style="height: 200px; object-fit: cover;">
                        @else

                            <img src="{{ getImageUrl(null) }}"
                                    class="card-img-top"
                                    alt="{{ $course->name }}"
                                    style="height: 200px; object-fit: cover;">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <!-- Course Name -->
                            <h5 class="card-title fw-bold mb-3">{{ $course->name }}</h5>

                            <!-- Course Details Preview -->
                            @if($course->details)
                                <p class="card-text text-muted small flex-grow-1 mb-3">
                                    {{ Str::limit(strip_tags($course->details), 120) }}
                                </p>
                            @endif

                            <!-- Course Info -->
                            @if($course->is_pricing == 1 && $course->detail)
                                <div class="mb-3 p-2 bg-light rounded">
                                    <p class="card-text text-muted mb-2 small">
                                        <i class="fa fa-clock text-primary"></i>
                                        <strong>Duration:</strong> {{ $course->detail->duration }} {{ $course->detail->type }}
                                    </p>
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-tag text-success me-2"></i>
                                        @if($course->detail->sell_price)
                                            <span class="text-muted text-decoration-line-through me-2 small">
                                                {{ $course->detail->price }} ৳
                                            </span>
                                            <span class="text-success fw-bold fs-5">
                                                {{ $course->detail->sell_price }} ৳
                                            </span>
                                        @else
                                            <span class="text-dark fw-bold fs-5">{{ $course->detail->price }} ৳</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <span class="badge bg-info">Free Course</span>
                                </div>
                            @endif

                            <!-- View Details Button -->
                            <a href="{{ route('user.course.details', $course->slug) }}"
                                class="btn btn-primary w-100 mt-auto">
                                <i class="fa fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fa fa-info-circle fa-3x mb-3"></i>
                        <h5>No courses available at the moment.</h5>
                        <p class="mb-0">Please check back later for new courses.</p>
                    </div>
                </div>
            @endforelse
        </div>

    <style>
        .course-card-hover {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        .course-card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
        }
        .card-img-top {
            border-radius: 10px 10px 0 0;
        }
    </style>
@endsection
