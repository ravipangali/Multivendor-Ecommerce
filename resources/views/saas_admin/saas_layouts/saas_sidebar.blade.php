<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <span class="align-middle">Admin Dashboard</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Dashboard
            </li>

            <li class="sidebar-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <i class="align-middle" data-feather="sliders"></i> <span
                        class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Catalog
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.categories.index') }}">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Categories</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.subcategories.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.subcategories.index') }}">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Sub Categories</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.childcategories.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.childcategories.index') }}">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Child Categories</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.brands.index') }}">
                    <i class="align-middle" data-feather="bookmark"></i> <span class="align-middle">Brands</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.attributes.index') }}">
                    <i class="align-middle" data-feather="tag"></i> <span class="align-middle">Attributes</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.attribute-values.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.attribute-values.index') }}">
                    <i class="align-middle" data-feather="tag"></i> <span class="align-middle">Attribute Values</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.units.index') }}">
                    <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Units</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.products.index') }}">
                    <i class="align-middle" data-feather="box"></i> <span class="align-middle">Products</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.product-reviews.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.product-reviews.index') }}">
                    <i class="align-middle" data-feather="star"></i> <span class="align-middle">Product Reviews</span>
                </a>
            </li>

            <li class="sidebar-header">
                Marketing
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.banners.index') }}">
                    <i class="align-middle" data-feather="image"></i> <span class="align-middle">Banners</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.flash-deals.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.flash-deals.index') }}">
                    <i class="align-middle" data-feather="zap"></i> <span class="align-middle">Flash Deals</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.coupons.index') }}">
                    <i class="align-middle" data-feather="tag"></i> <span class="align-middle">Coupons</span>
                </a>
            </li>

            <li class="sidebar-header">
                Sales
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.orders.index') }}">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Orders</span>
                </a>
            </li>

            <li class="sidebar-header">
                Users
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Administrators</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.customers.index') }}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Customers</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.sellers.index') }}">
                    <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Sellers</span>
                </a>
            </li>

            <li class="sidebar-header">
                Reports
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reports.sales') }}">
                    <i class="align-middle" data-feather="dollar-sign"></i> <span class="align-middle">Sales Report</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.reports.products') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reports.products') }}">
                    <i class="align-middle" data-feather="package"></i> <span class="align-middle">Product Report</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.reports.customers') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reports.customers') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer Report</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.reports.sellers') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reports.sellers') }}">
                    <i class="align-middle" data-feather="user-check"></i> <span class="align-middle">Seller Report</span>
                </a>
            </li>

            <li class="sidebar-header">
                Settings
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.index') }}">
                    <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Settings Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.general') }}">
                    <i class="align-middle" data-feather="settings"></i> <span class="align-middle">General Settings</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.email') }}">
                    <i class="align-middle" data-feather="mail"></i> <span class="align-middle">Email Settings</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.payment') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.payment') }}">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payment Settings</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.shipping') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.shipping') }}">
                    <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Shipping Settings</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.settings.tax') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.tax') }}">
                    <i class="align-middle" data-feather="percent"></i> <span class="align-middle">Tax Settings</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
