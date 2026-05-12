@extends('layouts.home.app')
@section('title', $blog->title)
@section('content')

@section('styles')

<style>
.article .content img {
    max-width: 100%;
    height: auto;
}

.d-none {
    display: none !important;
}

/* Warna latar belakang abu-abu untuk komentar */
.comment {
    background-color: #f5f5f5;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    position: relative;
}

.reaksi-btn {
  font-size: 2rem; /* Ukuran ikon besar */
  background-color: #f8f9fa; /* Warna default */
  border: none;
  margin: 0 5px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s;
}

.reaksi-btn:hover {
  background-color: #e2e6ea;
}

.reaksi-btn.aktif {
  background-color: #fd550d; /* Warna aktif */
  color: white;
}
.centered-text {
    color: #000; /* Warna hitam */
    text-align: center; /* Posisikan di tengah */
    font-size: 1.5rem; /* Ukuran font */
    font-weight: bold; /* Tebalkan tulisan */
    margin: 20px 0; /* Jarak atas dan bawah */
}

</style>
@endsection



  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Blog</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="current">{{ $jenis_konten }}</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-8">

          <!-- Blog Details Section -->
          <section id="blog-details" class="blog-details section">
            <div class="container">

              <article class="berita">
                <h2 class="title">{{ $blog->judul }}</h2>

                <div class="meta-top">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-person"></i> {{ $blog->user->pegawai->nama_pegawai ?? $blog->user->masyarakat->nama_masyarakat }}</li>
                    <li><i class="bi bi-clock"></i>
                        <time datetime="{{ $blog->tanggal_publikasi }}">
                            {{ $blog->tanggal_publikasi->format('d M Y') }}
                        </time>
                    </li>
                    <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> {{ $komentars->count() }} Komentar  </li>

                  </ul>
                </div><!-- End meta top -->


                <div class="content">
                    {!! $blog->isi !!}

                </div><!-- End post content -->

                <div class="meta-bottom">
                    <ul class="cats">
                        @foreach ($blog->kategori as $kategori)
                            <li>
                                <i class="{{ $kategori->icon }}"></i>
                                <a href="#">{{ $kategori->nama_kategori }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <hr>


              </article>

            </div>
          </section><!-- /Blog Details Section -->

          <!-- Blog Author Section -->
          <section id="blog-author" class="blog-author section">

            <div class="container">
              <div class="author-container d-flex align-items-center">
                @if(optional($blog->user->pegawai)->foto_profil)
                <img src="{{ asset('storage/' . $blog->user->pegawai->foto_profil) }}"
                     class="rounded-circle flex-shrink-0"
                     style="width: 100px; height: 100px; object-fit: cover;"
                     alt="Foto Profil Pegawai">
            @elseif(optional($blog->user->masyarakat)->foto_profil)
                <img src="{{ asset('storage/' . $blog->user->masyarakat->foto_profil) }}"
                     class="rounded-circle flex-shrink-0"
                     style="width: 100px; height: 100px; object-fit: cover;"
                     alt="Foto Profil Masyarakat">
            @else
                <img src="{{asset('default-image/default-user.png') }}"
                     class="rounded-circle flex-shrink-0"
                     style="width: 100px; height: 100px; object-fit: cover;"
                     alt="Foto Profil Default">
            @endif

                <div>
                    <h4>{{ optional($blog->user->pegawai)->nama_pegawai ?? optional($blog->user->masyarakat)->nama_masyarakat ?? 'tidak ada nama' }}</h4>
                    <div class="social-links">
                        <a href="{{ optional($blog->user->pegawai)->twitter ?? optional($blog->user->masyarakat)->twitter ?? '#' }}"><i class="bi bi-twitter-x"></i></a>
                        <a href="{{ optional($blog->user->pegawai)->facebook ?? optional($blog->user->masyarakat)->facebook ?? '#' }}"><i class="bi bi-facebook"></i></a>
                        <a href="{{ optional($blog->user->pegawai)->instagram ?? optional($blog->user->masyarakat)->instagram ?? '#' }}"><i class="biu bi-instagram"></i></a>
                    </div>
                    <p>
                        {{ optional($blog->user->pegawai)->deskripsi ?? optional($blog->user->masyarakat)->deskripsi ?? '#' }}.
                    </p>
                </div>
              </div>
            </div>

          </section><!-- /Blog Author Section -->

          <section>
            <h2 class="centered-text">Bagaimana tanggapanmu terhadap berita ini</h2>
            <div class="container my-4">
                <div class="row justify-content-center">
                    {{-- Baris 1: 3 ikon --}}

                    <div class="col-auto">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'suka' ? 'aktif' : '' }}"
                                    data-jenis="suka" data-konten="{{ $blog->id_konten }}">
                                👍
                            </button>
                            <small class="mt-1 d-block text-center count-suka">{{ $reaksiCounts['suka'] ?? 0 }}</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn reaksi-btn {{ (isset($userReaksi) && $userReaksi->jenis === 'marah') ? 'aktif' : '' }}" data-jenis="marah" data-konten="{{ $blog->id_konten }}">
                                😡
                            </button>
                            <small class="mt-1 d-block text-center count-marah">{{ $reaksiCounts['marah'] ?? 0 }}</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn reaksi-btn {{ (isset($userReaksi) && $userReaksi->jenis === 'sedih') ? 'aktif' : '' }}" data-jenis="sedih" data-konten="{{ $blog->id_konten }}">
                                😢
                            </button>
                            <small class="mt-1 d-block text-center count-sedih">{{ $reaksiCounts['sedih'] ?? 0 }}</small>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mt-3">
                    {{-- Baris 2: 2 ikon --}}
                    <div class="col-auto">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn reaksi-btn {{ (isset($userReaksi) && $userReaksi->jenis === 'senang') ? 'aktif' : '' }}" data-jenis="senang" data-konten="{{ $blog->id_konten }}">
                                😊
                            </button>
                            <small class="mt-1 d-block text-center count-senang">{{ $reaksiCounts['senang'] ?? 0 }}</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" class="btn reaksi-btn {{ (isset($userReaksi) && $userReaksi->jenis === 'lucu') ? 'aktif' : '' }}" data-jenis="lucu" data-konten="{{ $blog->id_konten }}">
                                😂
                            </button>
                            <small class="mt-1 d-block text-center count-lucu">{{ $reaksiCounts['lucu'] ?? 0 }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>


<!-- Komentar Section -->
        <section id="blog-comments" class="blog-comments section">
            <div class="container">
              <h4 class="comments-count">{{ $komentars->count() }} Komentar</h4>

              @foreach ($komentars as $komentar)
                <div class="comment" id="comment-{{ $komentar->id_komentar }}">
                  <div class="d-flex">
                    <div class="comment-img me-3">
                      <img src="{{ $komentar->avatar_url }}"
                           class="rounded-circle"
                           style="width:50px; height:50px; object-fit:cover"
                           alt="Avatar {{ $komentar->nama ?? 'Pengguna' }}">
                    </div>
                    <div>
                      <h5>
                        {{ $komentar->nama
                          ?: ($komentar->user->masyarakat->nama_masyarakat
                             ?? $komentar->user->pegawai->nama_pegawai
                             ?? 'Pengguna Tidak Diketahui')
                        }}

                        {{-- … dropdown aksi … --}}
                        @if (
                            auth()->check() &&
                            (
                              in_array(auth()->user()->role, ['pegawai','camat']) ||
                              auth()->id() === $komentar->id_user
                            ) ||
                            (!auth()->check() && is_null($komentar->id_user) && $komentar->ip_address === request()->ip())
                          )
                            <div class="dropdown-center d-inline ms-2">
                              <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                              </button>
                              <ul class="dropdown-menu">
                                {{-- Balas --}}
                                @if (auth()->check() && in_array(auth()->user()->role, ['pegawai','camat','masyarakat']))
                                  <li>
                                    <a href="#" class="dropdown-item reply-link" data-id="{{ $komentar->id_komentar }}">
                                      <i class="bi bi-reply-fill"></i> Balas
                                    </a>
                                  </li>
                                @endif

                                {{-- Edit --}}
                                @if (auth()->check() && auth()->id() === $komentar->id_user)
                                  <li>
                                    <a href="#" class="dropdown-item edit-link" data-id="{{ $komentar->id_komentar }}">
                                      <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                  </li>
                                @endif

                                {{-- Hapus --}}
                                @if ((auth()->check() && (auth()->id() === $komentar->id_user || in_array(auth()->user()->role, ['pegawai','camat']))) || (!auth()->check() && is_null($komentar->id_user) && $komentar->ip_address === request()->ip()))
                                  <li>
                                    <form action="{{ route('komentar.destroy', $komentar->id_komentar) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                      </button>
                                    </form>
                                  </li>
                                @endif
                              </ul>
                            </div>
                          @endif


                      </h5>

                      <time datetime="{{ $komentar->created_at }}">
                        {{ $komentar->created_at->format('d M, Y') }}
                      </time>
                      <p>{{ $komentar->isi_komentar }}</p>

                      {{-- Form edit di sini (pastikan hanya satu, tidak duplikat) --}}
                      <form action="{{ route('komentar.update', $komentar->id_komentar) }}"
                            method="POST"
                            class="edit-form d-none"
                            id="edit-form-{{ $komentar->id_komentar }}">
                        @csrf @method('PATCH')
                        <textarea name="isi" class="form-control mb-2" required>
                          {{ $komentar->isi_komentar }}
                        </textarea>
                        <button class="btn btn-success btn-sm">Update</button>
                        <button type="button"
                                class="btn btn-secondary btn-sm cancel-edit"
                                data-id="{{ $komentar->id_komentar }}">
                          Cancel
                        </button>
                      </form>
                    </div>
                  </div>

                  {{-- Balasan --}}
                  @if ($komentar->balasan->count())
                    <div class="ms-4">
                      @foreach ($komentar->balasan as $balasan)
                        <div class="comment comment-reply d-flex mt-2" id="comment-{{ $balasan->id_komentar }}">
                          <div class="comment-img me-3">
                            <img src="{{ $balasan->avatar_url }}"
                                 class="rounded-circle"
                                 style="width:40px; height:40px; object-fit:cover"
                                 alt="Avatar {{ $balasan->nama ?? 'Pengguna' }}">
                          </div>
                          <div>
                            <h6>
                              {{ $balasan->nama
                                ?: ($balasan->user->masyarakat->nama_masyarakat
                                   ?? $balasan->user->pegawai->nama_pegawai
                                   ?? 'Pengguna Tidak Diketahui')
                              }}
                              {{-- … dropdown aksi … --}}
                            </h6>
                            <time datetime="{{ $balasan->created_at }}">
                              {{ $balasan->created_at->format('d M, Y') }}
                            </time>
                            <p>{{ $balasan->isi_komentar }}</p>
                            {{-- Form edit balasan (gunakan id unik) --}}
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          </section>



  <!-- Reply Form (hanya untuk pegawai, camat, atau masyarakat) -->
  @if (auth()->check() && in_array(auth()->user()->role, ['pegawai','camat','masyarakat']))
    <div id="reply-form" class="d-none">
      <form action="{{ route('balasan.store', $blog->id_konten) }}" method="POST">
        @csrf
        <!-- Field hidden untuk menyimpan ID komentar induk yang akan dibalas -->
        <input type="hidden" name="parent_id" id="reply_parent_id" value="">
        <div class="form-group">
          <textarea name="isi" class="form-control mb-2" placeholder="Your Reply*" required></textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary btn-sm me-2">Post Reply</button>
          <button type="button" id="cancel-reply" class="btn btn-secondary btn-sm">Cancel</button>
        </div>
      </form>
    </div>
  @endif

  <!-- Comment Form Section (untuk komentar utama, bagi user yang tidak login atau bukan pegawai/camat) -->
  <section id="comment-form" class="comment-form section">
    <div class="container">
      <form action="{{ route('komentar.store', $blog->id_konten) }}" method="POST">
        @csrf
        <h4>Post Comment</h4>
        <p>Email tidak akan dipublikasikan. Field bertanda * wajib diisi.</p>
        @if (!auth()->check())
          <div class="row">
            <div class="col-md-6 form-group">
              <input name="nama" type="text" class="form-control" placeholder="Your Name*" required>
            </div>
            <div class="col-md-6 form-group">
              <input name="email" type="email" class="form-control" placeholder="Your Email*" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 form-group">
              <input name="no_hp" type="text" class="form-control" placeholder="Your Phone Number">
            </div>
          </div>
        @endif
        <div class="form-group">
          <textarea name="isi" class="form-control" placeholder="Your Comment*" required></textarea>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Post Comment</button>
        </div>
      </form>
    </div>
  </section>






        </div>

        <div class="col-lg-4 sidebar">

         @include('partials.konten.sidebarleft')

        </div>

      </div>
    </div>

  </main>



</body>


@endsection

@section('script')

  <!-- Optional: JavaScript untuk menangani klik tombol "Balas" -->
  <script>
document.querySelectorAll('.reply-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var parentId = this.getAttribute('data-id');
        var replyForm = document.getElementById('reply-form');

        // Pindahkan form balasan langsung ke bawah komentar utama
        var commentElement = this.closest('.comment');
        commentElement.parentNode.insertBefore(replyForm, commentElement.nextSibling); // Tambahkan form tepat setelah komentar utama

        // Set parent_id di form
        document.querySelector('input[name="parent_id"]').value = parentId;

        // Tampilkan form balasan
        replyForm.classList.remove('d-none');
    });
});

