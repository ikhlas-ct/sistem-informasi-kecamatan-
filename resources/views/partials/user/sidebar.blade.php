<div class="sidebar-logo">
    <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ $settings?->logo ? asset('storage/' . $settings->logo) : asset('user/img/kaiadmin/logo_light.svg') }}"
                alt="navbar brand" class="navbar-brand" height="20" />
            <span class="ms-2 text-white">{{ $settings->nama_kecamatan ?? 'Nama Kecamatan' }}</span>
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>
</div>

<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">

            @can('isCamat', Auth::user())

                <li class="nav-item">
                    <a href="{{ route('camat.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pegawai.profil') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Komponen</h4>
                </li>

                <li class="nav-item">
                    <a href="{{ route('kecamatan.setting.edit') }}">
                        <i class="fas fa-sliders-h"></i>
                        <p>Website Setting</p>
                    </a>
                    <a href="{{ route('camat.settings.heroslide') }}">
                        <i class="fas fa-images"></i>
                        <p>Hero Slide</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('camat.nagari.index') }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Nagari</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Pegawai</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('camat.masyarakat.index') }}">
                        <i class="fas fa-users"></i>
                        <p>Kependudukan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-file-alt"></i>
                        <p>Konten</p>
                        @if (!empty($pendingKontenCombined))
                            <span class="badge badge-secondary">{{ $pendingKontenCombined }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('kategori.index') }}"><span class="sub-item">Kategori</span></a></li>
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

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sekolahMenu">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Sekolah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="sekolahMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('sekolah.index') }}"><span class="sub-item">Sekolah</span></a></li>
                            <li><a href="{{ route('siswa.index') }}"><span class="sub-item">Siswa</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-map"></i>
                        <p>Potensi Daerah</p>
                        @if (!empty($pendingPotensi))
                            <span class="badge badge-secondary">{{ $pendingPotensi }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'seni_tari') }}"><span class="sub-item">Seni Tari</span>
                                    @if (!empty($pendingKontenByJenis['seni_tari']))
                                        <span class="badge badge-secondary">{{ $pendingKontenByJenis['seni_tari'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'makanan_daerah') }}"><span class="sub-item">Makanan
                                        Daerah</span>
                                    @if (!empty($pendingKontenByJenis['makanan_daerah']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['makanan_daerah'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'kerajinan_daerah') }}"><span class="sub-item">Kerajinan
                                        Daerah</span>
                                    @if (!empty($pendingKontenByJenis['kerajinan_daerah']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['kerajinan_daerah'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'seni_musik') }}"><span class="sub-item">Seni
                                        Musik</span>
                                    @if (!empty($pendingKontenByJenis['seni_musik']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['seni_musik'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'seni_budaya') }}"><span class="sub-item">Seni
                                        Budaya</span>
                                    @if (!empty($pendingKontenByJenis['seni_budaya']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['seni_budaya'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'pariwisata') }}"><span
                                        class="sub-item">Pariwisata</span>
                                    @if (!empty($pendingKontenByJenis['pariwisata']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['pariwisata'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><a href="{{ route('konten.index', 'pertanian') }}"><span
                                        class="sub-item">Pertanian</span>
                                    @if (!empty($pendingKontenByJenis['pertanian']))
                                        <span
                                            class="badge badge-secondary">{{ $pendingKontenByJenis['pertanian'] }}</span>
                                    @endif
                                </a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Dokumen</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dokumenBersama">
                        <i class="fas fa-folder-open"></i>
                        <p>Dokumen Bersama</p>
                        @php
                            $belumBacaCamat = \App\Models\DokumenPenerima::where('id_user', auth()->id())
                                ->where('sudah_dibaca', false)
                                ->count();
                        @endphp
                        @if ($belumBacaCamat > 0)
                            <span class="badge badge-secondary">{{ $belumBacaCamat }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('dokumen.*') ? 'show' : '' }} collapse" id="dokumenBersama">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('dokumen.index') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.index') }}">
                                    <span class="sub-item"><i class="fas fa-inbox me-1"></i> Kotak Masuk</span>
                                    @if ($belumBacaCamat > 0)
                                        <span class="badge badge-secondary">{{ $belumBacaCamat }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('dokumen.terkirim') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.terkirim') }}">
                                    <span class="sub-item"><i class="fas fa-paper-plane me-1"></i> Terkirim</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dokumen.create') }}">
                                    <span class="sub-item"><i class="fas fa-plus me-1"></i> Kirim Dokumen</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Pengaduan</h4>
                </li>
                <li class="nav-item">
                    <a href="{{ route('balasanpengaduan.index') }}">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>Pengaduan</p>
                        @if (isset($pendingPengaduan) && $pendingPengaduan > 0)
                            <span class="badge badge-secondary">{{ $pendingPengaduan }}</span>
                        @endif
                    </a>
                </li>

            @endcan

            @if (Auth::user()->isMasyarakatBiasa())

                <li class="nav-item">
                    <a href="{{ route('dashboard.masyarakat') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('masyarakat.profil') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-file-alt"></i>
                        <p>Konten</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'berita') }}"><span
                                        class="sub-item">Berita</span></a></li>
                            <li><a href="{{ route('konten.index', 'artikel') }}"><span
                                        class="sub-item">Artikel</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-map"></i>
                        <p>Potensi Daerah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'seni_tari') }}"><span class="sub-item">Seni
                                        Tari</span></a></li>
                            <li><a href="{{ route('konten.index', 'makanan_daerah') }}"><span
                                        class="sub-item">Makanan Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'kerajinan_daerah') }}"><span
                                        class="sub-item">Kerajinan Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_musik') }}"><span class="sub-item">Seni
                                        Musik</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_budaya') }}"><span class="sub-item">Seni
                                        Budaya</span></a></li>
                            <li><a href="{{ route('konten.index', 'pariwisata') }}"><span
                                        class="sub-item">Pariwisata</span></a></li>
                            <li><a href="{{ route('konten.index', 'pertanian') }}"><span
                                        class="sub-item">Pertanian</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dokumenBersama">
                        <i class="fas fa-folder-open"></i>
                        <p>Dokumen Bersama</p>
                        @php
                            $belumBacaMasy = \App\Models\DokumenPenerima::where('id_user', auth()->id())
                                ->where('sudah_dibaca', false)
                                ->count();
                        @endphp
                        @if ($belumBacaMasy > 0)
                            <span class="badge badge-secondary">{{ $belumBacaMasy }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('dokumen.*') ? 'show' : '' }} collapse" id="dokumenBersama">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('dokumen.index') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.index') }}">
                                    <span class="sub-item"><i class="fas fa-inbox me-1"></i> Kotak Masuk</span>
                                    @if ($belumBacaMasy > 0)
                                        <span class="badge badge-secondary">{{ $belumBacaMasy }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('dokumen.terkirim') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.terkirim') }}">
                                    <span class="sub-item"><i class="fas fa-paper-plane me-1"></i> Terkirim</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dokumen.create') }}">
                                    <span class="sub-item"><i class="fas fa-plus me-1"></i> Kirim Dokumen</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pengaduan.index') }}">
                        <i class="fas fa-comment-dots"></i>
                        <p>Pengaduan</p>
                    </a>
                </li>

            @endif

            @can('isPegawai', Auth::user())

                <li class="nav-item">
                    <a href="{{ route('camat.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pegawai.profil') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Profile</p>
                    </a>
                </li>
                @canany(['isWaliNagari', 'isStafCamat'], Auth::user())

                <li class="nav-item">
                    <a href="{{ route('pegawai.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <p>Pegawai</p>
                    </a>
                </li>
                @endcanany
                <li class="nav-item">
                    <a href="{{ route('camat.masyarakat.index') }}">
                        <i class="fas fa-address-book"></i>
                        <p>Kependudukan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-file-alt"></i>
                        <p>Konten</p><span class="caret"></span>
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

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#Potensi">
                        <i class="fas fa-map"></i>
                        <p>Potensi Daerah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="Potensi">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'seni_tari') }}"><span class="sub-item">Seni
                                        Tari</span></a></li>
                            <li><a href="{{ route('konten.index', 'makanan_daerah') }}"><span class="sub-item">Makanan
                                        Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'kerajinan_daerah') }}"><span
                                        class="sub-item">Kerajinan Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_musik') }}"><span class="sub-item">Seni
                                        Musik</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_budaya') }}"><span class="sub-item">Seni
                                        Budaya</span></a></li>
                            <li><a href="{{ route('konten.index', 'pariwisata') }}"><span
                                        class="sub-item">Pariwisata</span></a></li>
                            <li><a href="{{ route('konten.index', 'pertanian') }}"><span
                                        class="sub-item">Pertanian</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sekolahMenu">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Sekolah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="sekolahMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('sekolah.index') }}"><span class="sub-item">Daftar Sekolah</span></a>
                            </li>
                            <li><a href="{{ route('sekolah.create') }}"><span class="sub-item">Tambah Sekolah</span></a>
                            </li>
                            <li><a href="{{ route('siswa.index') }}"><span class="sub-item">Data Siswa</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dokumenBersama">
                        <i class="fas fa-folder-open"></i>
                        <p>Dokumen Bersama</p>
                        @php
                            $belumBacaPeg = \App\Models\DokumenPenerima::where('id_user', auth()->id())
                                ->where('sudah_dibaca', false)
                                ->count();
                        @endphp
                        @if ($belumBacaPeg > 0)
                            <span class="badge badge-secondary">{{ $belumBacaPeg }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('dokumen.*') ? 'show' : '' }} collapse" id="dokumenBersama">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('dokumen.index') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.index') }}">
                                    <span class="sub-item"><i class="fas fa-inbox me-1"></i> Kotak Masuk</span>
                                    @if ($belumBacaPeg > 0)
                                        <span class="badge badge-secondary">{{ $belumBacaPeg }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('dokumen.terkirim') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.terkirim') }}">
                                    <span class="sub-item"><i class="fas fa-paper-plane me-1"></i> Terkirim</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dokumen.create') }}">
                                    <span class="sub-item"><i class="fas fa-plus me-1"></i> Kirim Dokumen</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

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

            @can('isAdminSekolah', Auth::user())
                @php
                    $belumBacaSek = \App\Models\DokumenPenerima::where('id_user', auth()->id())
                        ->where('sudah_dibaca', false)
                        ->count();
                @endphp

                <li class="nav-item">
                    <a href="{{ route('sekolah.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('masyarakat.profil') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Kelola Sekolah</h4>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sekolahMenu">
                        <i class="fas fa-graduation-cap"></i>
                        <p>Data Sekolah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="sekolahMenu">
                        <ul class="nav nav-collapse">
                            <li>
                                @php $idSekolahAdmin = Auth::user()->dataSekolah?->id_sekolah; @endphp
                                @if ($idSekolahAdmin)
                                    <a href="{{ route('sekolah.show', $idSekolahAdmin) }}"><span class="sub-item">Profil
                                            Sekolah</span></a>
                                @else
                                    <a href="{{ route('sekolah.index') }}"><span class="sub-item">Profil
                                            Sekolah</span></a>
                                @endif
                            </li>
                            <li><a href="{{ route('siswa.index') }}"><span class="sub-item">Data Siswa</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('mading.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#madingMenu">
                        <i class="fas fa-bullhorn"></i>
                        <p>Mading</p>
                        @if (!empty($stats['pending']))
                            <span class="badge badge-secondary">{{ $stats['pending'] }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('mading.*') ? 'show' : '' }} collapse" id="madingMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('mading.index') }}"><span class="sub-item">Semua Mading</span></a>
                            </li>
                            <li><a href="{{ route('mading.create') }}"><span class="sub-item">Tulis Mading</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#dokumenBersama">
                        <i class="fas fa-folder-open"></i>
                        <p>Dokumen Bersama</p>
                        @if ($belumBacaSek > 0)
                            <span class="badge badge-secondary">{{ $belumBacaSek }}</span>
                        @endif
                        <span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('dokumen.*') ? 'show' : '' }} collapse" id="dokumenBersama">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('dokumen.index') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.index') }}">
                                    <span class="sub-item"><i class="fas fa-inbox me-1"></i> Kotak Masuk</span>
                                    @if ($belumBacaSek > 0)
                                        <span class="badge badge-secondary">{{ $belumBacaSek }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('dokumen.terkirim') ? 'active' : '' }}">
                                <a href="{{ route('dokumen.terkirim') }}">
                                    <span class="sub-item"><i class="fas fa-paper-plane me-1"></i> Terkirim</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dokumen.create') }}">
                                    <span class="sub-item"><i class="fas fa-plus me-1"></i> Kirim Dokumen</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Layanan Masyarakat</h4>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-file-alt"></i>
                        <p>Konten</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'berita') }}"><span class="sub-item">Berita</span></a>
                            </li>
                            <li><a href="{{ route('konten.index', 'artikel') }}"><span
                                        class="sub-item">Artikel</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#PotensiSek">
                        <i class="fas fa-map"></i>
                        <p>Potensi Daerah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="PotensiSek">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'seni_tari') }}"><span class="sub-item">Seni
                                        Tari</span></a></li>
                            <li><a href="{{ route('konten.index', 'makanan_daerah') }}"><span class="sub-item">Makanan
                                        Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'kerajinan_daerah') }}"><span
                                        class="sub-item">Kerajinan Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_musik') }}"><span class="sub-item">Seni
                                        Musik</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_budaya') }}"><span class="sub-item">Seni
                                        Budaya</span></a></li>
                            <li><a href="{{ route('konten.index', 'pariwisata') }}"><span
                                        class="sub-item">Pariwisata</span></a></li>
                            <li><a href="{{ route('konten.index', 'pertanian') }}"><span
                                        class="sub-item">Pertanian</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pengaduan.index') }}">
                        <i class="fas fa-comment-dots"></i>
                        <p>Pengaduan</p>
                    </a>
                </li>

            @endcan

            @can('isSiswaSekolah', Auth::user())
                <li class="nav-item">
                    <a href="{{ route('dashboard.masyarakat') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('masyarakat.profil') }}">
                        <i class="fas fa-id-card"></i>
                        <p>Profil</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Mading</h4>
                </li>

                <li class="nav-item {{ request()->routeIs('mading.*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#madingMenu">
                        <i class="fas fa-bullhorn"></i>
                        <p>Mading</p><span class="caret"></span>
                    </a>
                    <div class="{{ request()->routeIs('mading.*') ? 'show' : '' }} collapse" id="madingMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('mading.index') }}"><span class="sub-item">Mading Saya</span></a></li>
                            <li><a href="{{ route('mading.create') }}"><span class="sub-item">Tulis Mading</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Layanan Masyarakat</h4>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#konten">
                        <i class="fas fa-file-alt"></i>
                        <p>Konten</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="konten">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'berita') }}"><span class="sub-item">Berita</span></a>
                            </li>
                            <li><a href="{{ route('konten.index', 'artikel') }}"><span
                                        class="sub-item">Artikel</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#PotensiSiswa">
                        <i class="fas fa-map"></i>
                        <p>Potensi Daerah</p><span class="caret"></span>
                    </a>
                    <div class="collapse" id="PotensiSiswa">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('konten.index', 'seni_tari') }}"><span class="sub-item">Seni
                                        Tari</span></a></li>
                            <li><a href="{{ route('konten.index', 'makanan_daerah') }}"><span class="sub-item">Makanan
                                        Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'kerajinan_daerah') }}"><span
                                        class="sub-item">Kerajinan Daerah</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_musik') }}"><span class="sub-item">Seni
                                        Musik</span></a></li>
                            <li><a href="{{ route('konten.index', 'seni_budaya') }}"><span class="sub-item">Seni
                                        Budaya</span></a></li>
                            <li><a href="{{ route('konten.index', 'pariwisata') }}"><span
                                        class="sub-item">Pariwisata</span></a></li>
                            <li><a href="{{ route('konten.index', 'pertanian') }}"><span
                                        class="sub-item">Pertanian</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pengaduan.index') }}">
                        <i class="fas fa-comment-dots"></i>
                        <p>Pengaduan</p>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</div>
