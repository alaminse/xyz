@extends('layouts.frontend')
@section('title', 'Medi Maniac')
@section('css')
<style>
    .rectangle-div .main-div {
    padding: 20px;
}

.single-ractangle {
    background-color: #f7f7f7;
    border: 1px solid #f7f7f7;
    margin-bottom: 20px;
    text-align: center;
}

.single-ractangle img {
    max-width: 100%;
    height: auto;
}

.single-ractangle h4 {
    font-size: 1rem;
    text-align: center;
}

/* Responsive styles */
@media (max-width: 767px) {
    .rectangle-div .row {
        flex-direction: column;
        padding: 10px;
    }

    .single-ractangle {
        padding: 20px;
        margin-bottom: 15px;
    }
}
</style>
@endsection
@section('content')
    <section id="main-carousel">
        <div class="row" style="--x-gutter-x: 0rem;">
            <div class="col px-0">
                <div class="carousel slide kb-carousel carousel-fade" id="carouselKenBurns" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{getImageUrl($sliders->slider1 ?? '')}}"
                                class="d-block w-100 img-fluid" alt="Slide 1">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="text-uppercase">{{$sliders->heading1 ?? ''}}</h2>
                                <p class="text-center">{{ $sliders->short_description1 ?? ''}}</p>
                                <div class="button">
                                    <a href="{{ route('contact') }}" class="btn btn-warning">Request Quote</a>
                                    <a href="{{ route('about') }}" class="btn btn-outline-success">About</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{getImageUrl($sliders->slider2 ?? '')}}"
                                class="d-block w-100 img-fluid" alt="Slide 2">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="text-uppercase">{{$sliders->heading2 ?? ''}}</h2>
                                <p class="text-center">{{ $sliders->short_description2 ?? ''}}</p>
                                <div class="button">
                                    <a href="#" class="btn btn-warning">Request Quote</a>
                                    <a href="about.html" class="btn btn-outline-success">About</a>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{getImageUrl($sliders->slider3 ?? '')}}"
                                class="d-block w-100 img-fluid" alt="Slide 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="text-uppercase">{{$sliders->heading3 ?? ''}}</h2>
                                <p class="text-center">{{ $sliders->short_description3 ?? ''}}</p>
                                <div class="button">
                                    <a href="#" class="btn btn-warning">Request Quote</a>
                                    <a href="about.html" class="btn btn-outline-success">About</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev kb-control-prev" type="button" data-bs-target="#carouselKenBurns"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next kb-control-next" type="button" data-bs-target="#carouselKenBurns"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="rectangle-div container-xl">
        <div class="main-div">
            <div class="row">
                <div class="col-md-3 single-ractangle p-5">
                    <div class="d-flex">
                        <img class="p-2" src="{{ getImageUrl($snotes->img1 ?? '') }}" />
                        <h4 class="text-center p-2 flex-shrink-1">
                            {{$snotes->note1 ?? ''}}
                        </h4>
                    </div>
                </div>
                <div class="col-md-3 single-ractangle p-5">
                    <div class="d-flex">
                        <img class="p-2" src="{{ getImageUrl($snotes->img2 ?? '') }}" />
                        <h4 class="text-center p-2 flex-shrink-1">
                            {{$snotes->note2 ?? ''}}
                        </h4>
                    </div>
                </div>
                <div class="col-md-3 single-ractangle p-5">
                    <div class="d-flex">
                        <img class="p-2" src="{{ getImageUrl($snotes->img3 ?? '') }}" />
                        <h4 class="text-center p-2 flex-shrink-1">
                            {{$snotes->note3 ?? ''}}
                        </h4>
                    </div>
                </div>
                <div class="col-md-3 single-ractangle p-5">
                    <div class="d-flex">
                        <img class="p-2" src="{{ getImageUrl($snotes->img4 ?? '') }}" />
                        <h4 class="text-center p-2 flex-shrink-1">
                            {{$snotes->note4 ?? ''}}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top: 50px"></div>

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
