<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile - PetroPlan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
            background-color: #f5f6fa;
        }

        .profile-page {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .card-profile {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            color: #343a40;
        }

        @media (max-width: 576px) {
            .section-title {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="container profile-page">
        <div class="text-center mb-5 mt-2">
            <img src="{{ asset('assets/img/PetroPlan-logo.png') }}" alt="PetroPlan Logo" style="height: 80px;">
            <h2 class="mt-3">My Profile</h2>
            <p class="text-muted">Manage your account settings</p>

            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary mt-3">
                ← Back to Dashboard
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">

                <!-- Update Profile Info -->
                <div class="card card-profile p-4 mb-4">
                    <div class="section-title">Update Profile Information</div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="card card-profile p-4 mb-4">
                    <div class="section-title">Update Password</div>
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                {{-- <div class="card card-profile p-4 mb-4">
                    <div class="section-title">Delete Account</div>
                    @include('profile.partials.delete-user-form')
                </div> --}}

            </div>
        </div>
    </div>

    <!-- JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
</body>

</html>