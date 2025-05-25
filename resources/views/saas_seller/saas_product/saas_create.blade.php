@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Create Product')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Product</h5>
                <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
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
                                <!-- Use Livewire Component for Category Dropdowns -->
                                @livewire('saas-category-dropdown')

                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select class="form-select" id="brand_id" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
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
                                        @foreach($units as $unit)
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

                <!-- Product Variations Component -->
                @livewire('saas-product-variations')

                <div class="row mb-3" id="regular-pricing" x-data="{ hasVariations: false }" x-init="hasVariations = document.getElementById('has_variations')?.checked || false; $watch('hasVariations', value => { document.getElementById('regular-pricing').style.display = value ? 'none' : 'flex'; })" x-show="!hasVariations">
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
                                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0">
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="SKU" class="form-label">SKU <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="SKU" name="SKU" value="{{ old('SKU') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0">
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
                                    <label for="images" class="form-label">Additional Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                    <small class="text-muted">Upload additional product images (Optional)</small>
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
    });
</script>
@endsection
