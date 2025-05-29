<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Water Billing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            padding: 2rem 1rem;
            background: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .login-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="login-container">
        <div class="login-card bg-white">
            <div class="login-header">
                <h2 class="mb-0">Water Billing System</h2>
                <p class="mb-0">Sign in to your account</p>
            </div>
            <div class="login-body">
                @if (session('message'))
                    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                    <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus>
                                @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                    <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password">
                                @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               name="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                        </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            Sign In
                            </button>
                        <a href="{{ route('register') }}" class="btn btn-link text-center">
                            Don't have an account? Register
                                </a>
                            </div>
                    </form>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 