@extends('layouts.home.app')
@section('title', $mading->judul)
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
/* Komentar */
.comment {
    background-color: #f5f5f5;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    position: relative;
}
/* Reaksi */
.reaksi-btn {
    font-size: 2rem;
    background-color: #f8f9fa;
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
.reaksi-btn:hover { background-color: #e2e6ea; }
.reaksi-btn.aktif  { background-color: #fd550d; color: white; }
.centered-text {
    color: #000;
    text-align: center;
    font-size: 1.3rem;
    font-weight: bold;
    margin: 20px 0;
}
/* Lampiran - Galeri Foto */
.lampiran-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; margin-top: 12px; }
.lampiran-gallery a { display: block; border-radius: 10px; overflow: hidden; border: 2px solid #e2e8f0; aspect-ratio: 4/3; background: #f1f5f9; position: relative; }
.lampiran-gallery a img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.25s; display: block; }
.lampiran-gallery a:hover img { transform: scale(1.07); }
.lampiran-gallery a .overlay { position: absolute; inset: 0; background: rgba(0,0,0,.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity .2s; }
.lampiran-gallery a:hover .overlay { opacity: 1; }
.lampiran-gallery a .overlay i { color: #fff; font-size: 1.6rem; }
/* Grid khusus 1 atau 2 foto */
.lampiran-gallery.cols-1 { grid-template-columns: 1fr; max-width: 480px; }
.lampiran-gallery.cols-2 { grid-template-columns: 1fr 1fr; }
/* Lampiran - File lainnya */
.lampiran-file-list { display: flex; flex-direction: column; gap: 8px; margin-top: 10px; }
.lampiran-file-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: #334155; font-size: 13px; transition: background .15s, border-color .15s; }
.lampiran-file-item:hover { background: #f1f5f9; border-color: #7c3aed; color: #7c3aed; }
.lampiran-file-item .file-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.lampiran-file-item .file-icon.pdf   { background: #fee2e2; color: #dc2626; }
.lampiran-file-item .file-icon.video { background: #f3e8ff; color: #7c3aed; }
.lampiran-file-item .file-icon.other { background: #f1f5f9; color: #64748b; }
.lampiran-file-item .file-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500; }
.lampiran-file-item .file-dl { font-size: .75rem; color: #94a3b8; flex-shrink: 0; }
</style>
@endsection

<main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Mading Digital</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('home.mading') }}">Mading</a></li>
                    <li class="current">{{ Str::limit($mading->judul, 40) }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="container mt-4">
        <div class="row">

            <!-- ── Konten Utama ── -->
            <div class="col-lg-8">

                <!-- ① Detail Mading -->
                <section id="blog-details" class="blog-details section">
                    <div class="container">
                        <article class="berita">

                            <!-- Badge Jenis & Sekolah -->
                            <div class="mb-2">
                                <span class="badge bg-primary me-1">
                                    {{ ucfirst(str_replace('_', ' ', $mading->jenis)) }}
                                </span>
                                @if ($mading->sekolah)
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-building me-1"></i>{{ $mading->sekolah->nama_sekolah }}
                                        @if ($mading->sekolah->jenjang)
                                            &bull; {{ strtoupper($mading->sekolah->jenjang) }}
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <h2 class="title">{{ $mading->judul }}</h2>

                            <!-- Meta -->
                            <div class="meta-top">
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <i class="bi bi-person"></i>&nbsp;
                                        {{ $mading->user->masyarakat->nama_masyarakat
                                           ?? $mading->user->pegawai->nama_pegawai
                                           ?? 'Anonim' }}
                                    </li>
                                    <li>
                                        <i class="bi bi-clock"></i>&nbsp;
                                        <time datetime="{{ $mading->tanggal_publikasi }}">
                                            {{ $mading->tanggal_publikasi?->format('d M Y') ?? '-' }}
                                        </time>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <i class="bi bi-chat-dots"></i>&nbsp;
                                        {{ $komentars->count() }} Komentar
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <i class="bi bi-eye"></i>&nbsp;
                                        {{ $mading->views }} Dilihat
                                    </li>
                                </ul>
                            </div><!-- End meta top -->

                            <!-- Gambar Utama -->
                            @if ($mading->gambar)
                            <div class="post-img my-3">
                                <img src="{{ asset('storage/' . $mading->gambar) }}"
                                     alt="{{ $mading->judul }}"
                                     class="img-fluid rounded w-100"
                                     style="max-height: 420px; object-fit: cover;">
                            </div>
                            @endif

                            <!-- Isi Konten -->
                            <div class="content">
                                {!! $mading->isi !!}
                            </div><!-- End post content -->

                            <!-- Lampiran: Galeri Foto & File -->
                            @php
                                $lampiranFoto  = $mading->lampiran->where('tipe', 'image');
                                $lampiranFiles = $mading->lampiran->whereIn('tipe', ['pdf', 'video', 'lainnya']);
                                $fotoCount     = $lampiranFoto->count();
                                $gridCols      = $fotoCount === 1 ? 'cols-1' : ($fotoCount === 2 ? 'cols-2' : '');
                            @endphp

                            {{-- ── Galeri Foto Lampiran ── --}}
                            @if ($fotoCount)
                            <div class="mt-4">
                                <h6 class="fw-bold mb-1" style="font-size:.88rem;color:#475569;">
                                    <i class="bi bi-images me-1" style="color:#7c3aed;"></i>
                                    Galeri Foto
                                    <span class="badge ms-1" style="background:#f3e8ff;color:#7c3aed;font-size:.72rem;">{{ $fotoCount }}</span>
                                </h6>
                                <div class="lampiran-gallery {{ $gridCols }}">
                                    @foreach ($lampiranFoto as $foto)
                                    <a href="{{ $foto->url }}" target="_blank" title="Lihat gambar penuh">
                                        <img src="{{ $foto->url }}" alt="Lampiran Foto" loading="lazy">
                                        <div class="overlay"><i class="bi bi-zoom-in"></i></div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- ── File Lampiran (PDF, Video, dll) ── --}}
                            @if ($lampiranFiles->count())
                            <div class="mt-4">
                                <h6 class="fw-bold mb-1" style="font-size:.88rem;color:#475569;">
                                    <i class="bi bi-paperclip me-1" style="color:#7c3aed;"></i>
                                    File Lampiran
                                    <span class="badge ms-1" style="background:#f3e8ff;color:#7c3aed;font-size:.72rem;">{{ $lampiranFiles->count() }}</span>
                                </h6>
                                <div class="lampiran-file-list">
                                    @foreach ($lampiranFiles as $file)
                                    @php
                                        $iconClass = match($file->tipe) {
                                            'pdf'   => 'pdf',
                                            'video' => 'video',
                                            default => 'other',
                                        };
                                        $iconBi = match($file->tipe) {
                                            'pdf'   => 'bi-file-earmark-pdf-fill',
                                            'video' => 'bi-play-circle-fill',
                                            default => 'bi-file-earmark-arrow-down-fill',
                                        };
                                        $typeLabel = match($file->tipe) {
                                            'pdf'   => 'PDF',
                                            'video' => 'Video',
                                            default => 'File',
                                        };
                                    @endphp
                                    <a href="{{ $file->url }}" target="_blank" class="lampiran-file-item">
                                        <span class="file-icon {{ $iconClass }}">
                                            <i class="bi {{ $iconBi }}"></i>
                                        </span>
                                        <span class="file-name" title="{{ basename($file->path) }}">
                                            {{ basename($file->path) }}
                                        </span>
                                        <span class="file-dl">
                                            {{ $typeLabel }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <hr class="mt-4">
                        </article>
                    </div>
                </section><!-- End Blog Details Section -->


                <!-- ② Info Sekolah (pengganti Blog Author) -->
                <section id="blog-author" class="blog-author section">
                    <div class="container">
                        <div class="author-container d-flex align-items-center gap-3">

                            {{-- Logo sekolah --}}
                            @if (optional($mading->sekolah)->logo)
                                <img src="{{ asset('storage/' . $mading->sekolah->logo) }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:90px; height:90px; object-fit:cover;"
                                     alt="Logo Sekolah">
                            @else
                                <img src="{{ asset('default-image/default-school.png') }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:90px; height:90px; object-fit:cover;"
                                     alt="Default Sekolah">
                            @endif

                            <div>
                                <h4 class="mb-0">{{ optional($mading->sekolah)->nama_sekolah ?? 'Sekolah' }}</h4>
                                <small class="text-muted">
                                    {{ strtoupper(optional($mading->sekolah)->jenjang ?? '') }}
                                    @if (optional($mading->sekolah)->alamat)
                                        &bull; {{ $mading->sekolah->alamat }}
                                    @endif
                                </small>
                                <p class="mb-0 mt-1 text-muted" style="font-size:13px;">
                                    Ditulis oleh:
                                    <strong>
                                        {{ $mading->user->masyarakat->nama_masyarakat
                                           ?? $mading->user->pegawai->nama_pegawai
                                           ?? 'Anonim' }}
                                    </strong>
                                </p>
                            </div>

                        </div>
                    </div>
                </section><!-- End Blog Author Section -->


                <!-- ③ Reaksi -->
                <section>
                    <h2 class="centered-text">Bagaimana tanggapanmu terhadap mading ini?</h2>
                    <div class="container my-4">

                        <!-- Baris 1 -->
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button"
                                            class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'suka' ? 'aktif' : '' }}"
                                            data-jenis="suka" data-mading="{{ $mading->id_mading }}">👍</button>
                                    <small class="mt-1 d-block text-center count-suka">{{ $reaksiCounts['suka'] ?? 0 }}</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button"
                                            class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'marah' ? 'aktif' : '' }}"
                                            data-jenis="marah" data-mading="{{ $mading->id_mading }}">😡</button>
                                    <small class="mt-1 d-block text-center count-marah">{{ $reaksiCounts['marah'] ?? 0 }}</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button"
                                            class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'sedih' ? 'aktif' : '' }}"
                                            data-jenis="sedih" data-mading="{{ $mading->id_mading }}">😢</button>
                                    <small class="mt-1 d-block text-center count-sedih">{{ $reaksiCounts['sedih'] ?? 0 }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2 -->
                        <div class="row justify-content-center mt-3">
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button"
                                            class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'senang' ? 'aktif' : '' }}"
                                            data-jenis="senang" data-mading="{{ $mading->id_mading }}">😊</button>
                                    <small class="mt-1 d-block text-center count-senang">{{ $reaksiCounts['senang'] ?? 0 }}</small>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <button type="button"
                                            class="btn reaksi-btn {{ $userReaksi && $userReaksi->jenis == 'lucu' ? 'aktif' : '' }}"
                                            data-jenis="lucu" data-mading="{{ $mading->id_mading }}">😂</button>
                                    <small class="mt-1 d-block text-center count-lucu">{{ $reaksiCounts['lucu'] ?? 0 }}</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>


                <!-- ④ Komentar -->
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
                                         alt="Avatar">
                                </div>
                                <div>
                                    <h5>
                                        {{ $komentar->nama
                                          ?: ($komentar->user->masyarakat->nama_masyarakat
                                             ?? $komentar->user->pegawai->nama_pegawai
                                             ?? 'Pengguna Tidak Diketahui') }}

                                        @if (
                                            (auth()->check() && (
                                                in_array(auth()->user()->role, ['pegawai','camat']) ||
                                                auth()->id() === $komentar->id_user
                                            )) ||
                                            (!auth()->check() && is_null($komentar->id_user) && $komentar->ip_address === request()->ip())
                                        )
                                        <div class="dropdown-center d-inline ms-2">
                                            <button class="btn btn-sm btn-light border dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (auth()->check() && in_array(auth()->user()->role, ['pegawai','camat','masyarakat']))
                                                <li>
                                                    <a href="#" class="dropdown-item reply-link"
                                                       data-id="{{ $komentar->id_komentar }}">
                                                        <i class="bi bi-reply-fill"></i> Balas
                                                    </a>
                                                </li>
                                                @endif

                                                @if (auth()->check() && auth()->id() === $komentar->id_user)
                                                <li>
                                                    <a href="#" class="dropdown-item edit-link"
                                                       data-id="{{ $komentar->id_komentar }}">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                </li>
                                                @endif

                                                <li>
                                                    <form action="{{ route('komentar.destroy', $komentar->id_komentar) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin ingin menghapus?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                    </h5>

                                    <time datetime="{{ $komentar->created_at }}">
                                        {{ $komentar->created_at->format('d M, Y') }}
                                    </time>
                                    <p>{{ $komentar->isi_komentar }}</p>

                                    <!-- Form Edit -->
                                    <form action="{{ route('komentar.update', $komentar->id_komentar) }}"
                                          method="POST"
                                          class="edit-form d-none"
                                          id="edit-form-{{ $komentar->id_komentar }}">
                                        @csrf @method('PATCH')
                                        <textarea name="isi" class="form-control mb-2" required>{{ $komentar->isi_komentar }}</textarea>
                                        <button class="btn btn-success btn-sm">Update</button>
                                        <button type="button"
                                                class="btn btn-secondary btn-sm cancel-edit"
                                                data-id="{{ $komentar->id_komentar }}">Cancel</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Balasan -->
                            @if ($komentar->balasan->count())
                            <div class="ms-4">
                                @foreach ($komentar->balasan as $balasan)
                                <div class="comment comment-reply d-flex mt-2"
                                     id="comment-{{ $balasan->id_komentar }}">
                                    <div class="comment-img me-3">
                                        <img src="{{ $balasan->avatar_url }}"
                                             class="rounded-circle"
                                             style="width:40px; height:40px; object-fit:cover"
                                             alt="Avatar">
                                    </div>
                                    <div>
                                        <h6>
                                            {{ $balasan->nama
                                              ?: ($balasan->user->masyarakat->nama_masyarakat
                                                 ?? $balasan->user->pegawai->nama_pegawai
                                                 ?? 'Pengguna Tidak Diketahui') }}
                                        </h6>
                                        <time datetime="{{ $balasan->created_at }}">
                                            {{ $balasan->created_at->format('d M, Y') }}
                                        </time>
                                        <p>{{ $balasan->isi_komentar }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach

                    </div>
                </section>


                <!-- ⑤ Form Balas -->
                @if (auth()->check() && in_array(auth()->user()->role, ['pegawai','camat','masyarakat']))
                <div id="reply-form" class="d-none">
                    <form action="{{ route('mading.balasan.store', $mading->id_mading) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" id="reply_parent_id" value="">
                        <div class="form-group">
                            <textarea name="isi" class="form-control mb-2" placeholder="Tulis balasan..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm me-2">Kirim Balasan</button>
                            <button type="button" id="cancel-reply" class="btn btn-secondary btn-sm">Batal</button>
                        </div>
                    </form>
                </div>
                @endif


                <!-- ⑥ Form Komentar -->
                <section id="comment-form" class="comment-form section">
                    <div class="container">
                        <form action="{{ route('mading.komentar.store', $mading->id_mading) }}" method="POST">
                            @csrf
                            <h4>Tulis Komentar</h4>
                            <p>Email tidak akan dipublikasikan. Field bertanda * wajib diisi.</p>

                            @if (!auth()->check())
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input name="nama" type="text" class="form-control" placeholder="Nama Anda*" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input name="email" type="email" class="form-control" placeholder="Email Anda*" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input name="no_hp" type="text" class="form-control" placeholder="No. HP">
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <textarea name="isi" class="form-control" placeholder="Komentar Anda*" required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                            </div>
                        </form>
                    </div>
                </section>

            </div><!-- End col-lg-8 -->

            <!-- ── Sidebar ── -->
            <div class="col-lg-4 sidebar">
                @include('partials.mading.sidebar')
            </div>

        </div>
    </div>

</main>

@endsection


@section('script')

{{-- ── Reply form toggle ── --}}
<script>
document.querySelectorAll('.reply-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var parentId = this.getAttribute('data-id');
        var replyForm = document.getElementById('reply-form');
        var commentEl = this.closest('.comment');
        commentEl.parentNode.insertBefore(replyForm, commentEl.nextSibling);
        document.querySelector('input[name="parent_id"]').value = parentId;
        replyForm.classList.remove('d-none');
    });
});

