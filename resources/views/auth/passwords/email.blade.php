@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

    <div class="rectangle-div">
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email" class="">Email</label>
        <input placeholder="Enter your Email" type="email" name="email" required>
        <button type="submit">Send Password Reset Link</button>
    </form>
    </div>
@endsection