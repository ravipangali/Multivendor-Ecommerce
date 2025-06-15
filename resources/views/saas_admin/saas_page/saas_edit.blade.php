@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit CMS Page')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Edit</strong> CMS Page</h3>
        </div>
        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Pages
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Page Content</h5>
                            </div>
                            <div class="card-body">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $page->title) }}"
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
                                           value="{{ old('slug', $page->slug) }}"
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
                                              rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                                    <small class="form-text text-muted">Brief description of the page</small>
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
                                              rows="15"
                                              required>{{ old('content', $page->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">SEO Settings</h5>
                            </div>
                            <div class="card-body">
                                <!-- Meta Title -->
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text"
                                           class="form-control @error('meta_title') is-invalid @enderror"
                                           id="meta_title"
                                           name="meta_title"
                                           value="{{ old('meta_title', $page->meta_title) }}"
                                           maxlength="60">
                                    <small class="form-text text-muted">Recommended: 50-60 characters</small>
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                              id="meta_description"
                                              name="meta_description"
                                              rows="3"
                                              maxlength="160">{{ old('meta_description', $page->meta_description) }}</textarea>
                                    <small class="form-text text-muted">Recommended: 150-160 characters</small>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Meta Keywords -->
                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text"
                                           class="form-control @error('meta_keywords') is-invalid @enderror"
                                           id="meta_keywords"
                                           name="meta_keywords"
                                           value="{{ old('meta_keywords', $page->meta_keywords) }}"
                                           placeholder="keyword1, keyword2, keyword3">
                                    <small class="form-text text-muted">Separate keywords with commas</small>
                                    @error('meta_keywords')
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
                                        <option value="1" {{ old('status', $page->status) == '1' ? 'selected' : '' }}>Published</option>
                                        <option value="0" {{ old('status', $page->status) == '0' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Author -->
                                <div class="mb-3">
                                    <label for="author_id" class="form-label">Author</label>
                                    <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ old('author_id', $page->author_id) == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Template -->
                                <div class="mb-3">
                                    <label for="template" class="form-label">Template</label>
                                    <select class="form-select @error('template') is-invalid @enderror" id="template" name="template">
                                        <option value="default" {{ old('template', $page->template) == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="full-width" {{ old('template', $page->template) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                                        <option value="sidebar" {{ old('template', $page->template) == 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                                    </select>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Published Date -->
                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Published Date</label>
                                    <input type="datetime-local"
                                           class="form-control @error('published_at') is-invalid @enderror"
                                           id="published_at"
                                           name="published_at"
                                           value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Show in Header -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input @error('in_header') is-invalid @enderror"
                                               type="checkbox"
                                               id="in_header"
                                               name="in_header"
                                               value="1"
                                               {{ old('in_header', $page->in_header) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="in_header">
                                            Show in Header Menu
                                        </label>
                                    </div>
                                    @error('in_header')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Featured Image</h5>
                            </div>
                            <div class="card-body">
                                @if($page->featured_image)
                                    <div class="mb-3">
                                        <img src="{{ $page->featured_image_url }}" alt="Current featured image" class="img-fluid rounded" style="max-height: 200px;">
                                        <p class="mt-2 mb-0"><small class="text-muted">Current featured image</small></p>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">
                                        {{ $page->featured_image ? 'Change Featured Image' : 'Featured Image' }}
                                    </label>
                                    <input type="file"
                                           class="form-control @error('featured_image') is-invalid @enderror"
                                           id="featured_image"
                                           name="featured_image"
                                           accept="image/*">
                                    <small class="form-text text-muted">Recommended: 1200x630px, Max: 2MB</small>
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($page->featured_image)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remove_featured_image" name="remove_featured_image" value="1">
                                        <label class="form-check-label" for="remove_featured_image">
                                            Remove current featured image
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="align-middle" data-feather="save"></i> Update Page
                                    </button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                                        <i class="align-middle" data-feather="x"></i> Cancel
                                    </a>
                                    @if($page->status)
                                        <a href="{{ route('customer.page', $page->slug) }}" class="btn btn-outline-info" target="_blank">
                                            <i class="align-middle" data-feather="eye"></i> View Page
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

@push('scripts')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE Editor
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 400,
        menubar: false,
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
@endpush