var cancelReply = document.getElementById('cancel-reply');
if (cancelReply) {
    cancelReply.addEventListener('click', function() {
        var replyForm = document.getElementById('reply-form');
        replyForm.classList.add('d-none');
        document.querySelector('input[name="parent_id"]').value = '';
    });
}

document.querySelectorAll('.edit-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var commentId = this.getAttribute('data-id');
        document.getElementById('edit-form-' + commentId)?.classList.remove('d-none');
    });
});

document.querySelectorAll('.cancel-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var commentId = this.getAttribute('data-id');
        document.getElementById('edit-form-' + commentId)?.classList.add('d-none');
    });
});
</script>

{{-- ── Reaksi AJAX ── --}}
<script>
$(document).ready(function() {
    $('.reaksi-btn').click(function() {
        const jenis      = $(this).data('jenis');
        const id_mading  = $(this).data('mading');

        $.ajax({
            url: '/mading/reaksi/' + id_mading,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', jenis: jenis },
            success: function(response) {
                $('.reaksi-btn').removeClass('aktif');
                if (response.user_reaksi) {
                    $(`.reaksi-btn[data-jenis="${response.user_reaksi}"]`).addClass('aktif');
                }
                const allTypes = ['suka', 'marah', 'sedih', 'senang', 'lucu'];
                allTypes.forEach(function(t) {
                    $('.count-' + t).text(response.counts[t] || 0);
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
