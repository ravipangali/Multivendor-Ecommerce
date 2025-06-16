@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Edit Order')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Order - {{ $order->order_number }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('seller.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="order_status" class="form-label">Order Status</label>
                    <select class="form-select" id="order_status" name="order_status" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ $order->order_status === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Status</button>
                <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection