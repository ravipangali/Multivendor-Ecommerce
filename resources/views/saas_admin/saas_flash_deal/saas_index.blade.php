@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Flash Deals')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Flash Deals</h5>
                <a href="{{ route('admin.flash-deals.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Flash Deal
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

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.flash-deals.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search flash deals..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="align-middle" data-feather="search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" action="{{ route('admin.flash-deals.index') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.flash-deals.index') }}" class="btn btn-outline-secondary">
                        <i class="align-middle" data-feather="refresh-cw"></i> Reset
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Banner</th>
                            <th width="25%">Title</th>
                            <th width="20%">Date Range</th>
                            <th width="10%">Status</th>
                            <th width="10%">Products</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashDeals as $key => $flashDeal)
                            <tr>
                                <td>{{ $flashDeals->firstItem() + $key }}</td>
                                <td>
                                    @if($flashDeal->banner_image)
                                        <img src="{{ asset('storage/'.$flashDeal->banner_image) }}" alt="{{ $flashDeal->title }}" width="60" class="img-thumbnail">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                            <i data-feather="image" class="text-muted" style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $flashDeal->title }}</strong>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="text-muted">Start:</div>
                                        <div>{{ $flashDeal->start_time->format('M d, Y') }}</div>
                                        <div>{{ $flashDeal->start_time->format('H:i') }}</div>
                                        <div class="text-muted mt-1">End:</div>
                                        <div>{{ $flashDeal->end_time->format('M d, Y') }}</div>
                                        <div>{{ $flashDeal->end_time->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($flashDeal->end_time < now())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($flashDeal->start_time > now())
                                        <span class="badge bg-warning">Upcoming</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-sm btn-outline-primary">
                                        {{ $flashDeal->products->count() }} Products
                                    </a>
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.flash-deals.show', $flashDeal->id) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.flash-deals.edit', $flashDeal->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-sm btn-success" title="Manage Products">
                                            <i class="align-middle" data-feather="tag"></i>
                                        </a>
                                        <form action="{{ route('admin.flash-deals.destroy', $flashDeal->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Delete">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="align-middle" data-feather="zap" style="font-size: 48px;"></i>
                                        <p class="mt-2">No flash deals found.</p>
                                        <a href="{{ route('admin.flash-deals.create') }}" class="btn btn-primary">Create First Flash Deal</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $flashDeals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-confirm').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this flash deal? This action cannot be undone and will also remove all associated products.')) {
                this.closest('form').submit();
            }
        });
    });

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
