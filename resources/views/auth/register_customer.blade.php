<!DOCTYPE html>
<html lang="en" style="min-width: 100vh !important;">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('APP_NAME', 'Teaching factory') }}</title>
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
  <link href="/dist/css/style.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
  <style>
    .auth-wrapper {
      min-height: 100vh;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    .auth-box {
      max-width: 500px;
      width: 90%;
      margin: auto;
      background: #ffffff !important;
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
      border: 1px solid #e9ecef;
    }
    .form-control {
      border-radius: 0 8px 8px 0 !important;
      height: 50px;
      font-size: 14px;
      border: 1px solid #dee2e6;
      background: #ffffff;
      color: #212529;
    }
    .form-control:focus {
      background: #ffffff;
      border-color: #80bdff;
      color: #212529;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .input-group-text {
      border-radius: 8px 0 0 8px !important;
      border: none;
      width: 50px;
      justify-content: center;
    }
    .input-group {
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .input-group:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn {
      height: 45px;
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
      border-radius: 8px;
    }
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .alert {
      border-radius: 8px;
      border: none;
    }
    .title-text {
      color: #2c3e50;
      font-weight: 600;
    }
    textarea.form-control {
      height: auto;
      min-height: 100px;
    }
    .light-logo {
      max-width: 120px;
      margin-bottom: 15px;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }
    .fs-4 {
      font-size: 1.2rem !important;
    }
    ::placeholder {
      color: #6c757d !important;
    }
  </style>
  <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  </script>
</head>

<body>
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
  <div class="main-wrapper">
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
      <div class="auth-box border-top border-primary p-4 animate__animated animate__fadeIn">
        <div id="registerform">
          <div class="text-center pt-3 pb-3">
            <span class="db">
              <img src="assets/images/icon.png" alt="logo" class="light-logo animate__animated animate__pulse animate__infinite" />
              <h4 class="title-text mb-3 animate__animated animate__fadeInUp">DAFTAR AKUN CUSTOMER</h4>
            </span>
          </div>
          <!-- Form -->
          <form class="form-horizontal mt-3" action="{{ route('register.post') }}" method="POST">
            @csrf
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="row">
              <div class="col-12">
                <!-- Name -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-success text-white h-100">
                      <i class="mdi mdi-account fs-4"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                    placeholder="Nama Lengkap" name="name" value="{{ old('name') }}" required autofocus />
                </div>

                <!-- Username -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-info text-white h-100">
                      <i class="mdi mdi-account-card-details fs-4"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror"
                    placeholder="Username" name="username" value="{{ old('username') }}" required />
                </div>

                <!-- Email -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-danger text-white h-100">
                      <i class="mdi mdi-email fs-4"></i>
                    </span>
                  </div>
                  <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                    placeholder="Email" name="email" value="{{ old('email') }}" required />
                </div>

                <!-- Phone -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-primary text-white h-100">
                      <i class="mdi mdi-phone fs-4"></i>
                    </span>
                  </div>
                  <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                    placeholder="Nomor Telepon (opsional)" name="phone" value="{{ old('phone') }}" />
                </div>

                <!-- Address -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-secondary text-white h-100">
                      <i class="mdi mdi-map-marker fs-4"></i>
                    </span>
                  </div>
                  <textarea class="form-control form-control-lg @error('address') is-invalid @enderror"
                    placeholder="Alamat Lengkap (opsional)" name="address">{{ old('address') }}</textarea>
                </div>

                <!-- Password -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white h-100">
                      <i class="mdi mdi-lock fs-4"></i>
                    </span>
                  </div>
                  <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    placeholder="Password" name="password" id="password" required />
                  <div class="input-group-append">
                    <span class="input-group-text h-100" style="border-radius: 0 8px 8px 0; cursor: pointer;" onclick="togglePassword('password', 'passIcon1')">
                      <i class="mdi mdi-eye fs-4" id="passIcon1"></i>
                    </span>
                  </div>
                </div>

                <!-- Confirm Password -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white h-100">
                      <i class="mdi mdi-lock-check fs-4"></i>
                    </span>
                  </div>
                  <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                    placeholder="Konfirmasi Password" name="password_confirmation" id="password_confirmation" required />
                  <div class="input-group-append">
                    <span class="input-group-text h-100" style="border-radius: 0 8px 8px 0; cursor: pointer;" onclick="togglePassword('password_confirmation', 'passIcon2')">
                      <i class="mdi mdi-eye fs-4" id="passIcon2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                  <a href="{{ route('login') }}" class="btn btn-outline-primary btn-hover-text">
                    <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Login
                  </a>
                  <button type="submit" class="btn btn-primary px-4">
                    <i class="mdi mdi-account-plus me-1"></i> Daftar
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      // Fade out preloader
      $(".preloader").fadeOut();
      
      // Add animation to input groups on focus
      $('.input-group').on('focusin', function() {
        $(this).addClass('animate__animated animate__headShake');
        $(this).find('.input-group-text').addClass('animate__animated animate__rubberBand');
      });
      
      $('.input-group').on('focusout', function() {
        $(this).removeClass('animate__animated animate__headShake');
        $(this).find('.input-group-text').removeClass('animate__animated animate__rubberBand');
      });
      
      // Validate password match
      $('input[name="password"], input[name="password_confirmation"]').on('keyup', function() {
        var password = $('input[name="password"]').val();
        var confirm = $('input[name="password_confirmation"]').val();
        
        if (confirm && password !== confirm) {
          $('input[name="password_confirmation"]').addClass('is-invalid');
        } else {
          $('input[name="password_confirmation"]').removeClass('is-invalid');
        }
      });
    });

    // Toggle password visibility
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
      
      // Add animation
      icon.classList.add('animate__animated', 'animate__flipInY');
      setTimeout(() => {
        icon.classList.remove('animate__animated', 'animate__flipInY');
      }, 1000);
    }
  </script>
</body>

</html>
