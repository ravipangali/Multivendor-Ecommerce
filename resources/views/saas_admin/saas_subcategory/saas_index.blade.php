@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Subcategories')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Subcategories</h5>
                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Subcategory
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

            <div class="mb-3">
                <form action="{{ route('admin.subcategories.index') }}" method="GET" class="row g-2">
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search subcategories..." name="search" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcategories as $key => $subcategory)
                            <tr>
                                <td>{{ $subcategories->firstItem() + $key }}</td>
                                <td>
                                    @if($subcategory->image)
                                        <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" width="50" height="50" class="img-thumbnail">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $subcategory->name }}</td>
                                <td>{{ $subcategory->slug }}</td>
                                <td>{{ $subcategory->category->name ?? 'None' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" method="POST" class="d-inline">
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
                                <td colspan="8" class="text-center">No subcategories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $subcategories->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
