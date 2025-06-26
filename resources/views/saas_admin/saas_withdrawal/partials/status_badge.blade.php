@php
    $badgeClass = '';
    switch ($status) {
        case 'pending':
            $badgeClass = 'bg-warning';
            break;
        case 'approved':
            $badgeClass = 'bg-success';
            break;
        case 'processing':
            $badgeClass = 'bg-info';
            break;
        case 'completed':
            $badgeClass = 'bg-primary';
            break;
        case 'rejected':
            $badgeClass = 'bg-danger';
            break;
        case 'cancelled':
            $badgeClass = 'bg-secondary';
            break;
        default:
            $badgeClass = 'bg-light text-dark';
            break;
    }
@endphp

<span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
