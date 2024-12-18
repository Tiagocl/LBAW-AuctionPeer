<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input placeholder="Enter your Email" type="email" name="email" required>
    <input placeholder="Enter your password" type="password" name="password" required>
    <input placeholder="Confirm your Password" type="password" name="password_confirmation" required>
    <button type="submit">Reset Password</button>
</form>