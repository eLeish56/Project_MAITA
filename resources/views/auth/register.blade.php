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
  <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
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
  <div class="main-wrapper mt-5">
    <div class="pt-5 pb-2"></div>
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center pt-5">
      <div class="auth-box border-top border-secondary bg-dark p-4">
        <div id="registerform">
          <div class="text-center pt-3 pb-3">
            <span class="db">
              <img src="assets/images/icon.png" alt="logo" class="light-logo" />
              <b class="text-white">DAFTAR AKUN CUSTOMER</b>
            </span>
          </div>
          <!-- Form -->
          <form class="form-horizontal mt-3" action="{{ route('register') }}" method="POST">
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
                    placeholder="Nomor Telepon" name="phone" value="{{ old('phone') }}" required />
                </div>

                <!-- Address -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-secondary text-white h-100">
                      <i class="mdi mdi-map-marker fs-4"></i>
                    </span>
                  </div>
                  <textarea class="form-control form-control-lg @error('address') is-invalid @enderror"
                    placeholder="Alamat Lengkap" name="address" required>{{ old('address') }}</textarea>
                </div>

                <!-- Password -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white h-100">
                      <i class="mdi mdi-lock fs-4"></i>
                    </span>
                  </div>
                  <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    placeholder="Password" name="password" required />
                </div>

                <!-- Confirm Password -->
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-warning text-white h-100">
                      <i class="mdi mdi-lock-check fs-4"></i>
                    </span>
                  </div>
                  <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                    placeholder="Konfirmasi Password" name="password_confirmation" required />
                </div>

                <!-- Hidden Role -->
                <input type="hidden" name="role" value="customer">
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                  <a href="{{ route('login') }}" class="text-white">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Login
                  </a>
                  <button type="submit" class="btn btn-success text-white">
                    <i class="mdi mdi-account-plus"></i> Daftar
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
    $(".preloader").fadeOut();
  </script>
</body>

</html>
