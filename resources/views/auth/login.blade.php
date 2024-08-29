<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }

        .navbar-brand {
            font-family: 'Great Vibes', cursive;
            font-size: 2em;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            background-color: #fff;
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            color: #333;
        }

        .card-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 1rem;
            background-color: #f9f9f9;
            color: #333;
        }

        .form-control:focus {
            border-color: #333;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #333;
            border: none;
            border-radius: 5px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            width: 100%;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #555;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .invalid-feedback {
            font-size: 0.875em;
            color: #e74c3c;
        }

        .switch-link {
            text-align: center;
            margin-top: 1rem;
        }

        .switch-link a {
            color: #333;
            text-decoration: none;
        }

        .switch-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="card-header">{{ __('Login') }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                @if (Route::has('password.request'))
                <div class="switch-link">
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
                @endif
            </form>
            <div class="switch-link">
                <p>{{ __('Don\'t have an account?') }} <a href="{{ route('register') }}">{{ __('Register') }}</a></p>
            </div>
        </div>
    </div>
</body>

</html>