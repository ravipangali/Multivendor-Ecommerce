@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Product Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Product Details</h5>
                <div>
                    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Product
                    </a>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Product Images -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($product->images->count() > 0)
                            <div class="mb-3">
                                <img src="{{ asset('storage/'.$product->images->first()->image_url) }}"
                                    class="img-fluid rounded mb-2"
                                    alt="{{ $product->name }}"
                                    id="main-product-image">
                            </div>
                            @if($product->images->count() > 1)
                            <div class="d-flex justify-content-center flex-wrap">
                                @foreach($product->images as $image)
                                <img src="{{ asset('storage/'.$image->image_url) }}"
                                    class="rounded m-1"
                                    style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                    onclick="document.getElementById('main-product-image').src='{{ asset('storage/'.$image->image_url) }}'"
                                    alt="{{ $product->name }}">
                                @endforeach
                            </div>
                            @endif
                            @else
                            <div class="alert alert-warning">
                                No images available for this product.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Basic Information -->
                <div class="col-md-7">
                    <div class="card">
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

                            @if($product->has_variations)
                                @php
                                    $variations = $product->variations;
                                    $minPrice = $variations->min('price');
                                    $maxPrice = $variations->max('price');
                                    $totalStock = $variations->sum('stock');
                                @endphp
                                <div class="fs-5 fw-bold mb-3">
                                    @if($minPrice === $maxPrice)
                                        Rs{{ number_format($minPrice, 2) }}
                                    @else
                                        Rs{{ number_format($minPrice, 2) }} - Rs{{ number_format($maxPrice, 2) }}
                                    @endif
                                </div>

                                <p class="mb-1">
                                    <span class="fw-bold">Total Stock:</span>
                                    <span class="{{ $totalStock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $totalStock }} {{ $totalStock > 0 ? 'in stock' : 'out of stock' }}
                                    </span>
                                </p>
                            @else
                                <div class="mb-3">
                                    @if($product->discount > 0)
                                    <span class="text-decoration-line-through text-muted fs-5">Rs{{ number_format($product->price, 2) }}</span>
                                    <span class="text-danger fs-4 fw-bold">Rs{{ number_format($product->final_price, 2) }}</span>
                                    @if($product->discount_type == 'percentage')
                                    <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                                    @else
                                    <span class="badge bg-danger">Rs{{ number_format($product->discount, 2) }} OFF</span>
                                    @endif
                                    @else
                                    <p class="mb-1 fs-4 fw-bold">Rs{{ number_format($product->price, 2) }}</p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <span class="fw-bold">SKU:</span>
                                    <p class="mb-1">{{ $product->SKU }}</p>
                                </div>

                                <div class="mb-3">
                                    <span class="fw-bold">Stock:</span>
                                    @if($product->stock > 0)
                                    <p class="mb-1 text-success">{{ $product->stock }} in stock</p>
                                    @else
                                    <p class="mb-1 text-danger">Out of stock</p>
                                    @endif
                                </div>
                            @endif

                            <div class="mb-3">
                                <span class="fw-bold">Short Description:</span>
                                <p class="mb-1">{{ $product->short_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Categories & Brand -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organization & Classification</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="fw-bold">Category:</span>
                                    <p>{{ $product->category->name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <span class="fw-bold">Subcategory:</span>
                                    <p>{{ $product->subcategory->name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <span class="fw-bold">Child Category:</span>
                                    <p>{{ $product->childCategory->name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <span class="fw-bold">Brand:</span>
                                    <p>{{ $product->brand->name ?? 'N/A' }}</p>
                                </div>

                                <div class="col-md-4">
                                    <span class="fw-bold">Unit:</span>
                                    <p>{{ $product->unit->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Full Description -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Full Description</h5>
                        </div>
                        <div class="card-body">
                            <div class="product-description">
                                {{ $product->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variations (if applicable) -->
            @if($product->has_variations && $product->variations->count() > 0)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Product Variations</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
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
                </div>
            </div>
            @endif

            <!-- Product Information -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="fw-bold">Created At:</span>
                                    <p>{{ $product->created_at->format('F d, Y h:i A') }}</p>
                                </div>

                                <div class="col-md-4">
                                    <span class="fw-bold">Last Updated:</span>
                                    <p>{{ $product->updated_at->format('F d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
