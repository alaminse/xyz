@extends('layouts.app')
@section('title', 'Set New Password — Medi Maniac')

@section('content')
<div class="mm-card">
    <div class="mm-card-accent"></div>
    <div class="mm-card-body">

        <div class="mm-icon-badge">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
            </svg>
        </div>

        <h1 class="mm-title">Set new password</h1>
        <p class="mm-subtitle">Choose a strong password for your account.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mm-field">
                <label for="email" class="mm-label">Email Address</label>
                <input
                    id="email"
                    type="email"
                    class="mm-input @error('email') is-invalid @enderror"
                    name="email"
                    value="{{ $email ?? old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="mm-invalid-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="mm-field">
                <label for="password" class="mm-label">New Password</label>
                <input
                    id="password"
                    type="password"
                    class="mm-input @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Min. 8 characters"
                >
                @error('password')
                    <p class="mm-invalid-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="mm-field">
                <label for="password-confirm" class="mm-label">Confirm Password</label>
                <input
                    id="password-confirm"
                    type="password"
                    class="mm-input"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Repeat your new password"
                >
            </div>

            <button type="submit" class="mm-btn">
                Reset Password
            </button>

        </form>

        <p class="mm-footer-text">
            <a href="{{ route('login') }}" class="mm-link">← Back to sign in</a>
        </p>

    </div>
</div>
@endsection
