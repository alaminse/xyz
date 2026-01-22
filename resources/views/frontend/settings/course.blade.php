@extends('layouts.frontend')
@section('title', 'Course')
@section('content')
    <section id="thehero">
        <div class="the-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h1 class="active">Course</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @foreach ($courses as $course)
        <section class="topic" id="{{ $course->slug }}">
            <div class="container-xl">
                <h2 class="header">{{ $course->name }}</h2>
                <div class="row d-flex justify-content-center row-cols-1 row-cols-md-4 g-4 load-course"></div>
            </div>
        </section>
    @endforeach

    <div style="margin-top: 50px"></div>

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

@endsection
