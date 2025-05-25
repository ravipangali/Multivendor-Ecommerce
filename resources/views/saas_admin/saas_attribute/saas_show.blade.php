@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Attribute Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Attribute Details</h5>
                <div>
                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Attribute
                    </a>
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Attributes
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Attribute Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Name:</th>
                            <td>{{ $attribute->name }}</td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $attribute->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $attribute->updated_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted">Attribute Values</h6>
                        <a href="{{ route('admin.attribute-values.create', ['attribute_id' => $attribute->id]) }}" class="btn btn-sm btn-primary">
                            <i class="align-middle" data-feather="plus"></i> Add Value
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Value</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attribute->values as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->value }}</td>
                                        <td>{{ $value->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.attribute-values.edit', $value->id) }}" class="btn btn-sm btn-info">
                                                    <i class="align-middle" data-feather="edit"></i>
                                                </a>
                                                <form action="{{ route('admin.attribute-values.destroy', $value->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                        <i class="align-middle" data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No values found for this attribute.</td>
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
