@extends('layouts.app')
@section('title', 'Confirm Password — Medi Maniac')

@section('content')
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
        </div>

        <h1 class="mm-title">Confirm Password</h1>
        <p class="mm-subtitle">Please verify your identity before continuing.</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mm-field">
                <label for="password" class="mm-label">Password</label>
                <input
                    id="password"
                    type="password"
                    class="mm-input @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                @error('password')
                    <p class="mm-invalid-msg">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mm-btn">
                Confirm &amp; Continue
            </button>

        </form>

        @if (Route::has('password.request'))
            <p class="mm-footer-text">
                <a href="{{ route('password.request') }}" class="mm-link">Forgot your password?</a>
            </p>
        @endif

    </div>
</div>
@endsection
