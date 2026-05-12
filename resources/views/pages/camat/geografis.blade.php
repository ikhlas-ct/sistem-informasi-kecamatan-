@extends('layouts.user.user')

@section('title', 'Profil Kecamatan - Geografis')

@section('content')
<div class="container">
  <div class="page-inner">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Geografis</div>
      </div>
      <div class="card-body">
        @include('partials.alert.alert')
        <form action="{{ route('profil_kecamatan.geografis.update') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="geografis" class="form-label">Geografis</label>
            <textarea class="form-control summernote" id="geografis" name="geografis" rows="3">{{ old('geografis', $profil->geografis ?? '') }}</textarea>
          </div>
          <div class="card-action text-end">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script>
 $(document).ready(function(){
        $('.summernote').summernote({
    height: 500,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['insert', ['link', 'picture', 'video']],
    ],
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Times New Roman', 'Roboto', 'Tahoma', 'Verdana'],
    fontNamesIgnoreCheck: ['Roboto'],
    callbacks: {
        onImageUpload: function(files) {
            uploadImage(files[0]);
        },
        onMediaDelete: function(target) {
            deleteImage(target[0].src);
        }
    }
});

        // Fungsi upload gambar
        function uploadImage(file) {
            let data = new FormData();
            data.append("image", file);
            data.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('profil.upload.image') }}",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(url) {
                    $('.summernote').summernote('insertImage', url);
                },
                error: function(xhr, status, error) {
                    console.log("Upload gagal: ", xhr.responseText);
                }
            });
        }

        // Fungsi hapus gambar
        function deleteImage(src) {
            $.ajax({
                data: {
                    src: src,
                    _token: '{{ csrf_token() }}'
                },
                type: "POST",
                url: "{{ route('profil.delete.image') }}",
                success: function(resp) {
                    console.log("Gambar berhasil dihapus dari server");
                },
                error: function(xhr, status, error) {
                    console.log("Delete gagal: ", xhr.responseText);
                }
            });
        }
    });
    </script>





@endsection
