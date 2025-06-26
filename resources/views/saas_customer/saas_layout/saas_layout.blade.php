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
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet">

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

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-display);
            font-weight: 600;
            line-height: 1.3;
            color: var(--text-dark);
        }

        .btn-primary,
        .btn-thm {
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

        .btn-primary:hover,
        .btn-thm:hover {
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

        .form-control,
        .form-select {
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: var(--white);
        }

        .form-control:focus,
        .form-select:focus {
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

        /* Custom Rs currency icon */
        .rs-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            font-weight: bold;
            font-size: 11px;
            font-family: var(--font-primary);
            color: inherit;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            text-align: center;
            line-height: 1;
        }

        .rs-icon-sm {
            width: 14px;
            height: 14px;
            font-size: 9px;
        }

        .rs-icon-lg {
            width: 20px;
            height: 20px;
            font-size: 12px;
        }

        .rs-icon-xl {
            width: 24px;
            height: 24px;
            font-size: 14px;
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

            h1 {
                font-size: 1.875rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.25rem;
            }

            h4 {
                font-size: 1.125rem;
            }

            h5 {
                font-size: 1rem;
            }

            h6 {
                font-size: 0.875rem;
            }
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

        /* Floating WhatsApp Button */
        .floating-whatsapp-btn {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            /* WhatsApp brand color */
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 999;
            transition: all 0.3s ease;
        }

        @media (min-width: 991px) {
            .floating-whatsapp-btn {
                bottom: 30px;
                right: 20px;
            }
        }

        .floating-whatsapp-btn i {
            font-size: 30px;
        }

        .floating-whatsapp-btn:hover {
            transform: scale(1.1);
            color: white;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background-color: #20ba5a;
        }

        /* Add a subtle pulse animation */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 0 0 12px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .floating-whatsapp-btn {
            animation: pulse 2s infinite;
        }
    </style>

    <style>
        /* Sticky Header Styling */
        .header-nav.main-menu.is-fixed,
        .mobile-menu .header.stylehome1.is-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1020;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        /* Enhanced Mobile Header Styling */
        @media (max-width: 991px) {
            .mobile-menu .header.stylehome1 {
                background: var(--secondary-color);
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                padding: 1rem 0;
                border-bottom: 1px solid var(--border-light);
                position: relative;
            }

            .mobile_menu_bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0;
            }

            .mobile_menu_bar .mobile_logo img {
                height: 44px;
                width: auto;
            }

            .mobile_menu_bar .menubarmob {
                width: 42px;
                height: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--primary-color);
                border-radius: var(--radius-lg);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
                margin-top: 1rem;
            }

            .mobile_menu_widget_icons {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0;
            }

            .mobile_menu_widget_icons .cart {
                margin: 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .mobile_menu_widget_icons .cart li .cart_btn {
                width: 42px;
                height: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--secondary-color);
                border-radius: var(--radius-lg);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .mobile_menu_widget_icons .cart li .cart_btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s;
            }

            .mobile_menu_widget_icons .cart li .cart_btn:hover::before {
                left: 100%;
            }

            .mobile_menu_widget_icons .cart li .cart_btn:hover {
                background: var(--secondary-dark);
                box-shadow: 0 4px 12px rgba(9, 113, 126, 0.3);
            }

            .mobile_menu_widget_icons .cart li .cart_btn .icon {
                color: var(--white);
                font-size: 1.25rem;
            }

            .mobile_menu_widget_icons .cart li .cart_btn .badge {
                position: absolute;
                top: -6px;
                right: -6px;
                background: var(--danger);
                color: var(--white);
                border-radius: 50%;
                font-size: 0.625rem;
                font-weight: 700;
                min-width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 2px solid var(--white);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .mobile_menu_search_widget {
                background: linear-gradient(135deg, var(--accent-color), var(--white));
                padding: 1.25rem;
                border-top: 1px solid var(--border-light);
            }

            .mobile_menu_search_widget .search_form_wrapper {
                max-width: 100%;
            }

            .mobile_menu_search_widget .row {
                margin: 0;
                align-items: center;
            }

            .mobile_menu_search_widget .row>div {
                padding: 0;
            }

            .mobile_menu_search_widget .row>div:first-child {
                flex: 1;
                padding-right: 0.75rem;
            }

            .mobile_menu_search_widget .form_control {
                border: 2px solid var(--border-light);
                border-radius: var(--radius-lg);
                padding: 0.875rem 1.25rem;
                font-size: 0.875rem;
                background: var(--white);
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .mobile_menu_search_widget .form_control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(171, 207, 55, 0.1), 0 4px 12px rgba(0, 0, 0, 0.1);
                outline: none;
                background: var(--white);
            }

            .mobile_menu_search_widget .search-btn {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
                border: none;
                color: var(--white);
                padding: 0.875rem 1.25rem;
                transition: all 0.3s ease;
                font-size: 1rem;
                box-shadow: 0 2px 8px rgba(171, 207, 55, 0.3);
            }

            .mobile_menu_search_widget .search-btn:hover {
                background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
                transform: translateY(-2px);
                box-shadow: 0 4px 16px rgba(171, 207, 55, 0.4);
            }

            .mobile_menu_search_widget .search-btn .flaticon-search {
                font-size: 1.125rem;
            }

            /* Mobile Header Animation */
            .mobile-menu .header.stylehome1.is-fixed {
                animation: slideDownMobile 0.3s ease-out;
            }

            @keyframes slideDownMobile {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        }

        /* Mobile Bottom Navigation Bar */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--white);
            border-top: 1px solid var(--border-light);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            display: none;
            padding: 0.75rem 0 0.5rem;
            backdrop-filter: blur(15px);
            transition: transform 0.3s ease;
        }

        @media (max-width: 991px) {
            .mobile-bottom-nav {
                display: block;
            }

            body {
                padding-bottom: 80px;
            }
        }

        .mobile-bottom-nav .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            margin: 0;
            padding: 0 1rem;
            list-style: none;
        }

        .mobile-bottom-nav .nav-item {
            flex: 1;
            text-align: center;
            max-width: 70px;
        }

        .mobile-bottom-nav .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.25rem;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.3s ease;
            border-radius: var(--radius-lg);
            position: relative;
            min-height: 50px;
        }

        .mobile-bottom-nav .nav-link:hover,
        .mobile-bottom-nav .nav-link.active {
            color: var(--primary-color);
            background: rgba(171, 207, 55, 0.08);
            transform: translateY(-2px);
        }

        .mobile-bottom-nav .nav-icon {
            font-size: 1.375rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
        }

        .mobile-bottom-nav .nav-link:hover .nav-icon,
        .mobile-bottom-nav .nav-link.active .nav-icon {
            transform: scale(1.15);
        }

        .mobile-bottom-nav .nav-text {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            line-height: 1;
            margin-top: 2px;
        }

        .mobile-bottom-nav .nav-badge {
            position: absolute;
            top: 0.125rem;
            right: 0.875rem;
            background: var(--danger);
            color: var(--white);
            border-radius: 50%;
            font-size: 0.5rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
            box-shadow: 0 2px 6px rgba(245, 101, 101, 0.3);
            animation: pulse-badge 3s infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Ripple Effect for Navigation Items */
        .mobile-bottom-nav .nav-link::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(171, 207, 55, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .mobile-bottom-nav .nav-link:active::after {
            width: 100%;
            height: 100%;
        }

        /* Popup Banner Styles */
        .popup-banner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .popup-banner-overlay.show {
            display: flex !important;
        }

        .popup-banner-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            animation: popupZoom 0.3s ease-out;
        }

        @keyframes popupZoom {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .popup-banner-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.7);
            color: var(--white);
            border: none;
            border-radius: 50%;
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .popup-banner-close:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.1);
        }

        .popup-banner-image {
            width: 100%;
            height: auto;
            display: block;
            max-height: 80vh;
            object-fit: cover;
        }

        .popup-banner-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        /* Footer Banner Styles */
        .footer-banner {
            background: var(--white);
            border-top: 1px solid var(--border-light);
            padding: 1rem 0;
            margin-top: 2rem;
        }

        .footer-banner-item {
            display: block;
            text-decoration: none;
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .footer-banner-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .footer-banner-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: var(--radius-md);
        }

        /* Main Section Banner Styles */
        .main-section-banner {
            margin: 2rem 0;
        }

        .main-section-banner-item {
            display: block;
            text-decoration: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .main-section-banner-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .main-section-banner-image {
            width: 100%;
            height: auto;
            display: block;
            min-height: 200px;
            object-fit: cover;
        }

        /* Hide floating WhatsApp on mobile to avoid overlap */
        @media (max-width: 991px) {
            .floating-whatsapp-btn {
                bottom: 90px;
            }

            .popup-banner-content {
                max-width: 95%;
                max-height: 85%;
            }

            .footer-banner-image {
                height: 80px;
            }

            .main-section-banner-image {
                min-height: 150px;
            }

            /* Enhanced mobile scrollbar */
            ::-webkit-scrollbar {
                width: 4px;
            }

            ::-webkit-scrollbar-track {
                background: var(--accent-color);
            }

            ::-webkit-scrollbar-thumb {
                background: var(--primary-color);
                border-radius: 2px;
            }

            /* Mobile Menu Close Button Enhancement */
            .mobile_menu_close_btn {
                position: absolute;
                top: 1rem;
                right: 1rem;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0.05);
                border-radius: 50%;
                transition: all 0.3s ease;
            }

            .mobile_menu_close_btn:hover {
                background: rgba(0, 0, 0, 0.1);
                transform: scale(1.1);
            }

            /* Mobile Safe Area Handling */
            .mobile-bottom-nav {
                padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
            }

            body {
                padding-bottom: calc(80px + env(safe-area-inset-bottom));
            }

            /* Hide cart count if zero */
            .nav-badge:empty,
            .nav-badge[data-count="0"] {
                display: none;
            }
        }

        /* Additional mobile improvements */
        @media (max-width: 767px) {
            .mobile_menu_search_widget {
                padding: 1rem;
            }

            .mobile_menu_search_widget .form_control {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }

            .mobile_menu_search_widget .search-btn {
                padding: 0.75rem 1rem;
            }

            .mobile-bottom-nav .nav-text {
                font-size: 0.55rem;
            }

            .mobile-bottom-nav .nav-icon {
                font-size: 1.25rem;
            }

            .mobile-bottom-nav .nav-item:nth-child(2) .nav-link {
                width: 52px;
                height: 52px;
            }

            .mobile-bottom-nav .nav-item:nth-child(2) .nav-link .nav-icon {
                font-size: 1.375rem;
            }
        }
    </style>

    <!-- Responsive stylesheet -->
    <link rel="stylesheet" href="{{ asset('saas_frontend/css/responsive.css') }}">
    <!-- Title -->
    <title>Allsewa</title>
    <!-- Favicon -->
    <link href="{{ asset('saas_frontend/images/favicon.ico') }}" sizes="128x128" rel="shortcut icon"
        type="image/x-icon" />
    <link href="{{ asset('saas_frontend/images/favicon.ico') }}" sizes="128x128" rel="shortcut icon" />
    <!-- Apple Touch Icon -->
    <link href="{{ asset('saas_frontend/images/apple-touch-icon-60x60.png') }}" sizes="60x60" rel="apple-touch-icon">
    <link href="{{ asset('saas_frontend/images/apple-touch-icon-72x72.png') }}" sizes="72x72" rel="apple-touch-icon">
    <link href="{{ asset('saas_frontend/images/apple-touch-icon-114x114.png') }}" sizes="114x114"
        rel="apple-touch-icon">
    <link href="{{ asset('saas_frontend/images/apple-touch-icon-180x180.png') }}" sizes="180x180"
        rel="apple-touch-icon">
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
                                    <img src="{{ asset('saas_frontend/img/logo.png') }}" alt=""
                                        class="img-fluid">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-3">
                        <div class="header_middle_advnc_search mb-xxl-0">
                            <div class="search_form_wrapper home7_style athome8">
                                <div class="top-search home7_style athome8">
                                    <form style="margin: 0 !important;" action="{{ route('customer.search') }}"
                                        method="get" class="form-search" accept-charset="utf-8">
                                        <div class="box-search pre_line before_none">
                                            <input class="form_control" type="text" name="q"
                                                placeholder="Search products…">
                                            <div class="advscrh_frm_btn home7_style">
                                                <button type="submit" class="btn search-btn"><span
                                                        class="flaticon-search"></span></button>
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
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu3"
                                        data-bs-toggle="dropdown" aria-expanded="false"> Language</button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">English</a></li>
                                        <li><a class="dropdown-item" href="#">Nepali</a></li>
                                    </ul>
                                </div>
                                @guest
                                    <a href="{{ route('login') }}"
                                        class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                                        <div class="d-block d-flex align-items-center">
                                            <div class="details">
                                                <h5 class="title">Log In</h5>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                                        <div class="d-block d-flex align-items-center">
                                            <div class="details">
                                                <h5 class="title">Register</h5>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}"
                                        class="header_iconbox_home3_style athome8 ps-3 pe-3 has-border">
                                        <div class="d-block d-flex align-items-center">
                                            <div class="details">
                                                <h5 class="title">My Account</h5>
                                            </div>
                                        </div>
                                    </a>
                                @endguest
                                <a href="{{ route('customer.cart') }}"
                                    class="header_iconbox_home3_style athome8 has-border">
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
                        <button type="button" id="menu-btn"> <span class="icon-bar"></span> <span
                                class="icon-bar"></span> <span class="icon-bar"></span> </button>
                    </div>
                    <div class="posr logo1">
                        @include('saas_customer.saas_layout.saas_partials.saas_mega_menu')
                    </div>
                    <!-- Responsive Menu Structure-->
                    <ul id="respMenu" class="ace-responsive-menu menu_list_custom_code wa pl200"
                        data-menu-style="horizontal">
                        <li class=""> <a href="{{ route('customer.home') }}"><span
                                    class="title">All</span></a> </li>
                        <li class=""> <a href="{{ route('customer.products') }}"><span
                                    class="title">Products</span></a> </li>
                        <li class=""> <a href="{{ route('customer.brands') }}"><span
                                    class="title">Brands</span></a> </li>
                        <li class=""> <a href="{{ route('customer.sellers') }}"><span
                                    class="title">Sellers</span></a> </li>
                        <li class=""> <a href="{{ route('customer.blog.index') }}"><span
                                    class="title">Blog</span></a> </li>
                        @if ($settings->apply_share_link)
                            <li class=""> <a href="{{ $settings->apply_share_link }}" target="_blank"><span
                                        class="title">Apply Share</span></a> </li>
                        @endif
                        <li class="dropdown-submenu">
                            <a href="#"><span class="title">More</span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('customer.about') }}">About Us</a></li>
                                <li><a href="{{ route('customer.contact') }}">Contact Us</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="widget_menu_home2" style="width: auto !important;">
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
                        <div class="mobile_menu_bar float-start">
                            <button style="border: none;" class="menubarmob">
                                <img src="{{ asset('saas_frontend/images/desktop-nav-menu-white.svg') }}"
                                    alt="" height="20px" width="20px">
                            </button>
                            <a style="margin-top: 1rem; margin-left: .5rem; background-color: white; border-radius: 5px; padding: 5px;" href="{{ route('customer.home') }}">
                                <img src="{{ asset('saas_frontend/img/logo.png') }}" alt=""
                                    style="height: 30px;">
                            </a>
                        </div>
                        <div class="mobile_menu_widget_icons">
                            <ul class="cart mt15">
                                <li class="list-inline-item">
                                    @guest
                                        <a class="cart_btn " href="{{ route('login') }}"><span
                                                class="icon flaticon-profile"></span></a>
                                    @else
                                        <a class="cart_btn " href="{{ route('customer.dashboard') }}"><span
                                                class="icon flaticon-profile"></span></a>
                                    @endguest
                                </li>
                                <li class="list-inline-item"> <a class="cart_btn "
                                        href="{{ route('customer.cart') }}"><span class="icon"><img
                                                src="{{ asset('saas_frontend/images/icons/flaticon-shopping-cart-white.svg') }}"
                                                alt=""></span></a> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mobile_menu_search_widget" style="padding: 0 !important;">
                        <div class="header_middle_advnc_search">
                            <div class="container search_form_wrapper">
                                <div class="row">
                                    <div>
                                        <div class="top-search text-start">
                                            <form style="margin: 0 !important;"
                                                action="{{ route('customer.search') }}" method="get"
                                                class="form-search" accept-charset="utf-8">
                                                <div class="box-search">
                                                    <input class="form_control" type="text" name="q"
                                                        placeholder="Search products…">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="advscrh_frm_btn" style="border-radius: 0 !important;">
                                            <button type="submit" class="btn search-btn"><span
                                                    class="flaticon-search"></span></button>
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
                            <div>
                                @if (isset($promotionalBanners) && $promotionalBanners->count() > 0)

                                    <div class="row d-flex justify-content-center align-items-center">
                                        @foreach ($promotionalBanners as $banner)
                                            <div class="col-md-4 col-12 mb-3">
                                                @if ($banner->link_url)
                                                    <a href="{{ $banner->link_url }}" class="footer-banner-item"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                                            alt="{{ $banner->title }}" class="footer-banner-image">
                                                    </a>
                                                @else
                                                    <div class="footer-banner-item">
                                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                                            alt="{{ $banner->title }}" class="footer-banner-image">
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <p class="mb-0 lead" style="color:#fff;">Stay Connected With: &nbsp;&nbsp;</p>
                            <div class="social_icon_list home2_style mt10">
                                <ul class="mb20">
                                    @if ($settings->site_facebook)
                                        <li class="list-inline-item"><a href="{{ $settings->site_facebook }}"
                                                target="_blank"><i class="fab fa-facebook"></i></a></li>
                                    @endif
                                    @if ($settings->site_twitter)
                                        <li class="list-inline-item"><a href="{{ $settings->site_twitter }}"
                                                target="_blank"><i class="fab fa-twitter"></i></a></li>
                                    @endif
                                    @if ($settings->site_instagram)
                                        <li class="list-inline-item"><a href="{{ $settings->site_instagram }}"
                                                target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    @endif
                                    @if ($settings->site_youtube)
                                        <li class="list-inline-item"><a href="{{ $settings->site_youtube }}"
                                                target="_blank"><i class="fab fa-youtube"></i></a></li>
                                    @endif
                                    @if ($settings->site_whatsapp)
                                        <li class="list-inline-item"><a href="{{ $settings->site_whatsapp }}"
                                                target="_blank"><i class="fab fa-whatsapp"></i></a></li>
                                    @endif
                                    @if ($settings->site_linkedin)
                                        <li class="list-inline-item"><a href="{{ $settings->site_linkedin }}"
                                                target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                    @endif
                                    <li class="list-inline-item"><a href="mailto:{{ $settings->site_email }}"
                                            target="_blank"><i class="fa fa-envelope"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container menu_bdrt1 pt10 pb10">
                    <div class="row">
                        <div class="col-lg-6">
                            <div
                                class="copyright-widget home2_style text-center text-lg-start d-block d-lg-flex mb15-md">
                                <p class="me-4">© 2023 AllSewa. All Rights Reserved</p>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div
                                class="copyright-widget home2_style text-center text-lg-end d-block d-lg-flex justify-content-lg-end mb15-md">
                                <p><a href="{{ route('customer.privacy') }}">Privacy Policy</a> · <a
                                        href="{{ route('customer.terms') }}">Terms & Conditions</a> · <a
                                        href="{{ route('customer.blog.index') }}">Blog</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a> --}}

            @if ($settings->site_whatsapp)
                <!-- Floating WhatsApp Button -->
                <a href="{{ $settings->site_whatsapp }}" target="_blank" class="floating-whatsapp-btn">
                    <i class="fab fa-whatsapp"></i>
                </a>
            @endif
        </div>
    </div>
    <!-- Wrapper End -->

    <!-- Popup Banners -->
    @if (isset($popupBanners) && $popupBanners->count() > 0)
        @foreach ($popupBanners as $banner)
            <div class="popup-banner-overlay" id="popup-banner-{{ $banner->id }}">
                <div class="popup-banner-content">
                    <button class="popup-banner-close"
                        onclick="closePopupBanner({{ $banner->id }})">&times;</button>
                    @if ($banner->link_url)
                        <a href="{{ $banner->link_url }}" class="popup-banner-link" target="_blank">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                                class="popup-banner-image">
                        </a>
                    @else
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}"
                            class="popup-banner-image">
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="{{ route('customer.home') }}"
                    style="{{ request()->routeIs('customer.home') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                    class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customer.cart') }}"
                    style="{{ request()->routeIs('customer.cart') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                    class="nav-link {{ request()->routeIs('customer.cart') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="nav-text">Cart</span>
                </a>
            </li>
            <li class="nav-item">
                @auth
                    <a href="{{ route('customer.orders') }}"
                        style="{{ request()->routeIs('customer.orders') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                        class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        style="{{ request()->routeIs('customer.orders') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                        class="nav-link">
                        <i class="nav-icon fas fa-receipt"></i>
                        <span class="nav-text">Orders</span>
                    </a>
                @endauth
            </li>
            <li class="nav-item">
                @auth
                    <a href="{{ route('customer.dashboard') }}"
                        style="{{ request()->routeIs('customer.dashboard') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                        class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <span class="nav-text">Account</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        style="{{ request()->routeIs('customer.login') ? 'border-bottom: .15rem solid var(--primary-color);' : '' }}"
                        class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <span class="nav-text">Login</span>
                    </a>
                @endauth
            </li>
        </ul>
    </nav>
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
                switch (type) {
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
    <script>
        // Simple Mobile Navigation Script using jQuery (consistent with existing codebase)
        $(document).ready(function() {

            // Mobile Bottom Navigation Functions
            function initMobileBottomNav() {
                if ($(window).width() <= 991) {
                    // Handle navigation clicks with animation
                    $('.mobile-bottom-nav .nav-link').on('click', function() {
                        var $this = $(this);
                        $this.css('transform', 'scale(0.95)');
                        setTimeout(function() {
                            $this.css('transform', '');
                        }, 150);
                    });

                    // Update cart count from existing header badge
                    function updateCartCount() {
                        var cartCount = 0;
                        var existingBadge = $('.bgc-thm, .badge');

                        if (existingBadge.length) {
                            cartCount = parseInt(existingBadge.text()) || 0;
                        }

                        $('#mobile-cart-count').text(cartCount);

                        if (cartCount === 0) {
                            $('#mobile-cart-count').hide();
                        } else {
                            $('#mobile-cart-count').show();
                        }
                    }

                    // Set active navigation state
                    function setActiveNav() {
                        var currentPath = window.location.pathname;
                        $('.mobile-bottom-nav .nav-link').removeClass('active');

                        $('.mobile-bottom-nav .nav-link').each(function() {
                            var href = $(this).attr('href');
                            if (href && (currentPath === href || currentPath.indexOf(href + '/') === 0)) {
                                $(this).addClass('active');
                            }
                        });
                    }

                    // Initialize functions
                    updateCartCount();
                    setActiveNav();

                    // Update cart count periodically
                    setInterval(updateCartCount, 30000);
                }
            }

            // Mobile Header Functions
            function initMobileHeader() {
                if ($(window).width() <= 991) {
                    // Handle mobile menu button click to trigger mmenu
                    $('.menubarmob').on('click', function() {
                        // Trigger the existing mmenu functionality
                        var api = $("#menu").data("mmenu");
                        if (api) {
                            api.open();
                        }
                    });

                    // Mobile header sticky behavior
                    $(window).scroll(function() {
                        var scroll = $(window).scrollTop();
                        var mobileHeader = $('.mobile-menu .header.stylehome1');

                        if (scroll >= 100) {
                            mobileHeader.addClass('is-fixed');
                            $('body').css('padding-top', mobileHeader.outerHeight() + 'px');
                        } else {
                            mobileHeader.removeClass('is-fixed');
                            $('body').css('padding-top', '0');
                        }
                    });
                }
            }

            // Desktop Header Sticky Function
            function initDesktopHeader() {
                var header = $('.header-nav.main-menu');
                if (header.length > 0) {
                    var sticky = header.offset().top;
                    $(window).on('scroll', function() {
                        if ($(window).width() > 991) {
                            if ($(window).scrollTop() > sticky) {
                                header.addClass('is-fixed');
                                $('.body_content_wrapper').css('padding-top', header.outerHeight());
                            } else {
                                header.removeClass('is-fixed');
                                $('.body_content_wrapper').css('padding-top', 0);
                            }
                        } else {
                            header.removeClass('is-fixed');
                            $('.body_content_wrapper').css('padding-top', 0);
                        }
                    });
                }
            }

            // Initialize mobile navigation
            initMobileBottomNav();
            initMobileHeader();
            initDesktopHeader();

            // Re-initialize on window resize
            $(window).resize(function() {
                initMobileBottomNav();
                initMobileHeader();
                initDesktopHeader();
            });

            // Popup Banner Functionality
            function showPopupBanners() {
                console.log('showPopupBanners function called');
                @if (isset($popupBanners) && $popupBanners->count() > 0)
                    console.log('Popup banners available: {{ $popupBanners->count() }}');

                    // Create array of banner data
                    var banners = [
                        @foreach ($popupBanners as $banner)
                            {
                                id: {{ $banner->id }},
                                title: '{{ addslashes($banner->title) }}',
                                image: '{{ $banner->image }}',
                                link_url: '{{ $banner->link_url ?? '' }}'
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    ];

                    // Process banners
                    for (var i = 0; i < banners.length; i++) {
                        var banner = banners[i];
                        var bannerId = banner.id;
                        var closedBanners = JSON.parse(sessionStorage.getItem('closedPopupBanners') || '[]');

                        console.log('Checking banner ID:', bannerId);
                        console.log('Closed banners:', closedBanners);

                        var bannerElement = $('#popup-banner-' + bannerId);
                        console.log('Banner element exists:', bannerElement.length > 0);

                                                if (!closedBanners.includes(bannerId) && bannerElement.length > 0) {
                            console.log('Showing banner ID:', bannerId);

                            // Show the first available banner (use closure to capture variables)
                            (function(element, id) {
                                setTimeout(function() {
                                    console.log('Actually showing banner ID:', id);
                                    element.css({
                                        'display': 'flex',
                                        'opacity': '0'
                                    }).animate({
                                        'opacity': '1'
                                    }, 500);
                                }, 1000);
                            })(bannerElement, bannerId);

                            break; // Now this break is inside a proper for loop
                        } else {
                            console.log('Banner ID', bannerId, 'was previously closed or element not found');
                        }
                    }
                @else
                    console.log('No popup banners available');
                @endif
            }

            // Initialize popup banners after page load with multiple triggers
            setTimeout(showPopupBanners, 500);

            // Also try after window load
            $(window).on('load', function() {
                setTimeout(showPopupBanners, 1000);
            });

        });

        // Popup banner close function (global scope)
        function closePopupBanner(bannerId) {
            console.log('Closing popup banner ID:', bannerId);
            var bannerElement = $('#popup-banner-' + bannerId);

            if (bannerElement.length > 0) {
                bannerElement.animate({
                    'opacity': '0'
                }, 300, function() {
                    $(this).css('display', 'none');
                });

                // Store closed banner in session storage
                var closedBanners = JSON.parse(sessionStorage.getItem('closedPopupBanners') || '[]');
                if (!closedBanners.includes(bannerId)) {
                    closedBanners.push(bannerId);
                    sessionStorage.setItem('closedPopupBanners', JSON.stringify(closedBanners));
                    console.log('Banner ID', bannerId, 'added to closed banners');
                }
            } else {
                console.log('Banner element not found for closing ID:', bannerId);
            }
        }

                // Manual test function for debugging (can be called from browser console)
        function testPopupBanner() {
            console.log('Testing popup banner manually...');
            sessionStorage.removeItem('closedPopupBanners'); // Clear session storage
            @if (isset($popupBanners) && $popupBanners->count() > 0)
                // Create array of banner data for testing
                var banners = [
                    @foreach ($popupBanners as $banner)
                        {
                            id: {{ $banner->id }},
                            title: '{{ addslashes($banner->title) }}'
                        }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                ];

                for (var i = 0; i < banners.length; i++) {
                    var banner = banners[i];
                    var bannerId = banner.id;
                    var bannerElement = $('#popup-banner-' + bannerId);
                    console.log('Testing banner ID:', bannerId);
                    console.log('Banner element found:', bannerElement.length > 0);

                    if (bannerElement.length > 0) {
                        console.log('Showing banner ID:', bannerId);
                        bannerElement.css({
                            'display': 'flex',
                            'opacity': '0'
                        }).animate({
                            'opacity': '1'
                        }, 500);
                        break;
                    } else {
                        console.log('Banner element not found for ID:', bannerId);
                    }
                }
            @else
                console.log('No popup banners to test');
            @endif
        }

        // Clear session storage function for testing
        function clearPopupBannerSession() {
            sessionStorage.removeItem('closedPopupBanners');
            console.log('Popup banner session cleared');
        }

        // Debug function to check banner data
        function debugPopupBanners() {
            console.log('=== Popup Banner Debug Info ===');
            @if (isset($popupBanners) && $popupBanners->count() > 0)
                console.log('Popup banners count:', {{ $popupBanners->count() }});
                @foreach ($popupBanners as $banner)
                    console.log('Banner {{ $loop->iteration }}:', {
                        id: {{ $banner->id }},
                        title: '{{ addslashes($banner->title) }}',
                        image: '{{ $banner->image }}',
                        link_url: '{{ $banner->link_url ?? '' }}',
                        is_active: {{ $banner->is_active ? 'true' : 'false' }},
                        element_exists: $('#popup-banner-{{ $banner->id }}').length > 0
                    });
                @endforeach
            @else
                console.log('No popup banners available in view data');
            @endif
            console.log('Closed banners:', JSON.parse(sessionStorage.getItem('closedPopupBanners') || '[]'));
            console.log('=== End Debug Info ===');
        }

        // Close popup on overlay click
        $(document).on('click', '.popup-banner-overlay', function(e) {
            if (e.target === this) {
                var bannerId = $(this).attr('id').replace('popup-banner-', '');
                closePopupBanner(parseInt(bannerId));
            }
        });

        // Close popup on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.popup-banner-overlay:visible').each(function() {
                    var bannerId = $(this).attr('id').replace('popup-banner-', '');
                    closePopupBanner(parseInt(bannerId));
                });
            }
        });

                // Auto-show toast notifications for session messages
        $(document).ready(function() {
            @if(session('success'))
                showNotification({!! json_encode(session('success')) !!}, 'success');
            @endif

            @if(session('error'))
                showNotification({!! json_encode(session('error')) !!}, 'error');
            @endif

            @if(session('warning'))
                showNotification({!! json_encode(session('warning')) !!}, 'warning');
            @endif

            @if(session('info'))
                showNotification({!! json_encode(session('info')) !!}, 'info');
            @endif
        });
    </script>
</body>

</html>
