@extends('layouts.frontend')
@section('title', 'Contact')
@section('content')
<section id="thehero">
    <div class="the-inner">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h1 class="active">Home <a href="#" class="text-white">/ Contact</a></h1>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about-us">
    <h2 class="text-center fs-2 heading text-uppercase">FAQ</h2>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-card">
                    <h3 class="mb-4">Keep In Touch</h3>
                    <div class="form">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="name" placeholder="Your Name">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" placeholder="Your Email">
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="phone" placeholder="Phone">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="message" rows="3" placeholder="Message"></textarea>
                        </div>
                        <div class="mb-3">
                            <button>Send Message</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                @php
                    $value = [];
                    if ($faqs?->value) {
                        $value = json_decode($faqs->value);
                    }
                @endphp
                <div class="accordion" id="accordionExample">
                    @if (!empty($value))
                        @foreach ($value as $index => $item)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{$index}}" aria-expanded="false'" aria-controls="collapse-{{$index}}">
                                        {{ $item->question }}
                                    </button>
                                </h2>
                                <div id="collapse-{{$index}}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {{ $item->answer }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
