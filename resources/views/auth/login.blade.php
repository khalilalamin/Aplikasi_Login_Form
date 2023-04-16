<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</
                <br>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" name="password" required>
    </div>

    @if (app()->bound('captcha') && app('captcha')->isActivated())
        <div>
            {!! app('captcha')->display() !!}

            @if ($errors->has('g-recaptcha-response'))
                <span>{{ $errors->first('g-recaptcha-response') }}</span>
            @endif
        </div>
    @endif

    <div>
        <button type="submit">Login</button>
    </div>
</form>

<div>
    <a href="{{ route('password.request') }}">Forgot Your Password?</a>
</div>

<div>
    Don't have an account? <a href="{{ route('register') }}">Register</a>
</div>
</body>
</html>