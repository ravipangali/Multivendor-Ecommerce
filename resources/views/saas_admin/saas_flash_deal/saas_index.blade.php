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
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Banner</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashDeals as $key => $flashDeal)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $flashDeal->title }}</td>
                                <td>
                                    @if($flashDeal->banner_image)
                                        <img src="{{ asset('storage/'.$flashDeal->banner_image) }}" alt="{{ $flashDeal->title }}" width="100" class="img-thumbnail">
                                    @else
                                        <span class="badge bg-secondary">No Banner</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">Start: {{ $flashDeal->start_time->format('M d, Y H:i') }}</span><br>
                                    <span class="badge bg-secondary">End: {{ $flashDeal->end_time->format('M d, Y H:i') }}</span>
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
                                    <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-sm btn-primary">
                                        {{ $flashDeal->products->count() }} Products
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.flash-deals.show', $flashDeal->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.flash-deals.edit', $flashDeal->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.flash-deals.destroy', $flashDeal->id) }}" method="POST" class="d-inline">
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
                                <td colspan="7" class="text-center">No flash deals found.</td>
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
