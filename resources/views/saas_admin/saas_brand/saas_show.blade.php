@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Brand Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Brand Details</h5>
                <div>
                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Brand
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Brands
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
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Brand Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 text-center">
                                @if($brand->image)
                                    <img src="{{ asset('storage/'.$brand->image) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 150px;">
                                @else
                                    <div class="border p-5 text-center bg-light mb-3">
                                        <i data-feather="image" style="width: 64px; height: 64px;"></i>
                                        <p class="mt-2">No logo image available</p>
                                    </div>
                                @endif
                            </div>

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Name</th>
                                        <td>{{ $brand->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td>{{ $brand->slug }}</td>
                                    </tr>
                                    <tr>
                                        <th>Products Count</th>
                                        <td>
                                            <span class="badge bg-info">{{ $brand->products()->count() }} products</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $brand->created_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $brand->updated_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Products</h5>
                            <a href="{{ route('admin.products.index') }}?brand_id={{ $brand->id }}" class="btn btn-sm btn-primary">
                                View All Products
                            </a>
                        </div>
                        <div class="card-body">
                            @if($brand->products()->count() > 0)
                                <div class="list-group">
                                    @foreach($brand->products()->latest()->take(5)->get() as $product)
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @if($product->images->count() > 0)
                                                        <img src="{{ asset($product->images->first()->image_url) }}"
                                                             alt="{{ $product->name }}"
                                                             class="rounded me-3"
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 50px; height: 50px;">
                                                            <i data-feather="box" class="text-muted"></i>
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                                        <small>SKU: {{ $product->SKU }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success">Rs {{ number_format($product->price, 2) }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    This brand doesn't have any products yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-end mt-3">
                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Brand
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
