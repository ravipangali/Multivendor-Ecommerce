@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Product Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Details</h5>
                <div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-5">
                    <!-- Product Images -->
                    <div class="card">
                        <div class="card-body">
                            @if($product->images->count() > 0)
                                <div class="mb-4">
                                    <img src="{{ asset($product->images->first()->image_url) }}"
                                         class="img-fluid rounded"
                                         alt="{{ $product->name }}"
                                         id="main-product-image">
                                </div>

                                @if($product->images->count() > 1)
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        @foreach($product->images as $image)
                                            <img src="{{ asset($image->image_url) }}"
                                                 class="img-thumbnail"
                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 onclick="document.getElementById('main-product-image').src='{{ asset($image->image_url) }}'"
                                                 alt="{{ $product->name }}">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <i data-feather="image" class="mb-2" style="width: 64px; height: 64px;"></i>
                                    <p>No images available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <!-- Product Basic Information -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="mb-3">{{ $product->name }}</h4>

                            <div class="mb-3">
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>

                                @if($product->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <h5>Price:</h5>
                                @if($product->discount > 0)
                                    <p class="mb-1">
                                        <span class="text-decoration-line-through text-muted fs-5">Rs{{ number_format($product->price, 2) }}</span>
                                        <span class="text-danger fs-4 fw-bold">Rs{{ number_format($product->final_price, 2) }}</span>

                                        @if($product->discount_type == 'percentage')
                                            <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                                        @else
                                            <span class="badge bg-danger">Rs{{ number_format($product->discount, 2) }} OFF</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="mb-1 fs-4 fw-bold">Rs{{ number_format($product->price, 2) }}</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <h5>SKU:</h5>
                                <p class="mb-1">{{ $product->SKU }}</p>
                            </div>

                            <div class="mb-3">
                                <h5>Stock:</h5>
                                @if($product->stock > 0)
                                    <p class="mb-1 text-success">{{ $product->stock }} in stock</p>
                                @else
                                    <p class="mb-1 text-danger">Out of stock</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <h5>Short Description:</h5>
                                <p class="mb-1">{{ $product->short_description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Product Categories & Brand -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organization</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6>Category:</h6>
                                        <p>{{ $product->category->name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Subcategory:</h6>
                                        <p>{{ $product->subcategory->name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Child Category:</h6>
                                        <p>{{ $product->childCategory->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6>Brand:</h6>
                                        <p>{{ $product->brand->name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Unit:</h6>
                                        <p>{{ $product->unit->name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Seller:</h6>
                                        <p>{{ $product->seller->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Full Description -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Full Description</h5>
                </div>
                <div class="card-body">
                    <div class="product-description">
                        {{ $product->description }}
                    </div>
                </div>
            </div>

            <!-- Product Variations (if applicable) -->
            @if($product->variations && $product->variations->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Variations</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Attribute</th>
                                        <th>Value</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->variations as $variation)
                                        <tr>
                                            <td>{{ $variation->attribute->name }}</td>
                                            <td>{{ $variation->attributeValue->value }}</td>
                                            <td>{{ $variation->sku }}</td>
                                            <td>Rs{{ number_format($variation->price, 2) }}</td>
                                            <td class="{{ $variation->stock > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $variation->stock }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Reviews (if applicable) -->
            @if($product->reviews && $product->reviews->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Product Reviews ({{ $product->reviews->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($product->reviews as $review)
                            <div class="border-bottom mb-3 pb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>{{ $review->customer->name ?? 'Anonymous' }}</h6>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i data-feather="star" class="text-warning"></i>
                                                @else
                                                    <i data-feather="star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <p>{{ $review->review }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Meta Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Meta Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Meta Title:</h6>
                                <p>{{ $product->meta_title ?? $product->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Meta Description:</h6>
                                <p>{{ $product->meta_description ?? 'No meta description set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Created:</h6>
                                <p>{{ $product->created_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>Last Updated:</h6>
                                <p>{{ $product->updated_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
