@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Product')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create New Product</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Products
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

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3" id="seller_selection">
                                        <label for="seller_id" class="form-label">Seller <span
                                                class="text-danger" id="seller_required">*</span></label>
                                        <select class="form-select" id="seller_id" name="seller_id">
                                            <option value="">Select Seller</option>
                                            @foreach ($sellers as $seller)
                                                <option value="{{ $seller->id }}"
                                                    {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                                    {{ $seller->name }} ({{ $seller->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Leave empty for in-house products</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="product_type" class="form-label">Product Type <span
                                                        class="text-danger">*</span></label>
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
                                                <div class="form-check form-switch mt-4">
                                                    <input class="form-check-input" type="checkbox" id="is_in_house_product"
                                                        name="is_in_house_product" value="1"
                                                        {{ old('is_in_house_product') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_in_house_product">In-House Product</label>
                                                </div>
                                                <small class="text-muted">Check if this product is sold by the platform directly</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3" id="digital_file_section" style="display: none;">
                                        <label for="file" class="form-label">Digital Product File <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="file" name="file"
                                            accept=".pdf,.doc,.docx,.zip,.rar,.txt,.mp3,.mp4,.avi,.mov">
                                        <small class="text-muted">Supported formats: PDF, DOC, DOCX, ZIP, RAR, TXT, MP3, MP4, AVI, MOV (Max: 50MB)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" id="short_description" name="short_description" rows="3" required>{{ old('short_description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Full Description <span
                                                class="text-danger">*</span></label>
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
                                    <!-- Use Livewire Component for Category Dropdowns -->
                                    @livewire('saas-category-dropdown')

                                    <div class="mb-3">
                                        <label for="brand_id" class="form-label">Brand <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="brand_id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="unit_id" name="unit_id" required>
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Variations Component -->
                    @livewire('saas-product-variations')

                    <div class="row mb-3" id="regular-pricing">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Pricing & Inventory</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Regular Price <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rs</span>
                                                    <input type="number" class="form-control" id="price" name="price"
                                                        value="{{ old('price') }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discount" class="form-label">Discount</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="discount"
                                                        name="discount" value="{{ old('discount', 0) }}" step="0.01"
                                                        min="0">
                                                    <select class="form-select" id="discount_type" name="discount_type">
                                                        <option value="flat"
                                                            {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Rs
                                                        </option>
                                                        <option value="percentage"
                                                            {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="SKU" class="form-label">SKU <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="SKU" name="SKU"
                                                    value="{{ old('SKU') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3" id="stock_section">
                                                <label for="stock" class="form-label">Stock Quantity <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="stock" name="stock"
                                                    value="{{ old('stock', 0) }}" min="0">
                                                <small class="text-muted">Leave blank for digital products (unlimited stock)</small>
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
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                        <small class="text-muted">Enable to make this product visible to customers</small>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_featured"
                                                name="is_featured" value="1"
                                                {{ old('is_featured') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">Featured</label>
                                        </div>
                                        <small class="text-muted">Enable to highlight this product on the homepage</small>
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

                                    <!-- Product Images Section -->
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
                        <button type="submit" class="btn btn-primary">Create Product</button>
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

            // Handle in-house product checkbox
            const inHouseCheckbox = document.getElementById('is_in_house_product');
            const sellerSelection = document.getElementById('seller_selection');
            const sellerSelect = document.getElementById('seller_id');
            const sellerRequired = document.getElementById('seller_required');

            function toggleProductTypeFields() {
                const selectedType = productTypeSelect.value;

                if (selectedType === 'Digital') {
                    digitalFileSection.style.display = 'block';
                    stockSection.style.display = 'none';
                    fileInput.required = true;
                    stockInput.required = false;
                    stockInput.value = '';
                } else if (selectedType === 'Physical') {
                    digitalFileSection.style.display = 'none';
                    stockSection.style.display = 'block';
                    fileInput.required = false;
                    stockInput.required = true;
                    fileInput.value = '';
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

            function toggleSellerField() {
                if (inHouseCheckbox.checked) {
                    sellerSelect.removeAttribute('required');
                    sellerSelect.value = '';
                    sellerRequired.style.display = 'none';
                    sellerSelection.style.opacity = '0.6';
                    sellerSelect.disabled = true;
                } else {
                    sellerSelect.setAttribute('required', 'required');
                    sellerRequired.style.display = 'inline';
                    sellerSelection.style.opacity = '1';
                    sellerSelect.disabled = false;
                }
            }

            // Initial check
            toggleSellerField();

            // Listen for in-house checkbox changes
            inHouseCheckbox.addEventListener('change', toggleSellerField);

            // Don't hide regular pricing and inventory when variations are enabled
            Livewire.on('hasVariationsChanged', hasVariations => {
                // No need to hide regular pricing now
            });

            // Update stock field when variations stock changes
            Livewire.on('variationsStockUpdated', totalStock => {
                const stockField = document.getElementById('stock');
                if (stockField) {
                    stockField.value = totalStock;
                }
            });

            // Listen for the Livewire event to update stock field
            Livewire.on('update-stock-field', data => {
                const stockField = document.getElementById('stock');
                if (stockField) {
                    stockField.value = data.totalStock;
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
