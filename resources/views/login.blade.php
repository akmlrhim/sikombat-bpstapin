<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SIKOMBAT - Login</title>

  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="apple-touch-icon" href="{{ asset('icons/icon512_rounded.png') }}">

  <link rel="icon" href="{{ asset('img/logo_bps.png') }}" type="image/png">
  <link rel="shortcut icon" href="{{ asset('img/logo_bps.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet">

  <style>
    body {
      font-family: "Lato", sans-serif;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="col-11 col-sm-10 col-md-8 col-lg-5 col-xl-4">
      <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
        <div class="card-body p-5 bg-white">
          <div class="text-center mb-4">
            <img src="{{ asset('img/logo_bps.webp') }}" alt="Logo" class="mb-3" style="width: 80px; height: auto;"
              loading="lazy" />

            <h2 class="mb-1 font-weight-bold text-primary">LOGIN</h2>
            <p class="text-muted">Silakan masuk untuk melanjutkan</p>
          </div>

          <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="form-group mb-3">
              <label for="email" class="form-label font-weight-bold">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="Masukkan email anda" autofocus
                autocomplete="off">
              @error('email')
                <x-input-validation>{{ $message }}</x-input-validation>
              @enderror
            </div>

            <div class="form-group mb-4">
              <label for="password" class="form-label font-weight-bold">Password</label>
              <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                  name="password" placeholder="Masukkan password" autocomplete="off">

                <button class="btn btn-sm toggle-password btn-outline-primary" type="button">
                  <span class="icon-eye-off d-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" class="lucide lucide-eye">
                      <path d="M2.062 12.348a1 1 0 0 1 0-.696
                           10.75 10.75 0 0 1 19.876 0
                           1 1 0 0 1 0 .696
                           10.75 10.75 0 0 1-19.876 0" />
                      <circle cx="12" cy="12" r="3" />
                    </svg>
                  </span>
                  <span class="icon-eye">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" class="lucide lucide-eye-off">
                      <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575
                           1 1 0 0 1 0 .696
                           10.747 10.747 0 0 1-1.444 2.49" />
                      <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                      <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151
                                 1 1 0 0 1 0-.696
                           10.75 10.75 0 0 1 4.446-5.143" />
                      <path d="m2 2 20 20" />
                    </svg>
                  </span>
                </button>
              </div>
              @error('password')
                <x-input-validation>{{ $message }}</x-input-validation>
              @enderror
            </div>

            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <button type="submit" class="btn btn-primary w-100 shadow-sm">
              Login
            </button>
          </form>
        </div>
      </div>

      <div class="text-center mt-3">
        <p class="text-muted">&copy; {{ date('Y') }} SIKOMBAT</p>
      </div>
    </div>
  </div>

  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toggleBtn = document.querySelector(".toggle-password");
      const passwordInput = document.getElementById("password");
      const eye = toggleBtn.querySelector(".icon-eye-off");
      const eyeOff = toggleBtn.querySelector(".icon-eye");

      toggleBtn.addEventListener("click", function() {
        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");
        eye.classList.toggle("d-none", !isPassword);
        eyeOff.classList.toggle("d-none", isPassword);
      });

      if (!window.grecaptcha) return;

      grecaptcha.ready(function() {
        grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
          action: 'login'
        }).then(function(token) {
          document.getElementById('g-recaptcha-response').value = token;
        });

        document.getElementById('login-form').addEventListener('submit', function(e) {
          e.preventDefault();
          grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
            action: 'login'
          }).then(function(token) {
            document.getElementById('g-recaptcha-response').value = token;
            e.target.submit();
          });
        });
      });
    });
  </script>
</body>

</html>
