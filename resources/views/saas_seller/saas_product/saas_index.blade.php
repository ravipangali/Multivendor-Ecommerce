@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Products')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">My Products</h5>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Product
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ $product->images->first()->image_url }}"
                                            alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div style="width: 50px; height: 50px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                                            <i class="align-middle" data-feather="image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $product->name }}
                                    @if($product->has_variations)
                                        <span class="badge bg-info">Variations</span>
                                    @endif
                                </td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($product->has_variations)
                                        @php
                                            $variations = $product->variations;
                                            $minPrice = $variations->min('price');
                                            $maxPrice = $variations->max('price');
                                        @endphp
                                                                            @if($minPrice === $maxPrice)
                                        Rs {{ number_format($minPrice, 2) }}
                                    @else
                                        Rs {{ number_format($minPrice, 2) }} - Rs {{ number_format($maxPrice, 2) }}
                                    @endif
                                    @else
                                        @if($product->discount > 0)
                                                                                    <span class="text-decoration-line-through text-muted">Rs {{ number_format($product->price, 2) }}</span>
                                        <span class="text-danger">Rs {{ number_format($product->final_price, 2) }}</span>
                                        @else
                                            Rs {{ number_format($product->price, 2) }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($product->has_variations)
                                        @php $totalStock = $product->variations->sum('stock'); @endphp
                                        {{ $totalStock }}
                                    @else
                                        {{ $product->stock }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->seller_publish_status === 'request_product')
                                        <span class="badge bg-warning">
                                            <i class="align-middle" data-feather="clock" style="width: 12px; height: 12px;"></i>
                                            Pending Approval
                                        </span>
                                    @elseif($product->seller_publish_status === 'approved_product')
                                        <span class="badge bg-success">
                                            <i class="align-middle" data-feather="check-circle" style="width: 12px; height: 12px;"></i>
                                            Approved
                                        </span>
                                    @elseif($product->seller_publish_status === 'denied_product')
                                        <span class="badge bg-danger">
                                            <i class="align-middle" data-feather="x-circle" style="width: 12px; height: 12px;"></i>
                                            Denied
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('seller.products.show', $product->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        @if($product->seller_publish_status !== 'approved_product')
                                            <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger delete-product"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}">
                                            <i class="align-middle" data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete product confirmation with SweetAlert
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete product "${productName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('seller.products.index') }}/${productId}`;
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
