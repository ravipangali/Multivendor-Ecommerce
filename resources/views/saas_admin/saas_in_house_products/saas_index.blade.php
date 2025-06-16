@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'In-House Products')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">In-House Products Management</h5>
                        <a href="{{ route('admin.in-house-products.create') }}" class="btn btn-primary">
                            <i data-feather="plus"></i> Add New In-House Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Total Products</h6>
                                            <h3 class="mb-0 text-white">{{ $totalProducts }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="package" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Active Products</h6>
                                            <h3 class="mb-0 text-white">{{ $activeProducts }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="check-circle" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Low Stock Products</h6>
                                            <h3 class="mb-0 text-white">{{ $lowStockProducts }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="alert-triangle" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.in-house-products.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="category_id">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="brand_id">
                                        <option value="">All Brands</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-1">
                                    <a href="{{ route('admin.in-house-products.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if($product->images->count() > 0)
                                            <img src="{{ $product->images->first()->image_url }}"
                                                 alt="{{ $product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 class="rounded"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/no-image.svg') }}';this.style.backgroundColor='#f8f9fa';this.style.border='1px solid #dee2e6';">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded border" style="width: 50px; height: 50px;">
                                                <i data-feather="image" class="text-muted" style="width: 20px; height: 20px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->product_type === 'Digital')
                                                <span class="badge bg-info ms-1">Digital</span>
                                            @endif
                                            @if($product->is_featured)
                                                <span class="badge bg-warning ms-1">Featured</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ Str::limit($product->short_description, 50) }}</small>
                                    </td>
                                    <td>
                                        <code>{{ $product->SKU }}</code>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>
                                        <div>
                                            Rs {{ number_format($product->price, 2) }}
                                            @if($product->discount > 0)
                                                <br><small class="text-success">
                                                    {{ $product->discount_type === 'percentage' ? $product->discount . '%' : 'Rs ' . $product->discount }} off
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->stock <= 10 ? 'bg-danger' : ($product->stock <= 50 ? 'bg-warning' : 'bg-success') }}">
                                            {{ $product->product_type === 'Digital' ? '∞' : number_format($product->stock) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.in-house-products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('admin.in-house-products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i data-feather="edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-product"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    title="Delete">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i data-feather="package" class="icon-lg mb-2"></i>
                                            <p class="mb-0">No in-house products found</p>
                                            <a href="{{ route('admin.in-house-products.create') }}" class="btn btn-primary btn-sm mt-2">
                                                Add Your First In-House Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete product confirmation
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${productName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('admin.in-house-products.index') }}/${productId}`;
                    form.submit();
                }
            });
        });
    });

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
