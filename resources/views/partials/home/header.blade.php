<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center light-background">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">

          <i class="bi bi-envelope d-flex align-items-center"><a href="{{ $settings->email_kecamatan ?? '#' }}"></a>{{ $settings->email_kecamatan ?? 'Email tidak tersedia' }}</i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>{{ $settings->nomor_telepon_kecamatan ?? 'Nomor tidak tersedia' }}</span></i>
        </div>

        <div class="social-links d-none d-md-flex align-items-center">
          <a href="{{ $settings->social_twitter ?? '#' }}" target="_blank"><i class="bi bi-twitter-x"></i></a>
          <a href="{{ $settings->social_facebook ?? '#' }}" target="_blank"><i class="bi bi-facebook"></i></a>
          <a href="{{ $settings->social_instagram ?? '#' }}" target="_blank"><i class="bi bi-instagram"></i></a>

        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

      <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="{{ route('home') }}" class="logo d-flex align-items-center">
          <img src="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}" alt="">
          <h1 class="sitename">{{ $settings->nama_kecamatan }}</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{ route('home') }}" class="active">Beranda<br></a></li>
            <li class="dropdown"><a><span>Profil</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="{{ route('home.visi_misi') }}">Visi Dan Misi</a></li>
                  <li><a href="{{ route('home.struktur_organisasi') }}">Struktur Organisasi</a></li>
                  <li><a href="{{route('home.tugas_fungsi')}}">Tugas Dan Fungsi</a></li>
                  <li><a href="{{ route('home.sejarah') }}">Sejarah</a></li>
                  <li><a href="{{ route('home.geografis') }}">Geografis</a></li>


                </ul>
              </li>

              <li class="dropdown"><a><span>Galeri</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>

                    <li><a href="{{ route('home.galeri_foto')}}">Album Foto</a></li>
                    <li><a href="{{ route('home.koleksi_video') }}">Koleksi Vidio</a></li>


                </ul>
              <li class="dropdown"><a><span>Konten</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                </li>
                <li><a href="{{ route('home.konten', ['jenis' => 'berita']) }}">Berita</a></li>
                <li><a href="{{ route('home.konten', ['jenis' => 'artikel']) }}">Artikel</a></li>


                </ul>
              </li>

              <li class="dropdown"><a><span>Potensi</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                        <li><a href="{{ route('home.konten', ['jenis' => 'seni_tari']) }}">Seni Tari</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'makanan_daerah']) }}">Makanan Daerah</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'kerajinan_daerah']) }}">Kerajinan Daerah</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'seni_musik']) }}">Seni Musik</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'seni_budaya']) }}">Seni Budaya</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'pariwisata']) }}">Pariwisata</a></li>
                        <li><a href="{{ route('home.konten', ['jenis' => 'pertanian']) }}">Pertanian</a></li>






                </ul>
              </li>



            <li><a href="{{ route('home.contact') }}">Contact</a></li>

            <li class="dropdown">
                @if(Auth::check())
                    <a href="#">Halo, {{ Auth::user()->name }} <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        @if(Auth::user()->role == 'masyarakat')
                            <li><a href="{{ route('dashboard.masyarakat') }}">Dashboard Masyarakat</a></li>
                        @elseif(Auth::user()->role == 'pegawai')
                            <li><a href="{{ route('pegawai.dashboard') }}">Dashboard Pegawai</a></li>
                        @elseif(Auth::user()->role == 'camat')
                            <li><a href="{{ route('camat.dashboard') }}">Dashboard Camat</a></li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>



                @endif
            </li>

          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>

    </div>

  </header>
