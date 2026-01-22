@extends('layouts.frontend')
@section('title', $page_title)
@section('content')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/notes.css') }}">
@endsection

@include('frontend.includes.bradcaump')

<section class="htc__profile__area bg__white ptb--80">
    <div class="container">
        <div class="row d-flex align-items-stretch">
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class='dashboard'>
                    <div class="dashboard-nav">
                        <nav class="dashboard-nav-list">
                            @foreach ($chapters as $chapter)
                                @if (count($chapter->lessons) > 0)
                                    <div class='dashboard-nav-dropdown'><a href="#"
                                            class="dashboard-nav-item dashboard-nav-dropdown-toggle">
                                            {{ $chapter->name }} </a>
                                        <div class='dashboard-nav-dropdown-menu'>
                                            @foreach ($chapter->lessons as $lesson)
                                                <a href="{{ route('note.lesson', ['chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}"
                                                    class="dashboard-nav-dropdown-item">{{ $lesson->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('admin.notes.chapter', $chapter->slug) }}" class="dashboard-nav-item">
                                        {{ $chapter->name }} </a>
                                @endif
                            @endforeach
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-8 xs-mt-40">
                <div class="htc__profile__right">
                    @if (count($notes) > 0)
                        <div class="row pb--40 htc__blog__wrap">
                            @foreach ($notes as $note)
                                <div class="col-12 my-2">
                                    <div class="card text-black">
                                        <a href="{{ route('note.show', $note->slug) }}">
                                            <div class="card-header">
                                                <h5 class="card-title">{{ $note->title }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{!! substr($note->description, 0, 150) !!}</p>
                                                <div class="blog__btn">
                                                    <a class="read__more__btn" href="{{ route('note.show', $note->slug) }}">Read More</a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    {{-- <div class="blog">
                                        <div class="blog__details">
                                            <h2><a href="{{ route('note.show', $note->slug) }}">{{ $note->title }}</p>
                                                    <div class="blog__btn">
                                                        <a class="read__more__btn"
                                                            href="{{ route('note.show', $note->slug) }}">Read More</a>
                                                    </div>
                                        </div>
                                    </div> --}}
                                </div>
                            @endforeach
                        </div>
                        @include('frontend.includes.pagination', ['paginator' => $notes])
                    @else
                        <h5 class="text-center text-danger mt-5">No notes available.</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script src="{{ asset('frontend/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script>
        const mobileScreen = window.matchMedia("(max-width: 990px )");
        $(document).ready(function() {
            $(".dashboard-nav-dropdown-toggle").click(function() {
                $(this).closest(".dashboard-nav-dropdown")
                    .toggleClass("show")
                    .find(".dashboard-nav-dropdown")
                    .removeClass("show");
                $(this).parent()
                    .siblings()
                    .removeClass("show");
            });
            $(".menu-toggle").click(function() {
                if (mobileScreen.matches) {
                    $(".dashboard-nav").toggleClass("mobile-show");
                } else {
                    $(".dashboard").toggleClass("dashboard-compact");
                }
            });
        });
    </script>
@endpush
@endsection
