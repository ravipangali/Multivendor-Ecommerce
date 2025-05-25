@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Payment Methods')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Payment Methods</h1>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('seller.payment-methods.create') }}" class="btn btn-primary">Add New Payment Method</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-message">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-message">{{ session('error') }}</div>
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Details</th>
                                <th>Default</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentMethods as $method)
                                <tr>
                                    <td>
                                        @if($method->type == 'bank_transfer')
                                            <span class="badge bg-primary">Bank Transfer</span>
                                        @elseif($method->type == 'esewa')
                                            <span class="badge bg-success">eSewa</span>
                                        @elseif($method->type == 'khalti')
                                            <span class="badge bg-purple">Khalti</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($method->type) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $method->title }}</td>
                                    <td>
                                        @if($method->type == 'bank_transfer')
                                            Bank: {{ $method->bank_name }}<br>
                                            Branch: {{ $method->bank_branch }}<br>
                                            Account: {{ $method->account_number }}
                                        @elseif($method->type == 'esewa' || $method->type == 'khalti')
                                            Mobile: {{ $method->mobile_number }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($method->is_default)
                                            <span class="badge bg-success">Default</span>
                                        @else
                                            <form action="{{ route('seller.payment-methods.set-default', $method) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Set Default</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if($method->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.payment-methods.edit', $method) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('seller.payment-methods.destroy', $method) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payment methods found. <a href="{{ route('seller.payment-methods.create') }}">Add one now</a>.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
