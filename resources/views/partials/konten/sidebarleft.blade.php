<div class="widgets-container">




        <!--/Search Widget -->

    <!-- Categories Widget -->
    <div class="categories-widget widget-item">
        <h3 class="widget-title">Categories</h3>
        <ul class="mt-3">
            @foreach($categories as $cat)
              <li>
                <a href="{{ route('category.show', $cat->slug) }}">
                  <i class="{{ $cat->icon }} me-1"></i>
                  {{ $cat->nama_kategori }} <span>({{ $cat->kategori_count }})</span>
                </a>
              </li>
            @endforeach
          </ul>

      </div>


    <!--/Categories Widget -->

    <!-- Recent Posts Widget -->
    <div class="recent-posts-widget widget-item">

      <h3 class="widget-title">Recent Posts</h3>

      @foreach($recentPosts as $post)
      <div class="post-item">
        <img src="{{ asset('storage/' . $post->gambar) }}" alt="" class="flex-shrink-0">
        <div>
          <h4><a href="{{ route('konten.detail', [$post->jenis_konten, $post->slug]) }}">
            {{ $post->judul }}</a></h4>
          <time datetime="{{ $post->created_at->format('Y-m-d') }}">
            {{ $post->created_at->format('M d, Y') }}
          </time>
        </div>
      </div>
    @endforeach


    </div><!--/Recent Posts Widget -->


  </div>
