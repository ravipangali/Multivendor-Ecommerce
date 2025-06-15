@extends('saas_admin.saas_layouts.saas_app')

@section('title', 'TinyMCE Test')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">TinyMCE Image Upload Test</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control tinymce-editor" id="content" name="content" rows="10">
                                <p>Test content. Try uploading an image using the image button in the toolbar.</p>
                            </textarea>
                        </div>
                        <button type="button" class="btn btn-primary">Test Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing TinyMCE...');

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

        // Simplified Image upload configuration
        images_upload_url: '{{ route("admin.tinymce.upload") }}',
        images_upload_handler: function (blobInfo, success, failure, progress) {
            console.log('Upload handler called', blobInfo);
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("admin.tinymce.upload") }}');

            xhr.upload.onprogress = function (e) {
                console.log('Upload progress:', e.loaded / e.total * 100);
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = function() {
                console.log('Upload response:', xhr.status, xhr.responseText);
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
                    console.error('JSON parse error:', e);
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                console.log('Upload successful:', json.location);
                success(json.location);
            };

            xhr.onerror = function () {
                console.error('XHR error:', xhr.status);
                failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.send(formData);
        },

        automatic_uploads: true,
        paste_data_images: true,

        // Add setup callback for debugging
        setup: function (editor) {
            editor.on('init', function () {
                console.log('TinyMCE initialized successfully');
            });

            editor.on('UploadFailure', function (e) {
                console.error('TinyMCE Upload Failed:', e);
            });

            editor.on('UploadComplete', function (e) {
                console.log('TinyMCE Upload Complete:', e);
            });
        }
    });
});
</script>
@endpush
