<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>
    @hasSection('title')
        @yield('title') - Kecamatan IV Koto
    @else
        Kecamatan IV Koto
    @endif
</title>

  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- Favicons -->
  <link rel="icon" href="{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('home/img/favicon.png') }}">



  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


  <!-- Vendor CSS Files -->
  <link href="{{ asset('home/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('home/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('home/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('home/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('home/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- CDN  font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <!-- Main CSS File -->
  <link href="{{ asset('home/css/main.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  @yield('styles')

</head>

<body class="index-page">

    <!-- Header & Navbar -->
    @include('partials.home.header')


  <main class="main">
    @yield('content')
  </main>

  <!-- Footer -->
    @include('partials.home.footer')

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
  </div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('home/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('home/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('home/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('home/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('home/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('home/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('home/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ asset('home/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <!-- Main JS File -->
  <script src="{{ asset('home/js/main.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



      <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>


 @yield('script')

</body>

</html>
