<footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-3 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">{{ $settings->nama_kecamatan ?? 'Nama Kecamatan' }}</span>
            </a>
            <div class="footer-contact pt-3">
                <p>Alamat : {{ $settings->alamat_kecamatan ?? 'Alamat belum tersedia' }}</p>
                <p class="mt-3"><strong>Phone:</strong> <span>{{ $settings->nomor_telepon_kecamatan ?? '-' }}</span></p>
                <p><strong>Email:</strong> <span>{{ $settings->email_kecamatan ?? '-' }}</span></p>
            </div>

          </div>
               <!-- Google Maps -->
        <div class="col-lg-4 col-md-6 gmap_iframe">
            <div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=400&amp;height=200&amp;hl=en&amp;q=kantor camat iv koto&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://sprunkin.com/">Sprunki Phases</a></div><style>.mapouter{position:relative;text-align:right;width:100%;height:200px;}.gmap_canvas {overflow:hidden;background:none!important;width:100%;height:200px;}.gmap_iframe {height:200px!important;}</style></div>
          </div>



          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Hic solutasetp</h4>
            <ul>
              <li><a href="#">Molestiae accusamus iure</a></li>
              <li><a href="#">Excepturi dignissimos</a></li>
              <li><a href="#">Suscipit distinctio</a></li>
              <li><a href="#">Dilecta</a></li>
              <li><a href="#">Sit quas consectetur</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Nobis illum</h4>
            <ul>
              <li><a href="#">Ipsam</a></li>
              <li><a href="#">Laudantium dolorum</a></li>
              <li><a href="#">Dinera</a></li>
              <li><a href="#">Trodelas</a></li>
              <li><a href="#">Flexo</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
            <a href="{{ $settings->social_twitter ?? '#' }}" target="_blank"><i class="bi bi-twitter-x"></i></a>
            <a href="{{ $settings->social_facebook ?? '#' }}" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="{{ $settings->social_instagram ?? '#' }}" target="_blank"><i class="bi bi-instagram"></i></a>
        </div>

      </div>
    </div>

  </footer>
