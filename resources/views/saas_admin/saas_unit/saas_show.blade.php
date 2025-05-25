@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Unit Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Unit Details</h5>
                <div>
                    <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Unit
                    </a>
                    <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Units
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Unit Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Name:</th>
                            <td>{{ $unit->name }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $unit->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $unit->updated_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted">Products Using This Unit</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unit->products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->SKU }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-primary">
                                                <i class="align-middle" data-feather="eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No products are using this unit.</td>
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
