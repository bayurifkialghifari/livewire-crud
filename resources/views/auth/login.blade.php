@extends('layouts.auth')


@section('content')
    <div class="auth-logo">
        <a href="index.html"><img src="{{ url('mazer') }}/assets/images/logo/logo.svg" alt="Logo" /></a>
    </div>
    <h1 class="auth-title">{{ __('Login') }}</h1>

    <form method="POST" action="{{ route('login') }}">

        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror"
                placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                placeholder="Password" name="password" required autocomplete="current-password" />
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- <div class="form-check form-check-lg d-flex align-items-end">
            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault" />
            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                Keep me logged in
            </label>
        </div> --}}
        <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
            {{ __('Login') }}
        </button>
    </form>
    {{-- <div class="text-center mt-5 text-lg fs-4">
        <p class="text-gray-600">
            Don't have an account?
            <a href="auth-register.html" class="font-bold">Sign up</a>.
        </p>
        <p>
            <a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.
        </p>
    </div> --}}
@endsection
