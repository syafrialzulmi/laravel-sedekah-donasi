<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <input type="email" name="email" value="{{ $email }}" required>

    <input type="password" name="password" placeholder="Password baru" required>

    <input type="password" name="password_confirmation" placeholder="Ulangi password" required>

    <button type="submit">Reset Password</button>
</form>