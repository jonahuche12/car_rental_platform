@extends('layouts.app')
@section('title', 'Central School System - Login')
@section('sidebar')
    @include('sidebar')
@endsection

@section('style')

<!-- Additional CSS for styling improvements -->
<style>
    body {
        background: url('/path/to/your/background-image.jpg') no-repeat center center fixed;
        background-size: cover;
    }
    .card {
        border-radius: 10px;
    }
    .form-control {
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px;
        font-size: 1.1rem;
    }
    .btn-outline-light {
        color: #db4437;
        border-color: #db4437;
        background-color: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-outline-light:hover {
        background-color: rgba(219, 68, 55, 0.1);
    }
    .btn-link {
        color: #007bff;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>{{ __('Login') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="social-auth-links text-center mb-4">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-light btn-block google-btn mb-3">
                                <img src="{{ asset('dist/img/googlebtn.png') }}" alt="Google" style="height: 20px; width: 20px; margin-right: 10px;">
                                Continue with Google
                            </a>
                        </div>

                        <div class="form-group">
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" 
                                   autofocus placeholder="Enter Your Email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password" 
                                   placeholder="Enter Your Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group form-check text-left">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
