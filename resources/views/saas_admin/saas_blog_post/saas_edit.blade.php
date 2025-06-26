@extends('saas_admin.saas_layouts.saas_layout')


@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Edit</strong> Blog Post</h3>
        </div>
        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Posts
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.blog-posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Post Content</h5>
                            </div>
                            <div class="card-body">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $post->title) }}"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           id="slug"
                                           name="slug"
                                           value="{{ old('slug', $post->slug) }}"
                                           required>
                                    <small class="form-text text-muted">URL-friendly version of the title</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                              id="excerpt"
                                              name="excerpt"
                                              rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                                    <small class="form-text text-muted">Brief description of the post</small>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce-editor @error('content') is-invalid @enderror"
                                              id="content"
                                              name="content"
                                              rows="15">{{ old('content', $post->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input type="text"
                                           class="form-control @error('tags') is-invalid @enderror"
                                           id="tags"
                                           name="tags"
                                           value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : '') }}"
                                           placeholder="Enter tags separated by commas">
                                    <small class="form-text text-muted">Separate tags with commas</small>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Publishing Options -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Publishing Options</h5>
                            </div>
                            <div class="card-body">
                                <!-- Status -->
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="0" {{ old('status', $post->status) == '0' ? 'selected' : '' }}>Draft</option>
                                        <option value="1" {{ old('status', $post->status) == '1' ? 'selected' : '' }}>Published</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Author -->
                                <div class="mb-3">
                                    <label for="author_id" class="form-label">Author</label>
                                    <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ old('author_id', $post->author_id) == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Featured -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input @error('is_featured') is-invalid @enderror"
                                               type="checkbox"
                                               id="is_featured"
                                               name="is_featured"
                                               value="1"
                                               {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Post
                                        </label>
                                    </div>
                                    @error('is_featured')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="align-middle" data-feather="save"></i> Update Post
                                    </button>
                                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                                        <i class="align-middle" data-feather="x"></i> Cancel
                                    </a>
                                    @if($post->status)
                                        <a href="{{ route('customer.blog.show', $post->slug) }}" class="btn btn-outline-info" target="_blank">
                                            <i class="align-middle" data-feather="eye"></i> View Post
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE Editor
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 400,
        menubar: false,
        license_key: 'gpl',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | link image | code | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; line-height: 1.6; }',
        branding: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        image_advtab: true,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image table',
        // Image upload configuration
        images_upload_url: '{{ route("admin.tinymce.upload") }}',
        images_upload_handler: function (blobInfo, success, failure, progress) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.tinymce.upload") }}');

            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = function (e) {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function() {
                var json;

                if (xhr.status === 403) {
                    failure('HTTP Error: ' + xhr.status, { remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                try {
                    json = JSON.parse(xhr.responseText);
                } catch (e) {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            xhr.onerror = function () {
                failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },

        // File picker callback for browsing files
        file_picker_callback: function (callback, value, meta) {
            if (meta.filetype === 'image') {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        callback(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
        },

        // Enable automatic uploads and paste handling
        automatic_uploads: true,
        paste_data_images: true,
        images_upload_credentials: false,

        // Setup callback for additional configuration
        setup: function (editor) {
            editor.on('init', function () {
                console.log('TinyMCE initialized successfully');
            });

            // Handle paste events to convert base64 images
            editor.on('PastePreProcess', function (e) {
                // Look for base64 images in pasted content
                var content = e.content;
                var base64Pattern = /<img[^>]+src=["']data:image\/[^;]+;base64,([^"']+)["'][^>]*>/gi;
                var matches = content.match(base64Pattern);

                if (matches && matches.length > 0) {
                    console.log('Found base64 images in paste, converting...');

                    matches.forEach(function(match, index) {
                        var base64Match = match.match(/data:image\/([^;]+);base64,([^"']+)/);
                        if (base64Match) {
                            var imageType = base64Match[1];
                            var base64Data = base64Match[2];

                            // Convert base64 to blob
                            var byteCharacters = atob(base64Data);
                            var byteNumbers = new Array(byteCharacters.length);
                            for (var i = 0; i < byteCharacters.length; i++) {
                                byteNumbers[i] = byteCharacters.charCodeAt(i);
                            }
                            var byteArray = new Uint8Array(byteNumbers);
                            var blob = new Blob([byteArray], {type: 'image/' + imageType});

                            // Create blob info for TinyMCE
                            var id = 'paste' + (new Date()).getTime() + index;
                            var blobInfo = editor.editorUpload.blobCache.create(id, blob, base64Data);
                            editor.editorUpload.blobCache.add(blobInfo);

                            // Replace the base64 image with blob URI temporarily
                            // TinyMCE will automatically upload it
                            content = content.replace(match, match.replace(/src=["']data:image\/[^"']+["']/, 'src="' + blobInfo.blobUri() + '"'));
                        }
                    });

                    e.content = content;
                }
            });

            editor.on('UploadFailure', function (e) {
                console.error('TinyMCE Upload Failed:', e);
            });

            editor.on('UploadComplete', function (e) {
                console.log('TinyMCE Upload Complete:', e);
            });
        }
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Image preview
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
