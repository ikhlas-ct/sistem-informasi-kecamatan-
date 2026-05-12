@extends('layouts.home.app')
@section('title', 'Galeri Foto')
@section('content')


    <!-- Page Title -->
    <div class="page-title dark-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
          <h1 class="mb-2 mb-lg-0">Kontak</h1>
          <nav class="breadcrumbs">
            <ol>
              <li><a href="{{ route('home') }}">Home</a></li>
              <li class="current">Kontak</li>
            </ol>
          </nav>
        </div>
      </div><!-- End Page Title -->

      <!-- Contact Section -->
      <section id="contact" class="contact section">

        <div class="mb-5">
          <iframe style="width: 100%; height: 400px;" src="https://maps.google.com/maps?width=100%25&amp;height=400&amp;hl=en&amp;q=kantor%20camat%20Kecamatan%20IV%20Koto+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed" frameborder="0" allowfullscreen=""></iframe>
        </div><!-- End Google Maps -->

        <div class="container" data-aos="fade">

          <div class="row gy-5 gx-lg-5">

            <div class="col-lg-4">

              <div class="info">
                <h3> {{ $settings->nama_kecamatan ?? 'nama kecamatan tidak tersedia' }}</h3>
                <p>Jika Anda memiliki pertanyaan atau membutuhkan informasi lebih lanjut tentang layanan kami, jangan ragu untuk menghubungi kami melalui kontak yang tersedia di bawah ini.</p>

                <div class="info-item d-flex">
                  <i class="bi bi-geo-alt flex-shrink-0"></i>
                  <div>
                    <h4>Lokasi:</h4>
                    <p>{{ $settings->alamat_kecamatan ?? 'Alamat tidak ada' }}</p>
                  </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex">
                  <i class="bi bi-envelope flex-shrink-0"></i>
                  <div>
                    <h4>Email:</h4>
                    <p>{{ $settings->email_kecamatan ?? 'Alamat email tidak ada' }}</p>
                  </div>
                </div><!-- End Info Item -->

                <div class="info-item d-flex">
                  <i class="bi bi-phone flex-shrink-0"></i>
                  <div>
                    <h4>No Telp:</h4>
                    <p>{{ $settings->nomor_telepon_kecamatan ?? 'No telp tidak tersedia' }}</p>
                  </div>
                </div><!-- End Info Item -->

              </div>

            </div>

            <div class="col-lg-8">
              <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required="">
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
                  </div>
                </div>
                <div class="form-group mt-3">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required="">
                </div>
                <div class="form-group mt-3">
                  <textarea class="form-control" name="message" placeholder="Message" required=""></textarea>
                </div>
                <div class="my-3">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>
                <div class="text-center"><button type="submit">Send Message</button></div>
              </form>
            </div><!-- End Contact Form -->

          </div>

        </div>

      </section><!-- /Contact Section -->





@endsection
