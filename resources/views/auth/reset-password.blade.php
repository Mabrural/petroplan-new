<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Reset Password - PetroPlan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function () { sessionStorage.fonts = true; }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background-color: #f0f2f5;
        }

        .login-page {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            padding: 2.5rem;
            width: 100%;
            max-width: 460px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo img {
            height: 100px;
            max-width: 100%;
        }

        .login-subtitle {
            text-align: center;
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-control {
            height: 45px;
            border-radius: 6px;
        }

        .btn-reset {
            height: 45px;
            border-radius: 6px;
            font-weight: 600;
            width: 100%;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem;
                border-radius: 8px;
            }

            .login-logo img {
                height: 80px;
            }
        }
    </style>
</head>

<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('assets/img/PetroPlan-logo.png') }}" alt="PetroPlan Logo">
            </div>
            <p class="login-subtitle">Please enter your new password below.</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Hidden Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon">
                        <span class="input-icon-addon"><i class="fa fa-envelope"></i></span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Enter your email">
                    </div>
                    @error('email')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="form-group mt-4">
                    <label for="password">New Password</label>
                    <div class="input-icon">
                        <span class="input-icon-addon"><i class="fa fa-lock"></i></span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Enter new password">
                    </div>
                    @error('password')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group mt-4">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-icon">
                        <span class="input-icon-addon"><i class="fa fa-lock"></i></span>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Confirm new password">
                    </div>
                    @error('password_confirmation')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-reset">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
</body>

</html>
