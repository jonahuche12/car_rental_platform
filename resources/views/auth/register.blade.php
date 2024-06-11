@extends('layouts.app')

@section('title', 'Central School System - Register')
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
    .google-btn img {
        height: 20px;
        width: 20px;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>{{ __('Register') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="social-auth-links text-center mb-4">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-light btn-block google-btn mb-3">
                                <img src="{{ asset('dist/img/googlebtn.png') }}" alt="Google">
                                Continue with Google
                            </a>
                        </div>

                        <div class="form-group">
                            <input id="first_name" type="text" 
                                   class="form-control @error('first_name') is-invalid @enderror" 
                                   name="first_name" value="{{ old('first_name') }}" 
                                   required autocomplete="first_name" 
                                   autofocus placeholder="Enter Your First Name">

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="last_name" type="text" 
                                   class="form-control @error('last_name') is-invalid @enderror" 
                                   name="last_name" value="{{ old('last_name') }}" 
                                   required autocomplete="last_name" 
                                   autofocus placeholder="Enter Your Last Name">

                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="middle_name" type="text" 
                                   class="form-control @error('middle_name') is-invalid @enderror" 
                                   name="middle_name" value="{{ old('middle_name') }}" 
                                   required autocomplete="middle_name" 
                                   autofocus placeholder="Enter Your Middle Name">

                            @error('middle_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="email" type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email" 
                                   placeholder="Enter Your Email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password" 
                                   placeholder="Enter Your Password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input id="password-confirm" type="password" 
                                   class="form-control" name="password_confirmation" 
                                   required autocomplete="new-password" 
                                   placeholder="Confirm Your Password">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
