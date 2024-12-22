@extends('layouts.app')

@section('content')
<section class="login-register-section">
    <div class="login-register-container">
        <h2 class="login-register-header">Register</h2>
        <p class="login-register-subheader">Sign up to AuctionPeer</p>

        <!-- register form -->
        <form method="POST" action="{{ route('register') }}" class="form-container">
            @csrf

            <!-- username input -->
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter a username"
                       class="form-input" value="{{ old('username') }}" required>
                @error('username')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- email input -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email"
                       class="form-input" value="{{ old('email') }}" required>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- password input -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password"
                       class="form-input" required>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- password confirmation input -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password"
                       class="form-input" required>
            </div>

            <!-- submit button -->
            <button type="submit" class="primary-button">Register</button>
        </form>

        <!-- divider -->
        <div class="divider">
            <span class="divider-line"></span>
            <span class="divider-text">OR</span>
            <span class="divider-line"></span>
        </div>

        <!-- sign in link -->
        <div class="sign-up-link">
            <p>Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
            </p>
        </div>
    </div>
</section>
@endsection
