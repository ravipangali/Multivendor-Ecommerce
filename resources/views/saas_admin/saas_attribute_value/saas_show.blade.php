@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Attribute Value Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Attribute Value Details</h5>
                <div>
                    <a href="{{ route('admin.attribute-values.edit', $attributeValue->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Value
                    </a>
                    <a href="{{ route('admin.attribute-values.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Values
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Attribute Value Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Attribute:</th>
                            <td>{{ $attributeValue->attribute->name }}</td>
                        </tr>
                        <tr>
                            <th>Value:</th>
                            <td>{{ $attributeValue->value }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $attributeValue->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $attributeValue->updated_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted">Product Variations Using This Value</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attributeValue->productVariations as $key => $variation)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $variation->product_id) }}">
                                                {{ $variation->product->name }}
                                            </a>
                                        </td>
                                        <td>{{ $variation->sku }}</td>
                                        <td>{{ $variation->price }}</td>
                                        <td>{{ $variation->stock }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No product variations use this value.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
