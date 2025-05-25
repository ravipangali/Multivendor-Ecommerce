@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Attribute')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Attribute</h5>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Attributes
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attributes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Attribute Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create Attribute</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
