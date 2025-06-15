@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Add New Page')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Add New Page</h5>
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Pages
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Main Content -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Page Content</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Page Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{ old('title') }}" placeholder="Enter page title" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                           value="{{ old('slug') }}" placeholder="Auto-generated from title">
                                    <small class="text-muted">Leave empty to auto-generate from title</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Excerpt</label>
                                    <textarea class="form-control" name="excerpt" rows="3"
                                              placeholder="Brief description of the page">{{ old('excerpt') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce-editor @error('content') is-invalid @enderror" name="content" id="content" rows="15"
                                              placeholder="Enter page content" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">SEO Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title"
                                           value="{{ old('meta_title') }}" placeholder="SEO title for search engines">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3"
                                              placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords"
                                           value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                    <small class="text-muted">Separate keywords with commas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Published</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Author</label>
                                    <select class="form-select" name="author_id">
                                        <option value="">Select Author</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Leave empty to use current user</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Published Date</label>
                                    <input type="datetime-local" class="form-control" name="published_at"
                                           value="{{ old('published_at') }}">
                                    <small class="text-muted">Leave empty to publish immediately</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Template</label>
                                    <select class="form-select" name="template">
                                        <option value="default" {{ old('template', 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                        <option value="full-width" {{ old('template') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                                        <option value="sidebar" {{ old('template') == 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="number" class="form-control" name="position" min="0"
                                           value="{{ old('position', '0') }}" placeholder="0">
                                    <small class="text-muted">Higher numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Featured Image</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="featured_image" accept="image/*">
                                    <small class="text-muted">Recommended size: 1200x600 pixels</small>
                                </div>
                            </div>
                        </div>

                        <!-- Display Options -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Display Options</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="in_footer" value="1"
                                           id="in_footer" {{ old('in_footer') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="in_footer">
                                        Show in Footer
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="in_header" value="1"
                                           id="in_header" {{ old('in_header') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="in_header">
                                        Show in Header
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="align-middle" data-feather="save"></i> Save Page
                                    </button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                                        <i class="align-middle" data-feather="x"></i> Cancel
                                    </a>
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
});
</script>
@endpush
