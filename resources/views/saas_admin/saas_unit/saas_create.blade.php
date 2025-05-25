@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Unit')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Unit</h5>
                <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Units
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.units.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Unit Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Examples: Piece, Kg, Liter, Meter, Box, etc.</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Create Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
