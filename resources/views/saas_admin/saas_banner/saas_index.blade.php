@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Banners')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Banners</h5>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Banner
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $key => $banner)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $banner->title }}</td>
                                <td>
                                    @if($banner->image)
                                        <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title }}" width="100" class="img-thumbnail">
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $banner->position_display_name }}</span>
                                </td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.banners.show', $banner->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center">No banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
