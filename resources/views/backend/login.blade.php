@extends('layouts.backend')

@section('title', 'Login')
@section('auth')
<body class="login">
    <div>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="POST" action="{{ route('login') }}">
                @csrf
              <h1>Admin Login</h1>
              <div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
              </div>
              <div>
                <button type="submit" class="btn btn-primary submit">
                    {{ __('Login') }}
                </button>
                @if (Route::has('password.request'))
                    <a class="reset_pass" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
</body>

@endsection
