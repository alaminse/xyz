@extends('frontend.dashboard.app')
@section('title', 'Flash Card')
@section('content')
@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('frontend/css/notes.css') }}"> --}}
@endsection
<section class="htc__profile__area bg__white ptb--80">
    <div class="container">
        <div class="row d-flex align-items-stretch">
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class='dashboard'>
                    <div class="dashboard-nav">
                        <nav class="dashboard-nav-list">
                            {{-- @foreach ($chapters as $chapter)
                                @if (count($chapter->lessons) > 0)
                                    <div class='dashboard-nav-dropdown'><a href="#"
                                            class="dashboard-nav-item dashboard-nav-dropdown-toggle">
                                            {{ $chapter->name }} </a>
                                        <div class='dashboard-nav-dropdown-menu'>
                                            @foreach ($chapter->lessons as $lesson)
                                                <a href="{{ route('flash.card.question', ['chapter' => $chapter->slug, 'lesson' => $lesson->slug]) }}"
                                                    class="dashboard-nav-dropdown-item">{{ $lesson->name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('flash.card.question', ['chapter' => $chapter->slug, 'lesson' => '']) }}"
                                        class="dashboard-nav-item"> {{ $chapter->name }} </a>
                                @endif
                            @endforeach --}}
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-8 xs-mt-40">
                <div class="htc__profile__right">
                    <div class="card py-5">
                        <div class="card-body text-center my-5 py-5">
                            <h2 class="card-title">About Flash Card</h2>
                            <h6 class="card-text">This is the content of the card. It can include text, images, or other
                                elements.</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
    {{-- <script>
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
    </script> --}}
@endpush
@endsection
