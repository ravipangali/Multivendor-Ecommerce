@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Add New Child Category')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Add New Child Category</h5>
                <a href="{{ route('admin.childcategories.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Child Categories
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.childcategories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @livewire('saas-category-subcategory-dropdown', ['categoryId' => old('category_id'), 'sub_category_id' => old('sub_category_id')])

                <div class="mb-3">
                    <label class="form-label">Child Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name') }}" placeholder="Enter child category name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="text-muted">Recommended size: 100x100 pixels</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Child Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
