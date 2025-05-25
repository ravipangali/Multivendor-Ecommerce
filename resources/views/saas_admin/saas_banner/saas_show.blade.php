@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Banner Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Banner Details</h5>
                <div>
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Banner
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Banners
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    @if($banner->image)
                        <div class="mb-3">
                            <h6 class="text-muted">Banner Image</h6>
                            <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title }}" class="img-fluid" style="max-height: 300px;">
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No image available for this banner.
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Banner Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">Title</th>
                            <td>{{ $banner->title }}</td>
                        </tr>
                        <tr>
                            <th>Link URL</th>
                            <td>
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank">{{ $banner->link_url }} <i class="align-middle" data-feather="external-link"></i></a>
                                @else
                                    <span class="text-muted">No link specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($banner->position) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($banner->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $banner->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $banner->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Banner
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
