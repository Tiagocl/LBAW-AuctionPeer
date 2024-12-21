@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="login-register-section">
    <div class="login-register-container">
        <h2 class="login-register-header">Login</h2>
        <p class="login-register-subheader">Sign in to your AuctionPeer account</p>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="form-container">
            @csrf

            <!-- Email Input -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email"
                       class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password"
                       class="form-input" required>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Forgot Password -->
            <p class="forgot-password">Forgot your password?
                <a href="{{ route('password.request') }}">Click Here</a>
            </p>

            <!-- Remember Me -->
            <div class="remember-me">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember" class="checkbox">
                    Remember me
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="primary-button">Login</button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span class="divider-line"></span>
            <span class="divider-text">OR</span>
            <span class="divider-line"></span>
        </div>

        <!-- Sign Up Link -->
        <div class="sign-up-link">
            <p>Don't have an account?
                <a href="{{ route('register') }}">Sign up</a>
            </p>
        </div>
    </div>
</section>
@endsection
