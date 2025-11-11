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
    <title>{{ config('app.name', 'Teaching factory') }}</title>
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
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- An additional preloader used by asynchronous operations -->
    <div class="preloader" style="display: none; background: transparent;" id="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div class="main-wrapper mt-5">
        <!-- ============================================================== -->
        <!-- Login wrapper -->
        <!-- ============================================================== -->
        <div class="pt-5 pb-2"></div>
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center pt-5">
            <div class="auth-box border-top border-secondary bg-dark p-4">
                <div id="loginform">
                    <div class="text-center pt-3 pb-3">
                        <span class="db">
                            <img src="assets/images/icon.png" alt="logo" class="light-logo" />
                            <b class="text-white">TEACHING FACTORY</b>
                        </span>
                    </div>
                    <!-- Login Form -->
                    <form class="form-horizontal mt-3" id="loginform" action="{{ isset($isCustomer) && $isCustomer ? route('customer.login.post') : route('login') }}" method="POST">
                        @csrf
                        <!-- Display validation errors at the top -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <!-- Username or Email -->
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white h-100" id="basic-addon1">
                                            <i class="mdi mdi-account fs-4"></i>
                                        </span>
                                    </div>
                                    <!-- Determine the input name and type dynamically based on customer flag -->
                                    @php
                                        // When $isCustomer is true, use email; otherwise use username
                                        $inputName = isset($isCustomer) && $isCustomer ? 'email' : 'username';
                                        $inputType = isset($isCustomer) && $isCustomer ? 'email' : 'text';
                                        $placeholder = isset($isCustomer) && $isCustomer ? 'Email' : 'Username';
                                    @endphp
                                    <input type="{{ $inputType }}" name="{{ $inputName }}"
                                           value="{{ old($inputName) }}"
                                           class="form-control form-control-lg @error($inputName) is-invalid @enderror"
                                           placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}" aria-describedby="basic-addon1"
                                           required autofocus />
                                    @error($inputName)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Password with toggle -->
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white h-100" id="basic-addon2">
                                            <i class="mdi mdi-lock fs-4"></i>
                                        </span>
                                    </div>
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           placeholder="Password" name="password" id="password" aria-label="Password" aria-describedby="basic-addon2"
                                           required />
                                    <div class="input-group-append">
                                        <span class="input-group-text h-100" style="cursor: pointer;" onclick="togglePassword('password', 'passIcon')">
                                            <i class="mdi mdi-eye fs-4" id="passIcon"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <!-- Registration link -->
                            <div class="row mt-3 align-items-center">
                                <a href="{{ route('register') }}"
                                   class="inline-block px-4 py-2 mt-2 rounded-lg border hover:bg-gray-50 transition">
                                    Buat Akun
                                </a>
                            </div>
                             @if (Route::has('password.request'))
                                <div class="row mt-2">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">
                                        Lupa Password?
                                    </a>
                                </div>
                            @endif
                            <div class="form-group">
                                <button class="btn btn-success float-end text-white" type="submit">
                                    Login
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Hide preloader after page load
        $(document).ready(function() {
            $(".preloader").fadeOut();
        });

        // Toggle password visibility with animation
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('mdi-eye');
                icon.classList.add('mdi-eye-off');
            } else {
                input.type = 'password';
                icon.classList.remove('mdi-eye-off');
                icon.classList.add('mdi-eye');
            }
            // Add simple flip animation on icon
            icon.classList.add('animate__animated', 'animate__flipInY');
            setTimeout(() => {
                icon.classList.remove('animate__animated', 'animate__flipInY');
            }, 1000);
        }
    </script>
</body>

</html>