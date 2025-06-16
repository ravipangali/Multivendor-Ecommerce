@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit In-House Product')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit In-House Product: {{ $product->name }}</h5>
                    <div>
                        <a href="{{ route('admin.in-house-products.show', $product) }}" class="btn btn-info me-2">
                            <i class="align-middle" data-feather="eye"></i> View Product
                        </a>
                        <a href="{{ route('admin.in-house-products.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                        </a>
                    </div>
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

                <form action="{{ route('admin.in-house-products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> This is an In-House product. Seller field is automatically managed.
                                    </div>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="product_type" class="form-label">Product Type <span class="text-danger">*</span></label>
                                                <select class="form-select" id="product_type" name="product_type" required>
                                                    <option value="">Select Product Type</option>
                                                    <option value="Digital" {{ old('product_type', $product->product_type) == 'Digital' ? 'selected' : '' }}>Digital Product</option>
                                                    <option value="Physical" {{ old('product_type', $product->product_type) == 'Physical' ? 'selected' : '' }}>Physical Product</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="SKU" class="form-label">SKU <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="SKU" name="SKU" value="{{ old('SKU', $product->SKU) }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="short_description" name="short_description" rows="3" required>{{ old('short_description', $product->short_description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description', $product->description) }}</textarea>
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
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="sub_category_id" class="form-label">Sub Category</label>
                                        <select class="form-select" id="sub_category_id" name="sub_category_id">
                                            <option value="">Select Sub Category</option>
                                            @if($product->subcategory)
                                                <option value="{{ $product->subcategory->id }}" selected>
                                                    {{ $product->subcategory->name }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
                                        <select class="form-select" id="brand_id" name="brand_id" required>
                                            <option value="">Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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
                                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
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
                                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="discount" class="form-label">Discount</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount', $product->discount) }}" step="0.01" min="0">
                                                    <select class="form-select" id="discount_type" name="discount_type">
                                                        <option value="flat" {{ old('discount_type', $product->discount_type) == 'flat' ? 'selected' : '' }}>Rs</option>
                                                        <option value="percentage" {{ old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : '' }}>%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
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
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                        <small class="text-muted">Enable to make this product visible to customers</small>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
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
                                    <!-- Current Images -->
                                    @if($product->images->count() > 0)
                                        <div class="mb-4">
                                            <h6>Current Images</h6>
                                            <div class="row">
                                                @foreach($product->images as $image)
                                                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                                                        <div class="card">
                                                            <img src="{{ $image->image_url }}"
                                                                 class="card-img-top"
                                                                 style="height: 200px; object-fit: cover;"
                                                                 onerror="this.onerror=null;this.src='{{ asset('images/no-image.svg') }}';this.style.backgroundColor='#f8f9fa';this.style.border='1px solid #dee2e6';"
                                                            <div class="card-body p-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="deleted_images[]" value="{{ $image->id }}" id="remove_image_{{ $image->id }}">
                                                                    <label class="form-check-label" for="remove_image_{{ $image->id }}">Remove</label>
                                                                </div>
                                                                @if($product->thumbnail === $image->image_url)
                                                                    <span class="badge bg-primary">Thumbnail</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label">New Primary Image (Thumbnail)</label>
                                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                                        <small class="text-muted">Upload a new main product image (will replace current thumbnail)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="product_images" class="form-label">Additional Product Images</label>
                                        <input type="file" class="form-control" id="product_images" name="product_images[]" accept="image/*" multiple>
                                        <small class="text-muted">Select multiple images to add to existing gallery</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Update In-House Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
