@extends('layouts.frontend')
@section('title', 'Reset Password')
@section('content')
@php
    $page_title = "Reset Password";
@endphp

@include('frontend.includes.bradcaump')
<section class="htc__login__container text-center ptb--80 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="login__area__wrap">
                    <div class="login__inner">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <h2>Reset Password</h2>

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="login__form__box">
                                <div class="login__form">
                                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                        {{-- <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}


                        <div class="login__btn">
                            <button type="submit" class="htc__btn btn--theme">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</section>

@endsection

