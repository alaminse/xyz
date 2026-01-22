@extends('layouts.frontend')
@section('title', 'Privacy Policy')
@section('content')
<section class="about-us">
    <div class="container" style="overflow: hidden">
        @if (isset($value) && !empty($value))
            {!! $value !!}
        @endif
    </div>
</section>

@endsection
