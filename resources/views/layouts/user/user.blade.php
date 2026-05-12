<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Kecamatan IV Koto')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />


    <link rel="icon" href="{{ asset('storage/' . ($settings->logo ?? 'defaultimage/default_logo.png')) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <!-- Fonts and Icons -->
    <script src="{{ asset('user/js/plugin/webfont/webfont.min.js') }}"></script>
    <link href="{{ asset('home/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <script>
        WebFont.load({
          google: { families: ["Public Sans:300,400,500,600,700"] },
          custom: {
            families: [
              "Font Awesome 6 Free",
              "simple-line-icons",
            ],
            urls: ["{{ asset('user/css/all.min.css') }}"],
          },
          active: function () {
            sessionStorage.fonts = true;
          },
        });
      </script>


    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/override.css') }}">

    @yield('styles')
</head>
<body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
          @include('partials.user.sidebar')
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
          {{-- Header --}}
          <div class="main-header">
              @include('partials.user.header')
          </div>
          {{-- End Header --}}

          @yield('content')

          {{-- Footer --}}
          <footer class="footer">
              @include('partials.user.footer')
          </footer>
      </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('user/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('user/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('user/js/core/bootstrap.min.js') }}"></script>

    <!-- Plugin Scripts -->
    <script src="{{ asset('user/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('user/js/plugin/jsvectormap/world.js') }}"></script>
    <script src="{{ asset('user/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('user/js/kaiadmin.min.js') }}"></script>

    <!-- Summernote JS (versi lite) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}

    <script src="{{ asset('user/js/plugin/summernote/summernote-lite.min.js') }}"></script>

    @yield('scripts')
</body>
</html>
