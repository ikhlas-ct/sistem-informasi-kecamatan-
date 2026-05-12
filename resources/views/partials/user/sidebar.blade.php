<!-- Sidebar -->
<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ $settings?->logo ? asset('storage/' . $settings->logo) : asset('user/img/kaiadmin/logo_light.svg') }}"
                alt="navbar brand" class="navbar-brand" height="20" />
            <span class="ms-2 text-white">{{ $settings->nama_kecamatan ?? 'Nama Kecamatan' }}</span>
        </a>

        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
    <!-- End Logo Header -->
</div>
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">
            {{-- Bagian untuk Camat --}}
            @can('isCamat', Auth::user())
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('camat.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Profile -->
                <li class="nav-item">
                    <a href="{{ route('pegawai.profil') }}">
                        <i class="fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Komponen</h4>
                </li>


                     <li class="nav-item">
                    <a href="{{ route('kecamatan.setting.edit') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Website Setting</p>
                    </a>
                             <a href="{{ route('camat.settings.heroslide') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Hero Slide</p>
                    </a>
                </li>



                    <li class="nav-item">
                    <a href="{{ route('camat.nagari.index') }}">
                        <i class="fas fa-home"></i>
                        <p>Nagari</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#masyarakat">
                        <i class="fas fa-newspaper"></i>
                        <p>Kependudukan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="masyarakat">
                        <ul class="nav nav-collapse">
                            <li class="nav-item">
                                <a href="{{ route('camat.masyarakat.index') }}">
                                    <i class="fas fa-cog"></i>
                                    <p>Penduduk</p>

                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <!-- Konten -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-newspaper"></i>
                        <p>Konten</p>
                        @if (!empty($pendingKontenCombined))
                            <span class="badge badge-secondary">{{ $pendingKontenCombined }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('kategori.index') }}">
                                    <span class="sub-item">Kategori</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'berita') }}">
                                    <span class="sub-item">Berita</span>
                                    @if (!empty($pendingKontenByJenis['berita']))
                                        <span class="badge badge-secondary">{{ $pendingKontenByJenis['berita'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'artikel') }}">
                                    <span class="sub-item">Artikel</span>
                                    @if (!empty($pendingKontenByJenis['artikel']))
                                        <span class="badge badge-secondary">{{ $pendingKontenByJenis['artikel'] }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Potensi Daerah -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-chart-area"></i>
                        <p>Potensi Daerah</p>
                        @if (!empty($pendingPotensi))
                            <span class="badge badge-secondary">{{ $pendingPotensi }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('konten.index', 'seni_tari') }}">
                                    <span class="sub-item">Seni Tari</span>
                                    @if (!empty($pendingKontenByJenis['seni_tari']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['seni_tari'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'makanan_daerah') }}">
                                    <span class="sub-item">Makanan Daerah</span>
                                    @if (!empty($pendingKontenByJenis['makanan_daerah']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['makanan_daerah'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'kerajinan_daerah') }}">
                                    <span class="sub-item">Kerajinan Daerah</span>
                                    @if (!empty($pendingKontenByJenis['kerajinan_daerah']))
                                        {{-- BUGFIX: sebelumnya salah tulis ['berita'] --}}
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['kerajinan_daerah'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_musik') }}">
                                    <span class="sub-item">Seni Musik</span>
                                    @if (!empty($pendingKontenByJenis['seni_musik']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['seni_musik'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_budaya') }}">
                                    <span class="sub-item">Seni Budaya</span>
                                    @if (!empty($pendingKontenByJenis['seni_budaya']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['seni_budaya'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pariwisata') }}">
                                    <span class="sub-item">Pariwisata</span>
                                    @if (!empty($pendingKontenByJenis['pariwisata']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['pariwisata'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pertanian') }}">
                                    <span class="sub-item">Pertanian</span>
                                    @if (!empty($pendingKontenByJenis['pertanian']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['pertanian'] }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Bagian Surat --}}
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#surat">
                        <i class="fas fa-th-list"></i>
                        <p>Surat</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="surat">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('camat.surat_keterangan_miskin') }}">
                                    <span class="sub-item">Surat Keterangan Tidak Mampu</span>
                                    @if (isset($pendingSurat) && $pendingSurat > 0)
                                        <span class="badge badge-secondary">{{ $pendingSurat }}</span>
                                    @endif
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                {{-- Bagian Pengaduan --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengaduan</h4>
                </li>
                <li class="nav-item">
                    <a href="{{ route('balasanpengaduan.index') }}">
                        <i class="fas fa-cog"></i>
                        <p>Pengaduan</p>
                        @if (isset($pendingPengaduan) && $pendingPengaduan > 0)
                            <span class="badge badge-secondary">{{ $pendingPengaduan }}</span>
                        @endif
                    </a>
                </li>

                {{-- Bagian Data Master --}}
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Data Master</h4>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.index') }}">
                        <i class="fas fa-cog"></i>
                        <p>Pegawai</p>

                    </a>
                </li>

            @endcan

            {{-- Bagian untuk Masyarakat --}}
            @can('isMasyarakat', Auth::user())
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard.masyarakat') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Profil -->
                <li class="nav-item">
                    <a href="{{ route('masyarakat.profil') }}">
                        <i class="fas fa-user"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <!-- Konten -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-newspaper"></i>
                        <p>Konten</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('konten.index', 'berita') }}">
                                    <span class="sub-item">Berita</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'artikel') }}">
                                    <span class="sub-item">Artikel</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Potensi Daerah -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-chart-area"></i>
                        <p>Potensi Daerah</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('konten.index', 'seni_tari') }}">
                                    <span class="sub-item">Seni Tari</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'makanan_daerah') }}">
                                    <span class="sub-item">Makanan Daerah</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'kerajinan_daerah') }}">
                                    <span class="sub-item">Kerajinan Daerah</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_musik') }}">
                                    <span class="sub-item">Seni Musik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_budaya') }}">
                                    <span class="sub-item">Seni Budaya</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pariwisata') }}">
                                    <span class="sub-item">Pariwisata</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pertanian') }}">
                                    <span class="sub-item">Pertanian</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @can('canRequestSuratKeteranganMiskin', auth()->user())
                    <!-- Surat -->
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#surat">
                            <i class="fas fa-envelope"></i>
                            <p>Surat</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="surat">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('masyarakat.surat_keterangan_miskin') }}">
                                        <span class="sub-item">Surat Keterangan Tidak Mampu</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Arsip Surat</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                <!-- Pengaduan -->
                <li class="nav-item">
                    <a href="{{ route('pengaduan.index') }}">
                        <i class="fas fa-comments"></i>
                        <p>Pengaduan</p>
                    </a>
                </li>
            @endcan

            {{-- Bagian untuk Pegawai --}}
            @can('isPegawai', Auth::user())
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('camat.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                  <li class="nav-item">
                    <a href="{{ route('pegawai.profil') }}">
                        <i class="fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <!-- Konten -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-newspaper"></i>
                        <p>Konten</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('konten.index', 'berita') }}">
                                    <span class="sub-item">Berita</span>
                                    @if (isset($pendingKontenByJenis['berita']) && $pendingKontenByJenis['berita'] > 0)
                                        <span class="badge badge-secondary">{{ $pendingKontenByJenis['berita'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'artikel') }}">
                                    <span class="sub-item">Artikel</span>
                                    @if (isset($pendingKontenByJenis['artikel']) && $pendingKontenByJenis['artikel'] > 0)
                                        <span class="badge badge-secondary">{{ $pendingKontenByJenis['artikel'] }}</span>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>



                <!-- Surat -->
                @can('canAccessSuratKeteranganMiskinPegawaiNagari', auth()->user())

                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#surat">
                            <i class="fas fa-envelope"></i>
                            <p>Surat</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="surat">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('pegawai.surat_keterangan_miskin') }}">
                                        <span class="sub-item">Surat Keterangan Tidak Mampu</span>
                                        @if (isset($pendingSurat) && $pendingSurat > 0)
                                            <span class="badge badge-secondary">{{ $pendingSurat }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Arsip Surat</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('canAccessSuratKeteranganMiskinKepalaNagari', auth()->user())

                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#surat">
                            <i class="fas fa-th-list"></i>
                            <p>Surat</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="surat">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('nagari.surat_keterangan_miskin') }}">
                                        <span class="sub-item">Surat Keterangan Tidak Mampu</span>
                                        @if (isset($pendingSurat) && $pendingSurat > 0)
                                            <span class="badge badge-secondary">{{ $pendingSurat }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('nagari.surat_keterangan_miskin_arsip') }}">
                                        <span class="sub-item">Arsip Surat</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                @endcan

                <!-- Potensi Daerah -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-chart-area"></i>
                        <p>Potensi Daerah</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('konten.index', 'seni_tari') }}">
                                    <span class="sub-item">Seni Tari</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'makanan_daerah') }}">
                                    <span class="sub-item">Makanan Daerah</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'kerajinan_daerah') }}">
                                    <span class="sub-item">Kerajinan Daerah</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_musik') }}">
                                    <span class="sub-item">Seni Musik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'seni_budaya') }}">
                                    <span class="sub-item">Seni Budaya</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pariwisata') }}">
                                    <span class="sub-item">Pariwisata</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('konten.index', 'pertanian') }}">
                                    <span class="sub-item">Pertanian</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Pengaduan -->
                <li class="nav-item">
                    <a href="{{ route('balasanpengaduan.index') }}">
                        <i class="fas fa-comments"></i>
                        <p>Pengaduan</p>
                        @if (isset($pendingPengaduan) && $pendingPengaduan > 0)
                            <span class="badge badge-secondary">{{ $pendingPengaduan }}</span>
                        @endif
                    </a>
                </li>
            @endcan
            <li class="nav-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</div>
