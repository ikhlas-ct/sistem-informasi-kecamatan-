<script>
$(document).ready(function () {

    // ── Inisialisasi Summernote ──────────────────────────────────
    $('.summernote').summernote({
        height: 400,
        lang: 'id-ID',
        placeholder: 'Tulis isi mading di sini…',
        toolbar: [
            ['style',    ['style']],
            ['font',     ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color',    ['color']],
            ['para',     ['ul', 'ol', 'paragraph']],
            ['height',   ['height']],
            ['table',    ['table']],
            ['insert',   ['link', 'picture', 'video', 'hr']],
            ['view',     ['fullscreen', 'codeview']],
        ],
        callbacks: {
            onImageUpload: function (files) {
                uploadMadingImage(files[0]);
            },
            onMediaDelete: function (target) {
                deleteMadingImage($(target).attr('src'));
            }
        }
    });

    // ── MutationObserver: deteksi hapus gambar via Backspace/Delete ──
    var editable = document.querySelector('.note-editable');
    if (editable) {
        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.removedNodes.forEach(function (node) {
                    if (node.nodeName === 'IMG') {
                        deleteMadingImage(node.getAttribute('src'));
                    } else if (node.querySelectorAll) {
                        node.querySelectorAll('img').forEach(function (img) {
                            deleteMadingImage(img.getAttribute('src'));
                        });
                    }
                });
            });
        }).observe(editable, { childList: true, subtree: true });
    }

    // ── Upload gambar inline ke Summernote ───────────────────────
    function uploadMadingImage(file) {
        var data = new FormData();
        data.append('image', file);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('mading.upload.image') }}",
            method: 'POST',
            data: data,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.url) $('.summernote').summernote('insertImage', res.url);
            },
            error: function (xhr) {
                console.error('Upload gambar gagal:', xhr.responseText);
            }
        });
    }

    // ── Hapus gambar inline dari server ─────────────────────────
    function deleteMadingImage(src) {
        if (!src) return;
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('mading.delete.image') }}",
            method: 'POST',
            data: { src: src }
        });
    }

    // ── Preview gambar sampul (drop zone) ────────────────────────
    var gambarInput       = document.getElementById('gambar-input');
    var gambarPreview     = document.getElementById('gambar-preview');
    var gambarPlaceholder = document.getElementById('gambar-placeholder');
    var dropZone          = document.getElementById('gambar-drop-zone')
                         || document.querySelector('.gambar-wrap');

    function showPreview(file) {
        if (!file || !file.type.match('image.*')) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            if (gambarPreview) {
                gambarPreview.src = e.target.result;
                gambarPreview.style.display = 'block';
            }
            if (gambarPlaceholder) gambarPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    if (gambarInput) {
        gambarInput.addEventListener('change', function () {
            if (this.files && this.files[0]) showPreview(this.files[0]);
        });
    }

    if (dropZone) {
        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        dropZone.addEventListener('dragleave', function () {
            dropZone.classList.remove('drag-over');
        });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            var file = e.dataTransfer.files[0];
            if (file && gambarInput) {
                var dt = new DataTransfer();
                dt.items.add(file);
                gambarInput.files = dt.files;
                showPreview(file);
            }
        });
    }

    // ── Char counter judul ───────────────────────────────────────
    function initCounter(inputId, counterId, max) {
        var el      = document.getElementById(inputId);
        var counter = document.getElementById(counterId);
        if (!el || !counter) return;
        var update = function () {
            var len = el.value.length;
            counter.textContent = len + ' / ' + max;
            counter.className   = 'char-counter' +
                (len >= max ? ' danger' : len >= max * 0.85 ? ' warning' : '');
        };
        el.addEventListener('input', update);
        update();
    }

    initCounter('judul', 'judul-counter', 255);

});
</script>
