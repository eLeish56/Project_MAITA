<!DOCTYPE html>
<html lang="en" style="min-width: 100vh !important;">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Ensure proper rendering and touch zooming on mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <!-- CSRF token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Teaching factory') }} – Reset Password</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <!-- Custom CSS -->
    <link href="/dist/css/style.min.css" rel="stylesheet">
    <!-- jQuery is required for preloader and CSRF setup -->
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
    <script>
        // Set the X-CSRF-TOKEN header for all jQuery AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="preloader" style="display: none; background: transparent;" id="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="main-wrapper mt-5">
        <!-- ============================================================== -->
        <!-- Reset Password wrapper -->
        <!-- ============================================================== -->
        <div class="pt-5 pb-2"></div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center pt-5">
            <div class="auth-box border-top border-secondary bg-dark p-4" style="min-width: 420px;">
                <div class="text-center pt-3 pb-3">
                    <span class="db">
                        <img src="{{ asset('assets/images/icon.png') }}" alt="logo" class="light-logo" />
                        <b class="text-white">TEACHING FACTORY</b>
                    </span>
                </div>
                <p class="text-white text-center mb-3">Masukkan email Anda dan buat password baru.</p>
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.store') }}">
            @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                    <!-- Email Address -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-success text-white h-100">
                                <i class="mdi mdi-email fs-4"></i>
                            </span>
                        </div>
                        <input type="email" name="email" value="{{ old('email', request()->email) }}"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               placeholder="Email" required autofocus />
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- New Password -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-warning text-white h-100">
                                <i class="mdi mdi-lock fs-4"></i>
                            </span>
                        </div>
                        <input type="password" name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               placeholder="Password Baru" required />
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- Confirm Password -->
                    <div class="input-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white h-100">
                                <i class="mdi mdi-lock-check fs-4"></i>
                            </span>
                        </div>
                        <input type="password" name="password_confirmation"
                               class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                               placeholder="Konfirmasi Password" required />
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <button class="btn btn-success float-end text-white" type="submit">
                            Reset Password
                        </button>
                    </div>
                    <div class="form-group mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Hide preloader after page load
        $(document).ready(function() {
            $(".preloader").fadeOut();
        });
    </script>
</body>

</html>