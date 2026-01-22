
@extends('layouts.frontend')
@section('title', 'Register')
@section('content')

<div class="d-flex justify-content-center">
    <div class="colsm-12 col-md-4 log-reg-form p-5">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h3 class="text-center">Register New Account </h3>
            <div class="mb-3">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter Name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- <div class="mb-3">
                <select class="form-select" aria-label="country">
                    <option value="">Choose One</option>
                    <option value="1" selected>Bangladesh</option>
                    <option value="2">India</option>
                    <option value="3">Pakistan</option>
                </select>
            </div>
            <div class="mb-3">
                <select class="form-select" aria-label="city">
                    <option value="">Choose City</option>
                    <option value="1" selected>Dhaka</option>
                    <option value="2">Barisal</option>
                    <option value="3">Khulna</option>
                </select>
            </div> --}}
            <div class="mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
            </div>
            <div class="mb-3 d-flex justify-content-center">
                <button type="submit">Register</button>
            </div>
        </form>
        <div class="mb-3 links">
            <div class="p-2 text-center">
                <a href="{{ route('login') }}">Already Have Account?</a>
            </div>
        </div>
        <div class="mb-3">
            <a href="#" class="btn btn-outline-info login-with">Login With Facebook</a>
        </div>
        <div class="mb-3">
            <a href="#" class="btn btn-outline-danger login-with">Login With Google</a>
        </div>
    </div>
</div>
@endsection
