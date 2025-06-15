<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="keywords"
    content="auto parts, baby store, ecommerce, electronics, fashion, food, marketplace, modern, multi vendor, multipurpose, organic, responsive, shop, shopping, store">
  <meta name="description" content="AllSewa - Multivendor Online Shopping Platform">
  <meta name="unlockdesign" content="SaniUlHassan">
  <!-- css file -->
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/ace-responsive-menu.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/menu.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/flaticon.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/bootstrap-select.min.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/slider.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/custom.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <!-- Enhanced Theme Styles -->
  <style>
    :root {
      --primary-color: #abcf37;
      --primary-light: #c4e04a;
      --primary-dark: #8fb12d;
      --secondary-color: #09717e;
      --secondary-light: #1a8d9a;
      --secondary-dark: #075a64;
      --accent-color: #f8fafc;
      --text-dark: #1a202c;
      --text-medium: #4a5568;
      --text-light: #718096;
      --text-muted: #a0aec0;
      --border-light: #e2e8f0;
      --border-medium: #cbd5e0;
      --white: #ffffff;
      --success: #48bb78;
      --warning: #ed8936;
      --danger: #f56565;
      --info: #4299e1;
      --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --radius-sm: 0.375rem;
      --radius-md: 0.5rem;
      --radius-lg: 0.75rem;
      --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      --font-display: 'Playfair Display', serif;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: var(--font-primary);
      color: var(--text-dark);
      line-height: 1.6;
      font-weight: 400;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: var(--font-display);
      font-weight: 600;
      line-height: 1.3;
      color: var(--text-dark);
    }

    .btn-primary, .btn-thm {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: var(--white);
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius-md);
      transition: all 0.2s ease;
      border: none;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-primary:hover, .btn-thm:hover {
      background-color: var(--primary-dark);
      border-color: var(--primary-dark);
      color: var(--white);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .btn-secondary {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
      color: var(--white);
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius-md);
      transition: all 0.2s ease;
    }

    .btn-secondary:hover {
      background-color: var(--secondary-dark);
      border-color: var(--secondary-dark);
      color: var(--white);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .btn-outline-primary {
      background-color: transparent;
      border: 1px solid var(--primary-color);
      color: var(--primary-color);
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius-md);
      transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: var(--white);
    }

    .card {
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-sm);
      transition: all 0.2s ease;
      background: var(--white);
    }

    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    .form-control, .form-select {
      border: 1px solid var(--border-medium);
      border-radius: var(--radius-md);
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      transition: all 0.2s ease;
      background: var(--white);
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
      outline: none;
    }

    .badge {
      font-weight: 500;
      padding: 0.375rem 0.75rem;
      border-radius: var(--radius-sm);
      font-size: 0.75rem;
    }

    .badge-primary {
      background-color: var(--primary-color);
      color: var(--white);
    }

    .badge-secondary {
      background-color: var(--secondary-color);
      color: var(--white);
    }

    .badge-success {
      background-color: var(--success);
      color: var(--white);
    }

    .badge-warning {
      background-color: var(--warning);
      color: var(--white);
    }

    .badge-danger {
      background-color: var(--danger);
      color: var(--white);
    }

    .text-primary {
      color: var(--primary-color) !important;
    }

    .text-secondary {
      color: var(--secondary-color) !important;
    }

    .text-muted {
      color: var(--text-muted) !important;
    }

    .bg-light {
      background-color: var(--accent-color) !important;
    }

    .border {
      border: 1px solid var(--border-light) !important;
    }

    .shadow-sm {
      box-shadow: var(--shadow-sm) !important;
    }

    .shadow {
      box-shadow: var(--shadow-md) !important;
    }

    .rounded {
      border-radius: var(--radius-md) !important;
    }

    .rounded-lg {
      border-radius: var(--radius-lg) !important;
    }

    /* Header Improvements */
    .header_top {
      background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
      color: var(--white);
    }

    .header_middle {
      background: var(--white);
      border-bottom: 1px solid var(--border-light);
      box-shadow: var(--shadow-sm);
    }

    .main-menu {
      background: var(--white);
      border-bottom: 1px solid var(--border-light);
    }

    /* Product Cards */
    .shop_item {
      border: 1px solid var(--border-light);
      border-radius: var(--radius-lg);
      background: var(--white);
      transition: all 0.3s ease;
      overflow: hidden;
    }

    .shop_item:hover {
      box-shadow: var(--shadow-lg);
      transform: translateY(-4px);
      border-color: var(--primary-color);
    }

    .shop_item .details {
      padding: 1.5rem;
    }

    .shop_item .title a {
      color: var(--text-dark);
      font-weight: 600;
      font-size: 1rem;
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .shop_item .title a:hover {
      color: var(--primary-color);
    }

    .shop_item .sub_title {
      color: var(--text-light);
      font-size: 0.875rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }

    .shop_item .price {
      font-weight: 700;
      font-size: 1.125rem;
      color: var(--secondary-color);
    }

    /* Breadcrumb */
    .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
    }

    .breadcrumb-item a {
      color: var(--text-medium);
      text-decoration: none;
      transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
      color: var(--primary-color);
    }

    .breadcrumb-item.active {
      color: var(--text-dark);
      font-weight: 500;
    }

    /* Footer */
    .footer_one {
      background: linear-gradient(135deg, var(--text-dark), #2d3748);
      color: var(--white);
    }

    .footer-links li a {
      color: var(--text-muted);
      transition: color 0.2s ease;
    }

    .footer-links li a:hover {
      color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
      }

      h1 { font-size: 1.875rem; }
      h2 { font-size: 1.5rem; }
      h3 { font-size: 1.25rem; }
      h4 { font-size: 1.125rem; }
      h5 { font-size: 1rem; }
      h6 { font-size: 0.875rem; }
    }

    /* Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .fade-in-up {
      animation: fadeInUp 0.6s ease-out;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: var(--accent-color);
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-dark);
    }
  </style>
  <!-- Responsive stylesheet -->
  <link rel="stylesheet" href="{{ asset('saas_frontend/css/responsive.css') }}">
  <!-- Title -->
  <title>Allsewa</title>
  <!-- Favicon -->
  <link href="{{ asset('saas_frontend/images/favicon.ico') }}" sizes="128x128" rel="shortcut icon" type="image/x-icon" />
  <link href="{{ asset('saas_frontend/images/favicon.ico') }}" sizes="128x128" rel="shortcut icon" />
  <!-- Apple Touch Icon -->
  <link href="{{ asset('saas_frontend/images/apple-touch-icon-60x60.png') }}" sizes="60x60" rel="apple-touch-icon">
  <link href="{{ asset('saas_frontend/images/apple-touch-icon-72x72.png') }}" sizes="72x72" rel="apple-touch-icon">
  <link href="{{ asset('saas_frontend/images/apple-touch-icon-114x114.png') }}" sizes="114x114" rel="apple-touch-icon">
  <link href="{{ asset('saas_frontend/images/apple-touch-icon-180x180.png') }}" sizes="180x180" rel="apple-touch-icon">
  @stack('styles')
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body data-spy="scroll">
  <div class="wrapper ovh">
    <div class="preloader"></div>

    <!-- header Top -->
    <div class="header_top bb1 pt5 dn-992">
      <div class="container">
        <div class="d-flex align-items-center justify-content-center">
          <p class="mb-0 text-center">
            <strong>Sell your any products & service</strong> in your own price directly to the consumer.
            <strong>Buy any products & services</strong> from producer or skilled service provider
          </p>
        </div>
      </div>
    </div>

    <!-- header middle -->
    <div class="header_middle home3_style dn-992">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-1 col-xxl-1">
            <div class="header_top_logo_home3">
              <div class="logo">
                <a href="{{ route('customer.home') }}">
                  <img src="{{ asset('saas_frontend/img/logo.png') }}" alt="" class="img-fluid">
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-xxl-3">
            <div class="header_middle_advnc_search mb-xxl-0">
              <div class="search_form_wrapper home7_style athome8">
                <div class="top-search home7_style athome8">
                  <form action="{{ route('customer.search') }}" method="get" class="form-search" accept-charset="utf-8">
                    <div class="box-search pre_line before_none">
                      <input class="form_control" type="text" name="q" placeholder="Search products…">
                      <div class="advscrh_frm_btn home7_style">
                        <button type="submit" class="btn search-btn"><span class="flaticon-search"></span></button>
                      </div>
                    </div>
                    <!-- /.box-search -->
                  </form>
                  <!-- /.form-search -->
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7 col-xxl-8 align-self-center">
            <div class="text-center text-xl-end">

              <div class="wrapper d-flex align-items-center justify-content-end">
                <div class="dropdown has-border ps-3 pe-3">
                  <button class="btn dropdown-toggle" type="button" id="dropdownMenu3" data-bs-toggle="dropdown"
                    aria-expanded="false"> Language</button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">English</a></li>
                    <li><a class="dropdown-item" href="#">Nepali</a></li>
                  </ul>
                </div>
                @guest
                <a href="{{ route('login') }}" class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                  <div class="d-block d-flex align-items-center">
                    <div class="details">
                      <h5 class="title">Log In</h5>
                    </div>
                  </div>
                </a>
                <a href="{{ route('register') }}" class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                  <div class="d-block d-flex align-items-center">
                    <div class="details">
                      <h5 class="title">Register</h5>
                    </div>
                  </div>
                </a>
                @else
                <a href="{{ route('customer.dashboard') }}" class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                  <div class="d-block d-flex align-items-center">
                    <div class="details">
                      <h5 class="title">My Account</h5>
                    </div>
                  </div>
                </a>
                @endguest
                <a href="{{ route('customer.cart') }}" class="header_iconbox_home3_style athome8 has-border">
                  <div class="d-block d-flex align-items-center">
                    <div class="details ms-2">
                      <h5 class="title">Cart</h5>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Header Nav -->
    <header class="header-nav menu_style_home_one home8_style main-menu">
      <!-- Ace Responsive Menu -->
      <nav class="posr">
        <div class="container posr menu_bdrt1">
          <!-- Menu Toggle btn-->
          <div class="menu-toggle">
            <button type="button" id="menu-btn"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                class="icon-bar"></span> </button>
          </div>
          <div class="posr logo1">
            @include('saas_customer.saas_layout.saas_partials.saas_mega_menu')
          </div>
          <!-- Responsive Menu Structure-->
          <ul id="respMenu" class="ace-responsive-menu menu_list_custom_code wa pl200" data-menu-style="horizontal">
            <li class=""> <a href="{{ route('customer.home') }}"><span class="title">All</span></a> </li>
            <li class=""> <a href="{{ route('customer.products') }}"><span class="title">Products</span></a> </li>
            <li class=""> <a href="{{ route('customer.brands') }}"><span class="title">Brands</span></a> </li>
            <li class=""> <a href="{{ route('customer.sellers') }}"><span class="title">Sellers</span></a> </li>
            <li class=""> <a href="{{ route('customer.blog.index') }}"><span class="title">Blog</span></a> </li>
            <li class="dropdown-submenu">
              <a href="#"><span class="title">More</span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ route('customer.about') }}">About Us</a></li>
                <li><a href="{{ route('customer.contact') }}">Contact Us</a></li>
              </ul>
            </li>
          </ul>
          <div class="widget_menu_home2">
            <p class="is-marquee">
              <span>Shop the best of Nepal at All Sewa Nepal.</span>
              <span>Sell, Server & Earn</span>
            </p>
          </div>
        </div>
      </nav>
    </header>

    <!-- Body Ovelay Behind Sidebar -->
    <div class="hiddenbar-body-ovelay"></div>


    <!-- Main Header Nav For Mobile -->
    <div id="page" class="stylehome1">
      <div class="mobile-menu">
        <div class="header stylehome1 home8_style">
          <div class="menu_and_widgets">
            <div class="mobile_menu_bar float-start"> <a class="menubar" href="#menu"><span></span></a> <a
                class="mobile_logo" href="{{ route('customer.home') }}">
                <img src="{{ asset('saas_frontend/img/logo.png') }}" alt="" height="48">
              </a> </div>
            <div class="mobile_menu_widget_icons">
              <ul class="cart mt15">
                <li class="list-inline-item">
                  @guest
                  <a class="cart_btn " href="{{ route('login') }}"><span class="icon flaticon-profile"></span></a>
                  @else
                  <a class="cart_btn " href="{{ route('customer.dashboard') }}"><span class="icon flaticon-profile"></span></a>
                  @endguest
                </li>
                <li class="list-inline-item"> <a class="cart_btn " href="{{ route('customer.cart') }}"><span class="icon"><img
                        src="{{ asset('saas_frontend/images/icons/flaticon-shopping-cart-white.svg') }}" alt=""></span><span
                      class="badge bgc-thm">2</span></a> </li>
              </ul>
            </div>
          </div>
          <div class="mobile_menu_search_widget">
            <div class="header_middle_advnc_search">
              <div class="container search_form_wrapper">
                <div class="row">
                  <div>
                    <div class="top-search text-start">
                      <form action="{{ route('customer.search') }}" method="get" class="form-search" accept-charset="utf-8">
                        <div class="box-search">
                          <input class="form_control" type="text" name="q" placeholder="Search products…">
                        </div>
                      </form>
                    </div>
                  </div>
                  <div>
                    <div class="advscrh_frm_btn">
                      <button type="submit" class="btn search-btn"><span class="flaticon-search"></span></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="posr">
            <div class="mobile_menu_close_btn"><span class="flaticon-close"></span></div>
          </div>
        </div>
      </div>
      <!-- /.mobile-menu -->
      @include('saas_customer.saas_layout.saas_partials.saas_mobile_menu')
    </div>
    <div class="body_content_wrapper position-relative">

        @yield('content')

      <!-- Our Footer -->
      <section class="footer_one home8">
        <div class="container">
          <div class="row ">
            <div class="col-sm-12">
              <ul class="footer-links">
                <li>
                  <a href="#">About Us</a>
                </li>
                <li>
                  <a href="#">Contact Us</a>
                </li>
                <li>
                  <a href="#">Support</a>
                </li>
                <li>
                  <a href="#">Why All Sewa ?</a>
                </li>
                <li>
                  <a href="#">Download free Allsewa</a>
                </li>
                <li>
                  <a href="#">Return & Exchange</a>
                </li>
              </ul>
            </div>
          </div>
          <hr>
          <div class="row align-items-center justify-content-center">
            <div class="col-sm-12 text-center">
              <p class="mb-0 lead" style="color:#fff;">Stay Connected With: &nbsp;&nbsp;</p>
              <div class="social_icon_list home2_style mt10">
                <ul class="mb20">
                  <li class="list-inline-item"><a href="#"><i class="fab fa-facebook"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-whatsapp"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-viber"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-tiktok"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fab fa-telegram"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-envelope"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="container menu_bdrt1 pt10 pb10">
          <div class="row">
            <div class="col-lg-6">
              <div class="copyright-widget home2_style text-center text-lg-start d-block d-lg-flex mb15-md">
                <p class="me-4">© 2023 AllSewa. All Rights Reserved</p>

              </div>
            </div>
            <div class="col-lg-6">
              <div
                class="copyright-widget home2_style text-center text-lg-end d-block d-lg-flex justify-content-lg-end mb15-md">
                <p><a href="{{ route('customer.privacy') }}">Privacy Policy</a> · <a href="{{ route('customer.terms') }}">Terms & Conditions</a> · <a href="{{ route('customer.blog.index') }}">Blog</a></p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
    </div>
  </div>
  <!-- Wrapper End -->
  <script src="{{ asset('saas_frontend/js/jquery-3.6.0.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/jquery-migrate-3.0.0.min.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/popper.min.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/bootstrap-select.min.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/jquery.mmenu.all.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/ace-responsive-menu.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/jquery-scrolltofixed-min.js') }}"></script>
  <script src="{{ asset('saas_frontend/js/slider.js') }}"></script>
  <!-- Custom script for all pages -->
  <script src="{{ asset('saas_frontend/js/script.js') }}"></script>
  <!-- SweetAlert -->
  <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

  <!-- SweetAlert Configuration -->
  <script>
  // Configure SweetAlert defaults for consistent theming
  const SweetAlertConfig = {
      // Default configurations
      defaults: {
          customClass: {
              confirmButton: 'btn btn-primary me-3',
              cancelButton: 'btn btn-secondary',
              popup: 'sweet-alert-popup'
          },
          buttonsStyling: false,
          confirmButtonColor: '#abcf37',
          cancelButtonColor: '#6c757d',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showCloseButton: true
      },

      // Success notification
      success: function(title, text = '', options = {}) {
          return Swal.fire({
              ...this.defaults,
              title: title,
              text: text,
              icon: 'success',
              confirmButtonText: 'OK',
              ...options
          });
      },

      // Error notification
      error: function(title, text = '', options = {}) {
          return Swal.fire({
              ...this.defaults,
              title: title,
              text: text,
              icon: 'error',
              confirmButtonText: 'OK',
              ...options
          });
      },

      // Warning notification
      warning: function(title, text = '', options = {}) {
          return Swal.fire({
              ...this.defaults,
              title: title,
              text: text,
              icon: 'warning',
              confirmButtonText: 'OK',
              ...options
          });
      },

      // Confirmation dialog
      confirm: function(title, text = '', options = {}) {
          return Swal.fire({
              ...this.defaults,
              title: title,
              text: text,
              icon: 'question',
              showCancelButton: true,
              confirmButtonText: 'Yes',
              cancelButtonText: 'No',
              ...options
          });
      },

      // Toast notification
      toast: function(message, type = 'success', options = {}) {
          return Swal.fire({
              title: message,
              icon: type,
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true,
              didOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
              },
              ...options
          });
      },

      // Loading dialog
      loading: function(title = 'Processing...', text = 'Please wait while we process your request.') {
          return Swal.fire({
              title: title,
              text: text,
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false,
              didOpen: () => {
                  Swal.showLoading();
              }
          });
      }
  };

  // Make it globally available
  window.SweetAlert = SweetAlertConfig;

  // Notification helper function
  window.showNotification = function(message, type = 'success', asToast = true) {
      if (asToast) {
          return SweetAlertConfig.toast(message, type);
      } else {
          switch(type) {
              case 'success':
                  return SweetAlertConfig.success('Success!', message);
              case 'error':
                  return SweetAlertConfig.error('Error!', message);
              case 'warning':
                  return SweetAlertConfig.warning('Warning!', message);
              default:
                  return SweetAlertConfig.success('Notice', message);
          }
      }
  };
  </script>

  <!-- Additional SweetAlert Styles -->
  <style>
  .sweet-alert-popup {
      font-family: var(--font-primary);
      border-radius: var(--radius-lg) !important;
  }

  .sweet-alert-popup .swal2-title {
      font-family: var(--font-display);
      color: var(--text-dark);
      font-weight: 600;
  }

  .sweet-alert-popup .swal2-content {
      color: var(--text-medium);
      font-size: 0.95rem;
      line-height: 1.6;
  }

  .sweet-alert-popup .swal2-icon {
      border-width: 3px;
  }

  .sweet-alert-popup .swal2-icon.swal2-success {
      border-color: var(--success);
      color: var(--success);
  }

  .sweet-alert-popup .swal2-icon.swal2-error {
      border-color: var(--danger);
      color: var(--danger);
  }

  .sweet-alert-popup .swal2-icon.swal2-warning {
      border-color: var(--warning);
      color: var(--warning);
  }

  .sweet-alert-popup .swal2-icon.swal2-question {
      border-color: var(--info);
      color: var(--info);
  }

  /* Toast notifications */
  .swal2-toast {
      border-radius: var(--radius-md) !important;
      box-shadow: var(--shadow-lg) !important;
  }

  .swal2-toast .swal2-title {
      font-size: 0.875rem;
      font-weight: 500;
      margin: 0;
  }

  /* Button styling */
  .btn.swal2-styled {
      padding: 0.75rem 1.5rem;
      border-radius: var(--radius-md);
      font-weight: 500;
      font-size: 0.875rem;
      transition: all 0.2s ease;
  }

  .btn.swal2-styled:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
  }

  .btn.swal2-styled.btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
      border: none !important;
  }

  .btn.swal2-styled.btn-primary:hover {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
  }

  .btn.swal2-styled.btn-secondary {
      background: var(--text-medium) !important;
      border: none !important;
  }

  .btn.swal2-styled.btn-secondary:hover {
      background: var(--text-dark) !important;
  }
  </style>
  @stack('scripts')
</body>

</html>
