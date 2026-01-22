@extends('layouts.frontend')
@section('title', $page_title)
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/sba.css') }}">
@endsection
@section('content')
    @include('frontend.includes.bradcaump')

    <section class="htc__blog__area sba ptb--80 bg__white">
        <div class="container">
            <div class="row">
                @foreach ($chapters as $chapter)
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        @if (count($chapter->lessons) > 0)
                            <div class="dropdown">

                                <a class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $chapter->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($chapter->lessons as $lesson)
                                        <li>
                                            <a type="button" class="dropdown-item btn btn-link open-modal"
                                                data-chapter="{{ $chapter->id }}" data-lesson="{{ $lesson->id }}"
                                                data-toggle="modal" data-target="#exampleModal">
                                                {{ $lesson->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a type="button" class="btn btn-link open-modal" data-chapter="{{ $chapter->id }}"
                                data-lesson="0" data-toggle="modal" data-target="#exampleModal">
                                {{ $chapter->name }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="reloadPage()"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-12">
                                <form id="regForm">
                                    <div class="all-steps" id="all-steps">
                                    </div>
                                    <div class="all-tabs" id="all-tabs"> </div>

                                    <div style="overflow:auto;" id="nextprevious">
                                        <div style="float:right;">
                                            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                            <button type="button" id="nextBtn" disabled
                                                onclick="nextPrev(1)">Next</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('frontend/js/sba.js') }}"></script>
    @endpush
@endsection