// JavaScript untuk menyembunyikan form balasan
document.getElementById('cancel-reply').addEventListener('click', function() {
    var replyForm = document.getElementById('reply-form');
    replyForm.classList.add('d-none');
    document.querySelector('input[name="parent_id"]').value = ''; // Reset parent_id
});

// JavaScript untuk menangani klik tombol "Edit"
document.querySelectorAll('.edit-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('Edit button clicked'); // Debugging log
        var commentId = this.getAttribute('data-id');
        console.log('Comment ID:', commentId); // Debugging log
        var commentText = document.getElementById('comment-text-' + commentId);
        var editForm = document.getElementById('edit-form-' + commentId);

        if (commentText && editForm) {
            commentText.classList.add('d-none'); // Sembunyikan teks komentar
            editForm.classList.remove('d-none'); // Tampilkan form edit
        } else {
            console.error('Element not found for comment ID:', commentId);
        }
    });
});

// JavaScript untuk menangani klik tombol "Cancel"
document.querySelectorAll('.cancel-edit').forEach(function(button) {
    button.addEventListener('click', function() {
        var commentId = this.getAttribute('data-id');
        var commentText = document.getElementById('comment-text-' + commentId);
        var editForm = document.getElementById('edit-form-' + commentId);

        if (commentText && editForm) {
            commentText.classList.remove('d-none'); // Tampilkan teks komentar
            editForm.classList.add('d-none'); // Sembunyikan form edit
        } else {
            console.error('Element not found for comment ID:', commentId);
        }
    });
});





</script>


<script>
   $(document).ready(function() {
    $('.reaksi-btn').click(function() {
        const jenis = $(this).data('jenis');
        const id_konten = $(this).data('konten');
        const button = $(this);

        $.ajax({
            url: '/reaksi/' + id_konten,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                jenis: jenis
            },
            success: function(response) {
                // 1. Reset semua tombol reaksi
                $('.reaksi-btn').removeClass('aktif');

                // 2. Aktifkan tombol yang diklik jika ada reaksi aktif
                if (response.user_reaksi) {
                    $(`.reaksi-btn[data-jenis="${response.user_reaksi}"]`).addClass('aktif');
                }

                // 3. Update semua count reaksi (termasuk yang jadi 0)
                const allReaksiTypes = ['suka', 'marah', 'sedih', 'senang', 'lucu'];
                allReaksiTypes.forEach(function(jenis) {
                    $('.count-' + jenis).text(response.counts[jenis] || 0);
                });
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('Gagal menyimpan reaksi');
            }
        });
    });
});
</script>



@endsection
