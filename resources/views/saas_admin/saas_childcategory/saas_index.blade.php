@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Child Categories')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Child Categories</h5>
                <a href="{{ route('admin.childcategories.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Child Category
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
            @endif

            @livewire('saas-category-subcategory-filter', ['categoryId' => request('category'), 'subcategoryId' => request('subcategory'), 'redirectRoute' => 'admin.childcategories.index'])

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($childCategories as $key => $childCategory)
                            <tr>
                                <td>{{ $childCategories->firstItem() + $key }}</td>
                                <td>
                                    @if($childCategory->image)
                                        <img src="{{ asset('storage/' . $childCategory->image) }}" alt="{{ $childCategory->name }}" width="50" height="50" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $childCategory->name }}</td>
                                <td>{{ $childCategory->slug }}</td>
                                <td>{{ $childCategory->subcategory->category->name ?? 'None' }}</td>
                                <td>{{ $childCategory->subcategory->name ?? 'None' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.childcategories.edit', $childCategory->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.childcategories.destroy', $childCategory->id) }}" method="POST" class="d-inline">
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
                                <td colspan="8" class="text-center">No child categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $childCategories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
