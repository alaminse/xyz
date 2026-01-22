@extends('layouts.frontend')
@section('title', $page_title)

@section('content')

@include('frontend.includes.bradcaump')

<section class="htc__blog__details__container bg__white ptb--80">
    <div class="container">
        @if($note)
        <div class="row">
            <div class="col-12">
                <div class="htc__blog__left__sidebar">
                    <div class="blog__details__top mb-3">
                        <h2>{{$note->title}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="htc__blog__details">
                    <div class="single__details">
                        {!! $note->description !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
