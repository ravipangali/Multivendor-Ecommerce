@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Attribute Value')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Attribute Value</h5>
                <a href="{{ route('admin.attribute-values.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Attribute Values
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attribute-values.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="attribute_id" class="form-label">Attribute</label>
                    <select class="form-select @error('attribute_id') is-invalid @enderror" id="attribute_id" name="attribute_id" required>
                        <option value="">Select Attribute</option>
                        @foreach($attributes as $attribute)
                            <option value="{{ $attribute->id }}" {{ old('attribute_id', request('attribute_id')) == $attribute->id ? 'selected' : '' }}>
                                {{ $attribute->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('attribute_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Value</label>
                    <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required>
                    @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create Value</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
