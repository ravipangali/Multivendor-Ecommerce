<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

    <title>Admin Dashboard</title>
    @yield('styles')
    @livewireStyles

    <link href="{{ asset('saas_admin/css/my.css') }}" rel="stylesheet">
    <link href="{{ asset('saas_admin/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="wrapper">

        @include('saas_admin.saas_layouts.saas_sidebar')
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown"
                                data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="bell"></i>
                                    <span class="indicator">4</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0"
                                aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    4 New Notifications
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-danger" data-feather="alert-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Update completed</div>
                                                <div class="text-muted small mt-1">Restart server 12 to complete the
                                                    update.</div>
                                                <div class="text-muted small mt-1">30m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-feather="bell"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Lorem ipsum</div>
                                                <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate
                                                    hendrerit et.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-feather="home"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Login from 192.186.1.8</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">New connection</div>
                                                <div class="text-muted small mt-1">Christina accepted your request.
                                                </div>
                                                <div class="text-muted small mt-1">14h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li> --}}
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown"
                                data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0"
                                aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        4 New Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="{{ asset('saas_admin/img/avatars/avatar-5.jpg') }}"
                                                    class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Vanessa Tucker</div>
                                                <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis
                                                    arcu tortor.</div>
                                                <div class="text-muted small mt-1">15m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="{{ asset('saas_admin/img/avatars/avatar-2.jpg') }}"
                                                    class="avatar img-fluid rounded-circle" alt="William Harris">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">William Harris</div>
                                                <div class="text-muted small mt-1">Curabitur ligula sapien euismod
                                                    vitae.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="{{ asset('saas_admin/img/avatars/avatar-4.jpg') }}"
                                                    class="avatar img-fluid rounded-circle" alt="Christina Mason">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Christina Mason</div>
                                                <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.
                                                </div>
                                                <div class="text-muted small mt-1">4h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="{{ asset('saas_admin/img/avatars/avatar-3.jpg') }}"
                                                    class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div class="text-dark">Sharon Lessman</div>
                                                <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed,
                                                    posuere ac, mattis non.</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all messages</a>
                                </div>
                            </div>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                                data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown">
                                <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}"
                                    class="avatar rounded-circle img-fluid me-1" alt="avatar" /> <span
                                    class="text-dark">{{ request()->user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- <a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1"
                                        data-feather="user"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1"
                                        data-feather="pie-chart"></i> Analytics</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="index.html"><i class="align-middle me-1"
                                        data-feather="settings"></i> Settings & Privacy</a>
                                <a class="dropdown-item" href="#"><i class="align-middle me-1"
                                        data-feather="help-circle"></i> Help Center</a>
                                <div class="dropdown-divider"></div> --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">@yield('title')</h1>

                    <div class="row">
                        @yield('content')
                    </div>

                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="https://ravipangali.com.np/" target="_blank"><strong>Ravi
                                        Pangali</strong></a> Multi Vendor
                                        Ecommerce &copy;
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('saas_admin/js/app.js') }}"></script>
    @livewireScripts
    @yield('scripts')

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')
    <script src="{{ asset('saas_admin/js/delete-confirm.js') }}"></script>

    @if(View::exists('saas_admin.saas_layouts.saas_layout'))
        @yield('styles')

        <!-- Add professional design enhancements -->
        <style>
            :root {
                --primary-color: #3b7ddd;
                --primary-hover: #2d62b2;
                --secondary-color: #6c757d;
                --success-color: #28a745;
                --danger-color: #dc3545;
                --warning-color: #ffc107;
                --info-color: #17a2b8;
                --light-color: #f8f9fa;
                --dark-color: #343a40;
                --border-color: #dee2e6;
                --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
                --transition: all 0.3s ease;
                --border-radius: 0.375rem;
            }

            /* Global enhancements */
            .card {
                border: none;
                box-shadow: var(--shadow-sm);
                transition: var(--transition);
                border-radius: var(--border-radius);
            }

            .card:hover {
                box-shadow: var(--shadow);
            }

            .card-header {
                background-color: white;
                border-bottom: 1px solid var(--border-color);
                padding: 1rem 1.5rem;
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-primary:hover {
                background-color: var(--primary-hover);
                border-color: var(--primary-hover);
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(59, 125, 221, 0.25);
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .form-label {
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            /* Settings specific enhancements */
            .settings-nav .list-group-item {
                border-radius: 0;
                border-left: 3px solid transparent;
                padding: 1rem 1.25rem;
                font-weight: 500;
            }

            .settings-nav .list-group-item.active {
                background-color: rgba(59, 125, 221, 0.1);
                border-left: 3px solid var(--primary-color);
                color: var(--primary-color);
            }

            .settings-nav .list-group-item:hover:not(.active) {
                background-color: rgba(59, 125, 221, 0.05);
            }

            .form-section-title {
                font-weight: 600;
                color: var(--primary-color);
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 0.75rem;
                margin-bottom: 1.5rem;
            }

            /* Toast notifications */
            .toast-container {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 9999;
            }

            .toast {
                background-color: white;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow);
                margin-bottom: 0.75rem;
                min-width: 250px;
            }

            .toast-header {
                border-bottom: 1px solid var(--border-color);
                padding: 0.5rem 1rem;
            }

            .toast-body {
                padding: 1rem;
            }
        </style>
    @endif

    @if(View::exists('saas_admin.saas_layouts.saas_layout'))
        @yield('scripts')

        <!-- Add professional functionality enhancements -->
        <script>
            // Initialize all tooltips
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Feather icons if available
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }

                // Initialize any tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Initialize any popovers
                const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                });

                // Add form validation styles
                const forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });

                // Toast notification function
                window.showToast = function(title, message, type = 'success') {
                    // Create toast container if it doesn't exist
                    let toastContainer = document.querySelector('.toast-container');
                    if (!toastContainer) {
                        toastContainer = document.createElement('div');
                        toastContainer.className = 'toast-container';
                        document.body.appendChild(toastContainer);
                    }

                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = 'toast show';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');

                    // Set toast color based on type
                    let bgColor = '#28a745'; // success
                    if (type === 'error') bgColor = '#dc3545';
                    if (type === 'warning') bgColor = '#ffc107';
                    if (type === 'info') bgColor = '#17a2b8';

                    // Create toast header
                    const toastHeader = document.createElement('div');
                    toastHeader.className = 'toast-header';
                    toastHeader.style.borderLeft = `4px solid ${bgColor}`;

                    const toastTitle = document.createElement('strong');
                    toastTitle.className = 'me-auto';
                    toastTitle.textContent = title;

                    const closeButton = document.createElement('button');
                    closeButton.type = 'button';
                    closeButton.className = 'btn-close';
                    closeButton.setAttribute('data-bs-dismiss', 'toast');
                    closeButton.setAttribute('aria-label', 'Close');
                    closeButton.addEventListener('click', function() {
                        toast.remove();
                    });

                    toastHeader.appendChild(toastTitle);
                    toastHeader.appendChild(closeButton);

                    // Create toast body
                    const toastBody = document.createElement('div');
                    toastBody.className = 'toast-body';
                    toastBody.textContent = message;

                    // Assemble toast
                    toast.appendChild(toastHeader);
                    toast.appendChild(toastBody);

                    // Add to container
                    toastContainer.appendChild(toast);

                    // Auto-remove after 5 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 5000);
                };

                // Handle AJAX form submissions
                const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
                ajaxForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(form);
                        const submitBtn = form.querySelector('[type="submit"]');
                        const originalBtnText = submitBtn.innerHTML;

                        // Show loading state
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

                        fetch(form.action, {
                            method: form.method,
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;

                            // Show response
                            if (data.success) {
                                showToast('Success', data.message || 'Operation completed successfully', 'success');
                            } else {
                                showToast('Error', data.message || 'An error occurred', 'error');
                            }
                        })
                        .catch(error => {
                            // Reset button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;

                            // Show error
                            showToast('Error', 'An unexpected error occurred', 'error');
                            console.error('Error:', error);
                        });
                    });
                });
            });
        </script>
    @endif

</body>

</html>
