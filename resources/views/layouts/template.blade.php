<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html, charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SIKOMBAT - {{ $title }}</title>

  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="apple-touch-icon" href="{{ asset('icons/icon512_rounded.png') }}">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="icon" href="{{ asset('img/logo_bps.png') }}" type="image/png">
  <link rel="shortcut icon" href="{{ asset('img/logo_bps.ico') }}" type="image/x-icon">

  <link rel="stylesheet" href="{{ asset('select2/select2.bs4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">

  @stack('css-libs')
</head>

<body class="sidebar-mini layout-footer-fixed layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="content-wrapper">
      @include('layouts.breadcrumb')

      <section class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </section>
    </div>

    @include('layouts.control-sidebar')
  </div>


  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/adminlte.min.js') }}"></script>
  <script src="{{ asset('select2/select2.full.min.js') }}"></script>

  @yield('scripts')

  @include('sweetalert::alert')

</body>

</html>
