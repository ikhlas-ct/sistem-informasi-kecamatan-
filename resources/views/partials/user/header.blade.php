<div class="main-header-logo">
  <div class="logo-header" data-background-color="dark">
    <a href="index.html" class="logo">
      <img src="{{ asset('user/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand" height="20" />
    </a>
    <div class="nav-toggle">
      <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
      <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
    </div>
    <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
  </div>
</div>
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">

    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

      @php
      $user = auth()->user();
      $unreadNotifs = $user->unreadNotifications()->take(5)->get();
      $readNotifs = $user->readNotifications()->take(5)->get();
  @endphp

  <li class="nav-item topbar-icon dropdown hidden-caret">
      <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
          <i class="fa fa-bell"></i>
          @if($unreadNotifs->count())
              <span class="notification">{{ $unreadNotifs->count() }}</span>
          @endif
      </a>
      <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
          <li>
              <div class="dropdown-title">Kamu punya {{ $unreadNotifs->count() }} notifikasi belum dibaca</div>
          </li>
          <li>
              <div class="notif-scroll scrollbar-outer">
                  <div class="notif-center">

                      {{-- Notifikasi Belum Dibaca --}}
                      @forelse($unreadNotifs as $notif)
                          <a href="{{ route('notifikasi.baca', $notif->id) }}">
                              <div class="notif-icon" style="background-color: {{ getNotificationColor($notif->data['type'] ?? 'default') }}">
                                <i class="{{ getNotificationIcon($notif->data['type'] ?? 'default') }}" style="color: white;"></i>
                              </div>
                              <div class="notif-content">
                                  <span class="block"><strong>{{ $notif->data['message'] ?? 'Pesan tidak tersedia' }}</strong></span>
                                  @if(isset($notif->data['additional_info']))
                                      <small class="text-muted d-block">{{ $notif->data['additional_info'] }}</small>
                                  @endif
                                  <span class="time">{{ $notif->created_at->diffForHumans() }}</span>
                              </div>
                          </a>
                      @empty
                          <span class="dropdown-item text-muted">Tidak ada notifikasi baru</span>
                      @endforelse

                      {{-- Pemisah --}}
                      @if($readNotifs->count())
                          <hr class="dropdown-divider">
                          <div class="dropdown-title">Sudah dibaca</div>
                      @endif

                      {{-- Notifikasi Sudah Dibaca --}}
                      @foreach($readNotifs as $notif)
                          <a href="{{ $notif->data['url'] ?? '#' }}">
                              <div class="notif-icon" style="background-color: {{ getNotificationColor($notif->data['type'] ?? 'default', true) }}">
                                <i class="{{ getNotificationIcon($notif->data['type'] ?? 'default') }}" style="color: white;"></i>
                            </div>
                              <div class="notif-content">
                                  <span class="block">{{ $notif->data['message'] ?? 'Pesan tidak tersedia' }}</span>
                                  @if(isset($notif->data['additional_info']))
                                      <small class="text-muted d-block">{{ $notif->data['additional_info'] }}</small>
                                  @endif
                                  <span class="time">{{ $notif->created_at->diffForHumans() }}</span>
                              </div>
                          </a>
                      @endforeach

                  </div>
              </div>
          </li>
          <li>
              <a class="see-all text-center" href="{{ route('notifikasi.baca_semua') }}">
                  Tandai semua telah dibaca <i class="fa fa-check"></i>
              </a>
          </li>
          <li>
              <a class="see-all" href="{{ route('notifikasi.index') }}">Lihat semua notifikasi <i class="fa fa-angle-right"></i></a>
          </li>
      </ul>
  </li>




      <li class="nav-item topbar-user dropdown hidden-caret">
        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
          <div class="avatar-sm">
            <img src="{{ Auth::user()->pegawai?->foto_profil ? asset('storage/' . Auth::user()->pegawai->foto_profil) : (Auth::user()->masyarakat?->foto_profil ? asset('storage/' . Auth::user()->masyarakat->foto_profil) : asset('default-image/default-user.png')) }}" alt="image profile" class="avatar-img rounded" />
          </div>

          <span class="profile-username"><span class="op-7">Hi,</span><span class="fw-bold">{{ Auth::user()->pegawai?->nama_pegawai ?? Auth::user()->masyarakat?->nama_masyarakat ?? 'Tidak ada nama' }}</span></span>
        </a>
        <ul class="dropdown-menu dropdown-user animated fadeIn">
          <div class="dropdown-user-scroll scrollbar-outer">
            <li>
              <div class="user-box">
                <div class="avatar-lg">
                  <img src="{{ Auth::user()->pegawai?->foto_profil ? asset('storage/' . Auth::user()->pegawai->foto_profil) : (Auth::user()->masyarakat?->foto_profil ? asset('storage/' . Auth::user()->masyarakat->foto_profil) : asset('default-image/default-user.png')) }}" alt="image profile" class="avatar-img rounded" />
                </div>
                <div class="u-text">
                  <h4>{{ Auth::user()->pegawai?->nama_pegawai ?? Auth::user()->masyarakat?->nama_masyarakat ?? 'Tidak ada nama' }}</h4>
                  <p class="text-muted">{{ Auth::user()->pegawai ? Auth::user()->pegawai->email : (Auth::user()->masyarakat ? Auth::user()->masyarakat->email : 'Tidak ada email') }}</p>
                  <a href="{{ Auth::user()->hasRole('camat') || Auth::user()->hasRole('pegawai') ? route('pegawai.profil') : route('masyarakat.profil') }}" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                </div>
              </div>
            </li>
            <li>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ Auth::user()->hasRole('camat') || Auth::user()->hasRole('pegawai') ? route('camat.dashboard') : route('dashboard.masyarakat') }}">Dashboard</a>              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </li>
          </div>
        </ul>
      </li>


    </ul>
  </div>
</nav>
