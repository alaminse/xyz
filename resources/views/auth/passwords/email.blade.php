@extends('layouts.app')
@section('title', 'Reset Password — Medi Maniac')

@section('content')
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.065 2C6.505 2 2 6.486 2 12s4.505 10 10.065 10C17.585 22 22 17.514 22 12S17.585 2 12.065 2zM13 17h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>

        <h1 class="mm-title">Forgot password?</h1>
        <p class="mm-subtitle">No worries — we'll email you a reset link right away.</p>

        @if (session('status'))
            <div class="mm-alert mm-alert-success">
                <span class="mm-alert-icon">✓</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mm-field">
                <label for="email" class="mm-label">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="mm-input @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="mm-invalid-msg">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mm-btn">
                Send Reset Link
            </button>

        </form>

        <p class="mm-footer-text">
            Remember it? <a href="{{ route('login') }}" class="mm-link">Back to sign in</a>
        </p>

    </div>
</div>
@endsection
