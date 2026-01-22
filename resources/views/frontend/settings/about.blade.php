@extends('layouts.frontend')
@section('title', 'About Us')
@section('content')
<section id="thehero">
    <div class="the-inner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h1 class="active">About Us</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-us">
    <h2 class="text-center fs-2 heading">About Us</h2>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 mb-3 ">
                <img src="{{ getImageUrl($value->banner ?? '')}}" class="img-fluid" alt="About Us">
            </div>
            <div class="col-sm-6 p-3" style="background: rgba(247, 247, 247, 1)">
                {!! $value->description ?? '' !!}
            </div>
        </div>
    </div>
</section>

@endsection
