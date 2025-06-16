@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create In-House Product')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create New In-House Product</h5>
                    <a href="{{ route('admin.in-house-products.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to In-House Products
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

                <form action="{{ route('admin.in-house-products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="product_type" class="form-label">Product Type <span class="text-danger">*</span></label>
                                                <select class="form-select" id="product_type" name="product_type" required>
                                                    <option value="">Select Product Type</option>
                                                    <option value="Digital" {{ old('product_type') == 'Digital' ? 'selected' : '' }}>
                                                        Digital Product
                                                    </option>
                                                    <option value="Physical" {{ old('product_type', 'Physical') == 'Physical' ? 'selected' : '' }}>
                                                        Physical Product
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="SKU" class="form-label">SKU <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="SKU" name="SKU" value="{{ old('SKU') }}" required>
                                                <small class="text-muted">Unique product identifier</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3" id="digital_file_section" style="display: none;">
                                        <label for="file" class="form-label">Digital Product File <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.zip,.rar,.txt,.mp3,.mp4,.avi,.mov">
                                        <small class="text-muted">Supported formats: PDF, DOC, DOCX, ZIP, RAR, TXT, MP3, MP4, AVI, MOV (Max: 50MB)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="short_description" name="short_description" rows="3" required>{{ old('short_description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Categorize</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="subcategory_id" class="form-label">Sub Category</label>
                                        <select class="form-select" id="subcategory_id" name="subcategory_id">
                                            <option value="">Select Sub Category</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="child_category_id" class="form-label">Child Category</label>
                                        <select class="form-select" id="child_category_id" name="child_category_id">
                                            <option value="">Select Child Category</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" id="brand_id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                        <select class="form-select" id="unit_id" name="unit_id" required>
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Pricing & Inventory</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rs</span>
                                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discount" class="form-label">Discount</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', 0) }}" step="0.01" min="0">
                                                    <select class="form-select" id="discount_type" name="discount_type">
                                                        <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Rs</option>
                                                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="stock_section">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                                <small class="text-muted">Available quantity in inventory</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                        <small class="text-muted">Enable to make this product visible to customers</small>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Featured</label>
                                        </div>
                                        <small class="text-muted">Enable to highlight this product on the homepage</small>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> This product will be marked as an in-house product automatically.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Product Images</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label">Primary Image (Thumbnail) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*" required>
                                        <small class="text-muted">Upload the main product image (Required)</small>
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle"></i> All images will be saved in the public storage folder.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="product_images" class="form-label">Additional Product Images</label>
                                        <input type="file" class="form-control" id="product_images" name="product_images[]" accept="image/*" multiple>
                                        <small class="text-muted">Select multiple images by holding Ctrl/Cmd while selecting</small>

                                        <div id="selected-images-preview" class="row mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Create In-House Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle product type changes
            const productTypeSelect = document.getElementById('product_type');
            const digitalFileSection = document.getElementById('digital_file_section');
            const stockSection = document.getElementById('stock_section');
            const stockInput = document.getElementById('stock');
            const fileInput = document.getElementById('file');

            function toggleProductTypeFields() {
                const selectedType = productTypeSelect.value;

                if (selectedType === 'Digital') {
                    digitalFileSection.style.display = 'block';
                    stockSection.style.display = 'none';
                    fileInput.required = true;
                    stockInput.required = false;
                    stockInput.value = '999999'; // Set high stock for digital products
                } else if (selectedType === 'Physical') {
                    digitalFileSection.style.display = 'none';
                    stockSection.style.display = 'block';
                    fileInput.required = false;
                    stockInput.required = true;
                    stockInput.value = '{{ old('stock', 0) }}';
                } else {
                    digitalFileSection.style.display = 'none';
                    stockSection.style.display = 'block';
                    fileInput.required = false;
                    stockInput.required = true;
                }
            }

            // Initial check
            toggleProductTypeFields();

            // Listen for changes
            productTypeSelect.addEventListener('change', toggleProductTypeFields);

            // Category dependent dropdowns
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const childCategorySelect = document.getElementById('child_category_id');

            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;

                // Clear dependent dropdowns
                subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';

                if (categoryId) {
                    fetch(`{{ url('admin/subcategories/by-category') }}/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subcategory => {
                                const option = document.createElement('option');
                                option.value = subcategory.id;
                                option.textContent = subcategory.name;
                                subcategorySelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            subcategorySelect.addEventListener('change', function() {
                const subcategoryId = this.value;

                // Clear child category dropdown
                childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';

                if (subcategoryId) {
                    fetch(`{{ url('admin/childcategories/by-subcategory') }}/${subcategoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(childCategory => {
                                const option = document.createElement('option');
                                option.value = childCategory.id;
                                option.textContent = childCategory.name;
                                childCategorySelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Multiple Images Preview
            const multipleImagesInput = document.getElementById('product_images');
            const previewContainer = document.getElementById('selected-images-preview');

            if (multipleImagesInput && previewContainer) {
                multipleImagesInput.addEventListener('change', function() {
                    previewContainer.innerHTML = '';

                    if (this.files) {
                        Array.from(this.files).forEach((file, index) => {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                const col = document.createElement('div');
                                col.className = 'col-md-3 col-sm-4 col-6 mb-3';

                                const card = document.createElement('div');
                                card.className = 'card h-100';

                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'card-img-top';
                                img.style = 'height: 150px; object-fit: cover;';

                                const cardBody = document.createElement('div');
                                cardBody.className = 'card-body p-2 text-center';

                                const fileName = document.createElement('small');
                                fileName.className = 'text-muted';
                                fileName.textContent = file.name;

                                cardBody.appendChild(fileName);
                                card.appendChild(img);
                                card.appendChild(cardBody);
                                col.appendChild(card);
                                previewContainer.appendChild(col);
                            };

                            reader.readAsDataURL(file);
                        });
                    }
                });
            }
        });
    </script>
@endsection
