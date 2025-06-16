<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('seller.dashboard') }}">
            <span class="align-middle">Seller Dashboard</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Dashboard
            </li>

            <li class="sidebar-item {{ request()->is('seller/dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span
                        class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Profile
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.profile') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.profile') }}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">My Profile</span>
                </a>
            </li>

            <li class="sidebar-header">
                Products
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.products.*') && !request()->routeIs('seller.products.images.*') && !request()->routeIs('seller.products.variations.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.products.index') }}">
                    <i class="align-middle" data-feather="box"></i> <span class="align-middle">Products</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.reviews.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.reviews.index') }}">
                    <i class="align-middle" data-feather="star"></i> <span class="align-middle">Product Reviews</span>
                </a>
            </li>

            <li class="sidebar-header">
                Orders
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.index') }}">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">All Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.pending') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.pending') }}">
                    <i class="align-middle" data-feather="clock"></i> <span class="align-middle">Pending Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.processing') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.processing') }}">
                    <i class="align-middle" data-feather="refresh-cw"></i> <span class="align-middle">Processing Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.shipped') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.shipped') }}">
                    <i class="align-middle" data-feather="package"></i> <span class="align-middle">Shipped Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.delivered') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.delivered') }}">
                    <i class="align-middle" data-feather="check-circle"></i> <span class="align-middle">Delivered Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.orders.cancelled') || request()->routeIs('seller.orders.refunded') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.orders.cancelled') }}">
                    <i class="align-middle" data-feather="x-circle"></i> <span class="align-middle">Cancelled/Refunded</span>
                </a>
            </li>

            <li class="sidebar-header">
                Marketing
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.coupons.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.coupons.index') }}">
                    <i class="align-middle" data-feather="tag"></i> <span class="align-middle">Coupons</span>
                </a>
            </li>



            <li class="sidebar-header">
                Financial
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.payment-methods.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.payment-methods.index') }}">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payment Methods</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.withdrawals.index') || request()->routeIs('seller.withdrawals.create') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.withdrawals.index') }}">
                    <span class="rs-icon align-middle">Rs</span> <span class="align-middle" style="padding-left: 0.5rem;">Withdraw</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.withdrawals.history') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.withdrawals.history') }}">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Withdraw History</span>
                </a>
            </li>

            <li class="sidebar-header">
                Reports
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.reports.sales') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.reports.sales') }}">
                    <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Sales Report</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.reports.product') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.reports.product') }}">
                    <i class="align-middle" data-feather="pie-chart"></i> <span class="align-middle">Product Report</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('seller.reports.customer') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('seller.reports.customer') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer Report</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
