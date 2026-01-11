<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'Trang chủ')</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="@yield('title', 'Trang chủ')" />
    <meta name="author" content="{{ config('constants.site.name') }}" />
    <meta name="description" content="{{ config('constants.site.description') }}" />
    <meta name="keywords" content="{{ config('constants.meta.keywords') }}" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet"
        href="{{ asset('css/adminlte.min.css') }}?v={{ filemtime(public_path('css/adminlte.min.css')) }}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::Custom CSS-->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!--end::Custom CSS-->
    @stack('styles')
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="bg-body-tertiary" style="margin: 0; padding: 0;">
    <!--begin::App Wrapper-->
    <div class="app-wrapper" style="width: 100%; max-width: 100%; margin: 0; padding: 0;">
        <!--begin::Header-->
        <header class="bg-white shadow-sm sticky-top" style="z-index: 1000;">
            <!-- Top Bar -->
            <div class="top-bar text-white py-2">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-7 col-sm-12">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <small><i class="bi bi-telephone-fill"></i> Hotline:
                                    <strong>{{ config('constants.contact.hotline') }}</strong></small>
                                <small class="d-none d-md-inline">|</small>
                                <small><i class="bi bi-envelope-fill"></i>
                                    {{ config('constants.contact.email') }}</small>
                                <small class="d-none d-md-inline">|</small>
                                <small><i class="bi bi-geo-alt-fill"></i>
                                    {{ config('constants.contact.address') }}</small>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 text-md-end text-start mt-2 mt-md-0">
                            <div class="d-flex flex-wrap align-items-center gap-3 justify-content-md-end">
                                <small><i class="bi bi-truck"></i>
                                    {{ config('constants.shipping.free_message') }}</small>
                                <small class="d-none d-lg-inline">|</small>
                                <small class="d-none d-lg-inline"><i class="bi bi-shield-check"></i> Bảo hành chính
                                    hãng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 border-bottom">
                <div class="container-fluid">
                    <!-- Logo -->
                    <a class="navbar-brand d-flex align-items-center me-4" href="{{ route('home') }}">
                        <div class="logo-wrapper d-flex align-items-center">
                            <div class="logo-icon bg-primary text-white rounded-4 d-flex align-items-center justify-content-center"
                                style="width: 56px; height: 56px;">
                                <i class="bi bi-laptop" style="font-size: 2rem;"></i>
                            </div>
                            <div class="ms-3">
                                <h4 class="mb-0 fw-bold text-primary">{{ config('constants.site.name') }}</h4>
                                <small
                                    class="text-muted d-none d-md-block">{{ config('constants.site.slogan') }}</small>
                            </div>
                        </div>
                    </a>

                    <!-- Search Bar -->
                    <div class="flex-grow-1 mx-4 d-none d-lg-block">
                        <form class="search-form position-relative" method="GET"
                            action="{{ route('products.index') }}">
                            <input class="form-control w-100" type="search" name="search"
                                placeholder="Tìm kiếm laptop, phụ kiện, thương hiệu..." aria-label="Search"
                                value="{{ request('search') }}">
                            <button class="btn btn-primary position-absolute end-0 top-50 translate-middle-y me-2"
                                type="submit">
                                <i class="bi bi-search"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>

                    <!-- Right Menu -->
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('wishlist.index') }}"
                            class="nav-link position-relative d-none d-md-flex flex-column align-items-center text-center"
                            style="min-width: 50px;">
                            <i class="bi bi-heart" style="font-size: 1.75rem; color: var(--danger-color);"></i>
                            <small class="d-none d-lg-block">Yêu thích</small>
                            @auth
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger wishlist-count-badge"
                                    style="font-size: 0.7rem;">
                                    {{ \App\Models\WishlistItem::where('user_id', Auth::id())->count() }}
                                </span>
                            @else
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger wishlist-count-badge"
                                    style="font-size: 0.7rem;">0</span>
                            @endauth
                        </a>
                        <a href="{{ route('cart.index') }}"
                            class="nav-link position-relative d-flex flex-column align-items-center text-center"
                            style="min-width: 50px;">
                            <i class="bi bi-cart-fill" style="font-size: 1.75rem; color: var(--primary-color);"></i>
                            <small class="d-none d-lg-block">Giỏ hàng</small>
                            @auth
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count-badge"
                                    style="font-size: 0.7rem;">
                                    {{ \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity') }}
                                </span>
                            @else
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count-badge"
                                    style="font-size: 0.7rem;">0</span>
                            @endauth
                        </a>
                        @auth
                            <div class="dropdown position-relative">
                                <button
                                    class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none border-0 bg-transparent p-0"
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="min-width: 120px; cursor: pointer;">
                                    <i class="bi bi-person-circle me-2"
                                        style="font-size: 1.75rem; color: var(--primary-color);"></i>
                                    <div class="d-none d-md-block text-start">
                                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ Auth::user()->name }}
                                        </div>
                                        <small class="text-muted">Tài khoản</small>
                                    </div>
                                    <i class="bi bi-chevron-down ms-2 d-none d-md-inline"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                    aria-labelledby="userDropdown"
                                    style="border-radius: var(--radius-lg); min-width: 220px; z-index: 10000; position: absolute;">
                                    <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i
                                                class="bi bi-person me-2"></i> Thông tin tài khoản</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('user.orders') }}"><i
                                                class="bi bi-receipt me-2"></i> Đơn hàng của tôi</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('wishlist.index') }}"><i
                                                class="bi bi-heart me-2"></i> Sản phẩm yêu thích</a></li>
                                    @if (Auth::user()->isAdmin())
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i
                                                    class="bi bi-speedometer me-2"></i> Admin Panel</a></li>
                                    @endif
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button type="submit"
                                                class="dropdown-item py-2 w-100 text-start border-0 bg-transparent"><i
                                                    class="bi bi-box-arrow-right me-2"></i> Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="btn btn-outline-primary d-none d-md-inline-flex align-items-center">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}"
                                class="btn btn-primary d-none d-md-inline-flex align-items-center">
                                <i class="bi bi-person-plus me-2"></i> Đăng ký
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary d-md-none">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Category Menu with Mega Menu -->
            <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--gradient-primary);">
                <div class="container-fluid">
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNav">
                        <i class="bi bi-list me-2"></i> <span>Danh mục sản phẩm</span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav w-100 d-flex justify-content-between flex-nowrap"
                            style="overflow-x: auto; white-space: nowrap;">
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} px-3 py-2"
                                    href="{{ route('home') }}">
                                    <i class="bi bi-house-fill me-2"></i> Trang chủ
                                </a>
                            </li>
                            @foreach ($categories as $category)
                                <li class="nav-item flex-shrink-0">
                                    <a class="nav-link px-3 py-2 {{ request()->routeIs('products.index') && request('category') == $category->id ? 'active' : '' }}"
                                        href="{{ route('products.index', ['category' => $category->id]) }}">
                                        <i class="bi bi-tag me-2"></i> <span
                                            style="white-space: nowrap;">{{ $category->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link px-3 py-2 {{ request()->routeIs('vouchers.index') ? 'active' : '' }}"
                                    href="{{ route('vouchers.index') }}">
                                    <i class="bi bi-tag-fill me-2"></i> <span style="white-space: nowrap;">Khuyến
                                        mãi</span>
                                </a>
                            </li>
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link px-3 py-2 {{ request()->routeIs('news.index') ? 'active' : '' }}"
                                    href="{{ route('news.index') }}">
                                    <i class="bi bi-newspaper me-2"></i> <span style="white-space: nowrap;">Tin
                                        tức</span>
                                </a>
                            </li>
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link px-3 py-2 {{ request()->routeIs('about') ? 'active' : '' }}"
                                    href="{{ route('about') }}">
                                    <i class="bi bi-info-circle me-2"></i> <span style="white-space: nowrap;">Giới
                                        thiệu</span>
                                </a>
                            </li>
                            <li class="nav-item flex-shrink-0">
                                <a class="nav-link px-3 py-2  {{ request()->routeIs('about') ? 'active' : '' }}"
                                    href="{{ route('contact.index') }}">
                                    <i class="bi bi-telephone me-2"></i>
                                    <span style="white-space: nowrap;">Liên hệ</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!--end::Header-->

        <!--begin::App Main-->
        <main class="app-main" style="width: 100%; max-width: 100%; margin: 0; padding: 0;">
            <!--begin::App Content-->
            <div class="app-content" style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0;">
                <!--begin::Container-->
                <div class="container-fluid px-3 px-lg-4" style="max-width: 100%; margin: 0 auto;">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm" role="alert"
                            style="border-radius: 15px; border-left: 4px solid #10b981; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border: none; padding: 18px 25px;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-3"
                                    style="font-size: 1.5rem; color: #10b981;"></i>
                                <div class="flex-grow-1">
                                    <strong style="color: #065f46; font-size: 1.05rem;">Thành công!</strong>
                                    <div style="color: #047857; margin-top: 4px;">{{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3 shadow-sm" role="alert"
                            style="border-radius: 15px; border-left: 4px solid #ef4444; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: none; padding: 18px 25px;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-3"
                                    style="font-size: 1.5rem; color: #ef4444;"></i>
                                <div class="flex-grow-1">
                                    <strong style="color: #991b1b; font-size: 1.05rem;">Lỗi!</strong>
                                    <div style="color: #b91c1c; margin-top: 4px;">{{ session('error') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        <footer class="bg-dark text-white mt-5">
            <div class="container-fluid py-5">
                <div class="row">
                    <!-- About -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-laptop text-primary"></i> {{ config('constants.site.name') }}
                        </h5>
                        <p class="text-muted">{{ config('constants.site.description') }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ config('constants.social.facebook') }}" target="_blank"
                                class="text-white"><i class="bi bi-facebook" style="font-size: 1.5rem;"></i></a>
                            <a href="{{ config('constants.social.instagram') }}" target="_blank"
                                class="text-white"><i class="bi bi-instagram" style="font-size: 1.5rem;"></i></a>
                            <a href="{{ config('constants.social.youtube') }}" target="_blank" class="text-white"><i
                                    class="bi bi-youtube" style="font-size: 1.5rem;"></i></a>
                            <a href="{{ config('constants.social.tiktok') }}" target="_blank" class="text-white"><i
                                    class="bi bi-tiktok" style="font-size: 1.5rem;"></i></a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="mb-3">Liên kết nhanh</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('home') }}"
                                    class="text-muted text-decoration-none">Trang chủ</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Giới
                                    thiệu</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Sản phẩm</a>
                            </li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Khuyến
                                    mãi</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Tin tức</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="mb-3">Hỗ trợ</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Hướng dẫn
                                    mua hàng</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Chính sách
                                    đổi trả</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Bảo hành</a>
                            </li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Vận
                                    chuyển</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Thanh
                                    toán</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="mb-3">Liên hệ</h6>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary"></i>
                                {{ config('constants.contact.address') }}</li>
                            <li class="mb-2"><i class="bi bi-telephone-fill text-primary"></i>
                                {{ config('constants.contact.hotline') }}</li>
                            <li class="mb-2"><i class="bi bi-envelope-fill text-primary"></i>
                                {{ config('constants.contact.email') }}</li>
                            <li class="mb-2"><i class="bi bi-clock-fill text-primary"></i>
                                {{ config('constants.contact.working_hours') }}</li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h6 class="mb-3">Đăng ký nhận tin</h6>
                        <p class="text-muted">Nhận thông tin khuyến mãi và sản phẩm mới nhất</p>
                        <form class="d-flex flex-column gap-2">
                            <input type="email" class="form-control" placeholder="Email của bạn">
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                        </form>
                    </div>
                </div>

                <hr class="bg-secondary">

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ config('constants.site.name') }}.
                            All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <img src="https://img.vietqr.io/image/970422-1234567890-compact2.png" alt="Payment"
                            height="30" class="me-2">
                        <small class="text-muted">Chấp nhận thanh toán online</small>
                    </div>
                </div>
            </div>
        </footer>
        <!--end::Footer-->

        <!-- Customer Support Modal Button -->
        <button type="button" class="btn support-button position-fixed bottom-0 end-0 m-4"
            onclick="toggleSupportModal(); console.log('Button clicked');" style="z-index: 1040; cursor: pointer;">
            <i class="bi bi-headset"></i>
        </button>

        <!-- Customer Support Modal -->
        <div id="supportModal"
            style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.5); overflow-y: auto; align-items: center; justify-content: center;">
            <div
                style="display: flex; align-items: center; justify-content: center; min-height: 100%; padding: 20px; position: relative;">
                <div onclick="event.stopPropagation();"
                    style="position: relative; max-width: 600px; width: 100%; background: white; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); overflow: hidden; margin: auto;">
                    <div
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 24px 28px; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <h5 style="font-weight: 700; font-size: 1.3rem; margin: 0;">
                            <i class="bi bi-headset me-2"></i> Chăm sóc khách hàng
                        </h5>
                        <button type="button" onclick="toggleSupportModal()"
                            style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 1.8rem; cursor: pointer; padding: 0; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; line-height: 1; border-radius: 50%; transition: all 0.3s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
                    </div>
                    <div style="padding: 28px;">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                                style="width: 100px; height: 100px;">
                                <i class="bi bi-headset text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="mt-3 mb-2">Hỗ trợ trực tuyến 24/7</h5>
                            <p class="text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn!</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 text-center p-3"
                                    style="transition: all 0.3s; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                    <a href="tel:{{ str_replace(' ', '', config('constants.contact.hotline')) }}"
                                        class="text-decoration-none text-dark">
                                        <i class="bi bi-telephone-fill text-primary" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Gọi điện thoại</h6>
                                        <p class="mb-0 text-primary fw-bold">{{ config('constants.contact.hotline') }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 text-center p-3"
                                    style="transition: all 0.3s; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                    <a href="mailto:{{ config('constants.contact.email') }}"
                                        class="text-decoration-none text-dark">
                                        <i class="bi bi-envelope-fill text-danger" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Gửi Email</h6>
                                        <p class="mb-0 text-danger fw-bold small">
                                            {{ config('constants.contact.email') }}</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 text-center p-3"
                                    style="transition: all 0.3s; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                    <a href="{{ config('constants.contact.zalo') }}" target="_blank"
                                        class="text-decoration-none text-dark">
                                        <i class="bi bi-chat-dots-fill text-success" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Chat Zalo</h6>
                                        <p class="mb-0 text-muted small">Nhắn tin ngay</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 text-center p-3"
                                    style="transition: all 0.3s; cursor: pointer;"
                                    onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                    <a href="{{ config('constants.contact.messenger') }}" target="_blank"
                                        class="text-decoration-none text-dark">
                                        <i class="bi bi-messenger text-primary" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Messenger</h6>
                                        <p class="mb-0 text-muted small">Chat Facebook</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-light rounded p-3 mb-3">
                            <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i> Thông tin liên hệ
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                    <small>{{ config('constants.contact.address') }}</small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <i class="bi bi-clock-fill text-primary me-2"></i>
                                    <small>{{ config('constants.contact.working_hours') }}</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div>
                            <h6 class="mb-3"><i class="bi bi-question-circle text-primary me-2"></i> Câu hỏi thường
                                gặp</h6>
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq1">
                                            <i class="bi bi-arrow-return-left me-2"></i> Chính sách đổi trả như thế
                                            nào?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Chúng tôi hỗ trợ đổi trả trong vòng 7 ngày kể từ ngày nhận hàng với điều
                                            kiện sản phẩm còn nguyên vẹn, chưa sử dụng. Sản phẩm phải còn đầy đủ phụ
                                            kiện và hộp đựng gốc.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2">
                                            <i class="bi bi-shield-check me-2"></i> Thời gian bảo hành bao lâu?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Tất cả sản phẩm được bảo hành chính hãng từ 12-24 tháng tùy theo nhà sản
                                            xuất. Bảo hành bao gồm lỗi phần cứng và phần mềm do nhà sản xuất.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq3">
                                            <i class="bi bi-truck me-2"></i> Phí vận chuyển là bao nhiêu?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Miễn phí vận chuyển cho đơn hàng trên 5 triệu đồng. Đơn hàng dưới 5 triệu
                                            phí ship 30.000đ. Giao hàng toàn quốc trong 2-5 ngày làm việc.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq4">
                                            <i class="bi bi-credit-card me-2"></i> Phương thức thanh toán nào được chấp
                                            nhận?
                                        </button>
                                    </h2>
                                    <div id="faq4" class="accordion-collapse collapse"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body">
                                            Chúng tôi chấp nhận thanh toán qua MoMo, COD (thanh toán khi nhận hàng),
                                            chuyển khoản ngân hàng, và các thẻ tín dụng/ghi nợ quốc tế.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::App Wrapper-->

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js">
    </script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.min.js') }}?v={{ filemtime(public_path('js/adminlte.min.js')) }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    @stack('scripts')

    <!-- Initialize Bootstrap Dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownToggle = document.getElementById('userDropdown');

            if (dropdownToggle) {
                // Try Bootstrap first
                if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                    try {
                        var dropdown = new bootstrap.Dropdown(dropdownToggle, {
                            boundary: 'viewport',
                            popperConfig: {
                                modifiers: [{
                                    name: 'preventOverflow',
                                    options: {
                                        boundary: document.body
                                    }
                                }]
                            }
                        });
                        return;
                    } catch (e) {
                        console.log('Bootstrap dropdown failed, using fallback', e);
                    }
                }

                // Fallback: Manual dropdown toggle
                var dropdownMenu = dropdownToggle.nextElementSibling;
                if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                    // Ensure dropdown is hidden initially
                    dropdownMenu.style.display = 'none';

                    dropdownToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Close other dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                            if (menu !== dropdownMenu) {
                                menu.style.display = 'none';
                            }
                        });

                        // Toggle current dropdown
                        if (dropdownMenu.style.display === 'block' || dropdownMenu.style.display === '') {
                            dropdownMenu.style.display = 'none';
                        } else {
                            dropdownMenu.style.display = 'block';
                            dropdownMenu.style.position = 'absolute';
                            dropdownMenu.style.zIndex = '10000';
                        }
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (dropdownToggle && dropdownMenu) {
                            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                                dropdownMenu.style.display = 'none';
                            }
                        }
                    });
                }
            }
        });

        // Function to toggle support modal - Simple and reliable
        window.toggleSupportModal = function() {
            console.log('toggleSupportModal called');
            var modal = document.getElementById('supportModal');
            if (!modal) {
                console.error('Support modal element not found!');
                alert('Modal không tìm thấy! Vui lòng refresh trang.');
                return;
            }

            var currentDisplay = window.getComputedStyle(modal).display;
            console.log('Current display:', currentDisplay);
            console.log('Modal element:', modal);

            if (currentDisplay === 'none' || currentDisplay === '') {
                // Show modal
                console.log('Showing modal...');
                modal.style.display = 'flex';
                modal.style.zIndex = '9999';
                document.body.style.overflow = 'hidden';
            } else {
                // Hide modal
                console.log('Hiding modal...');
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };

        // Close modal when clicking backdrop (only the backdrop, not the content)
        document.getElementById('supportModal')?.addEventListener('click', function(e) {
            // Only close if clicking directly on the backdrop (not on modal content)
            if (e.target === this) {
                toggleSupportModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                var modal = document.getElementById('supportModal');
                if (modal && window.getComputedStyle(modal).display !== 'none') {
                    toggleSupportModal();
                }
            }
        });

        // Test function - can be called from console
        window.testSupportModal = function() {
            console.log('Testing support modal...');
            var modal = document.getElementById('supportModal');
            console.log('Modal element:', modal);
            console.log('Current display:', modal ? window.getComputedStyle(modal).display : 'not found');
            toggleSupportModal();
        };
    </script>

    @push('scripts')
        <script>
            function updateWishlistCount(count) {
                document.querySelectorAll('.wishlist-count-badge').forEach(badge => {
                    badge.textContent = count;
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const wishlistButtons = document.querySelectorAll('.toggle-wishlist-btn');

                wishlistButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (!{{ Auth::check() ? 'true' : 'false' }}) {
                            alert('Vui lòng đăng nhập để sử dụng tính năng yêu thích.');
                            window.location.href = "{{ route('login') }}";
                            return;
                        }

                        const productId = this.getAttribute('data-product-id');
                        const icon = this.querySelector('i');
                        if (!icon) {
                            console.error("Icon element not found inside the button!");
                            return; // Ngăn không cho mã chạy tiếp nếu không tìm thấy icon
                        }
                        const isAdded = icon.classList.contains('bi-heart-fill');

                        fetch('{{ route('api.wishlist.toggle') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    product_id: productId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'added') {
                                    icon.classList.remove('bi-heart');
                                    icon.classList.add('bi-heart-fill');
                                    alert(data.message);
                                } else if (data.status === 'removed') {
                                    icon.classList.remove('bi-heart-fill');
                                    icon.classList.add('bi-heart');
                                    // alert(data.message);

                                    // Nếu đang ở trang wishlist, xóa item đó khỏi DOM
                                    if (window.location.pathname.includes('/wishlist')) {
                                        const row = button.closest('.wishlist-item-row');
                                        console.log('Đang ở trang Wishlist. Phần tử cần xóa:', row);
                                        if (row) {
                                            row.remove();
                                        } else {
                                            console.error(
                                                'Không tìm thấy phần tử .wishlist-item-row để xóa!'
                                            );
                                        }
                                    }
                                }
                                updateWishlistCount(data.count);
                            })
                            .catch(error => {
                                console.error('Lỗi khi cập nhật wishlist:', error);
                                alert('Có lỗi xảy ra, vui lòng thử lại.');
                            });
                    });
                });
            });
        </script>
    @endpush

    @stack('scripts')
    <!--end::Script-->
</body>

<!--end::Body-->
<!--begin::Chatbot Popup-->
<div id="chatbot-popup" class="chatbot-popup">
    <!-- Chatbot button (luôn hiển thị) -->
    <button id="chatbot-toggle" class="chatbot-toggle-btn">
        <span class="chatbot-icon">🤖</span>
        <span class="chatbot-badge" id="chatbot-unread">0</span>
    </button>

    <!-- Chat popup (ẩn/hiện) -->
    <div id="chatbot-window" class="chatbot-window">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="chatbot-avatar">🤖</div>
                <div>
                    <h6 class="mb-0">Trợ lý ảo BeeFast</h6>
                    <small class="text-muted">Đang trực tuyến</small>
                </div>
            </div>
            <div class="chatbot-header-right">
                <button id="chatbot-minimize" class="btn btn-sm btn-link text-white">
                    <span class="minimize-icon">─</span>
                </button>
                <button id="chatbot-close" class="btn btn-sm btn-link text-white">
                    <span class="close-icon">×</span>
                </button>
            </div>
        </div>

        <!-- Chat container -->
        <div id="chatbot-container" class="chatbot-container">
            <!-- Welcome message will be loaded here -->
        </div>

        <!-- Input form -->
        <div class="chatbot-input-container">
            <form id="chatbot-form">
                <div class="input-group">
                    <input type="text" id="chatbot-message" class="form-control"
                           placeholder="Nhập câu hỏi của bạn..." autocomplete="off">
                    <button type="submit" class="btn btn-primary" id="chatbot-send">
                        📤
                    </button>
                </div>
            </form>

            <!-- Quick actions -->
            <div class="chatbot-quick-actions">
                <button class="btn btn-sm btn-outline-secondary quick-question" data-question="Xin chào">👋 Chào bot</button>
                <button class="btn btn-sm btn-outline-secondary quick-question" data-question="Sản phẩm">💻 Sản phẩm</button>
                <button class="btn btn-sm btn-outline-secondary quick-question" data-question="Giá cả">💰 Giá cả</button>
                <button class="btn btn-sm btn-outline-secondary quick-question" data-question="Danh mục">📁 Danh mục</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Chatbot popup container - VỊ TRÍ MỚI: bên trái support button */
.chatbot-popup {
    position: fixed;
    bottom: 20px;
    right: 90px; /* Đẩy sang trái để tránh support button */
    z-index: 1041; /* Cao hơn support button (1040) nhưng thấp hơn modal (9999) */
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Toggle button */
.chatbot-toggle-btn {
    position: fixed;
    bottom: 20px;
    right: 90px; /* Đẩy sang trái */
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%); /* Màu xanh khác biệt */
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1042;
}

.chatbot-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.3);
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
}

.chatbot-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4757;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Chat window */
.chatbot-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    max-width: calc(100vw - 40px);
    height: 500px;
    max-height: calc(100vh - 100px);
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 1041;
}

.chatbot-window.active {
    display: flex;
}

/* Khi support modal mở, ẩn chatbot window */
body:has(#supportModal[style*="display: flex"]) .chatbot-window.active {
    display: none;
}

.chatbot-window.minimized {
    height: 60px;
}

.chatbot-window.minimized .chatbot-container,
.chatbot-window.minimized .chatbot-input-container {
    display: none;
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbot-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chatbot-header-right {
    display: flex;
    gap: 5px;
}

.chatbot-header-right button {
    color: white;
    opacity: 0.8;
    font-size: 18px;
    line-height: 1;
    padding: 0 5px;
    background: none;
    border: none;
}

.chatbot-header-right button:hover {
    opacity: 1;
}

/* Chat container */
.chatbot-container {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    background: #f8f9fa;
}

/* Message styles */
.chat-message {
    margin-bottom: 15px;
    display: flex;
}

.chat-message.user {
    justify-content: flex-end;
}

.chat-message.bot {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
}

.chat-message.bot .message-bubble {
    background: white;
    border: 1px solid #e9ecef;
    border-top-left-radius: 5px;
}

.chat-message.user .message-bubble {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-top-right-radius: 5px;
}

.message-content {
    line-height: 1.4;
    white-space: pre-wrap;
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 5px;
    text-align: right;
}

/* Welcome message */
.chatbot-welcome {
    text-align: center;
    padding: 20px 0;
    color: #6c757d;
}

.chatbot-welcome-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

/* Input container */
.chatbot-input-container {
    padding: 15px;
    border-top: 1px solid #e9ecef;
    background: white;
}

.chatbot-input-container .input-group {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-radius: 25px;
    overflow: hidden;
}

#chatbot-message {
    border: none;
    padding: 10px 15px;
}

#chatbot-message:focus {
    box-shadow: none;
    outline: none;
}

#chatbot-send {
    border: none;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    padding: 0 20px;
}

/* Quick actions */
.chatbot-quick-actions {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    justify-content: center;
}

.chatbot-quick-actions button {
    font-size: 12px;
    padding: 3px 8px;
    border-radius: 15px;
    background: white;
    border: 1px solid #dee2e6;
}

.chatbot-quick-actions button:hover {
    background: #f8f9fa;
}

/* Loading spinner */
.chat-loading {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    color: #6c757d;
}

.spinner-small {
    width: 16px;
    height: 16px;
    border: 2px solid #28a745;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-rotate 1s linear infinite;
}

@keyframes spinner-rotate {
    to { transform: rotate(360deg); }
}

/* Scrollbar */
.chatbot-container::-webkit-scrollbar {
    width: 6px;
}

.chatbot-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chatbot-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chatbot-container::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Responsive */
@media (max-width: 768px) {
    .chatbot-popup {
        bottom: 20px;
        right: 80px; /* Điều chỉnh cho mobile */
    }

    .chatbot-toggle-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
        bottom: 20px;
        right: 80px; /* Điều chỉnh cho mobile */
    }

    .chatbot-window {
        width: 300px;
        height: 450px;
        bottom: 70px;
        right: 10px;
    }

    .chatbot-welcome h6 {
        font-size: 1rem;
    }
}

/* Extra small devices */
@media (max-width: 576px) {
    .chatbot-popup {
        bottom: 15px;
        right: 70px;
    }

    .chatbot-toggle-btn {
        width: 45px;
        height: 45px;
        font-size: 18px;
        bottom: 15px;
        right: 70px;
    }

    .chatbot-window {
        width: calc(100vw - 30px);
        right: 5px;
        left: 5px;
        margin: 0 auto;
    }
}

/* Animation cho badge */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chatbot popup loaded');

    // Các biến
    const chatbotPopup = document.getElementById('chatbot-popup');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotForm = document.getElementById('chatbot-form');
    const chatbotMessage = document.getElementById('chatbot-message');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMinimize = document.getElementById('chatbot-minimize');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotUnread = document.getElementById('chatbot-unread');
    const supportModal = document.getElementById('supportModal');

    let isOpen = false;
    let isMinimized = false;
    let unreadCount = 0;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Khởi tạo
    initChatbot();

    function initChatbot() {
        console.log('Initializing chatbot...');

        // Hiển thị welcome message
        showWelcomeMessage();

        // Event listeners
        chatbotToggle.addEventListener('click', toggleChatbot);
        chatbotForm.addEventListener('submit', handleSubmit);
        chatbotMinimize.addEventListener('click', minimizeChatbot);
        chatbotClose.addEventListener('click', closeChatbot);

        // Quick questions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('quick-question')) {
                const question = e.target.getAttribute('data-question');
                console.log('Quick question:', question);
                if (question) {
                    chatbotMessage.value = question;
                    chatbotForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Enter để gửi
        chatbotMessage.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatbotForm.dispatchEvent(new Event('submit'));
            }
        });

        // Click ngoài để đóng (chỉ trên mobile)
        if (window.innerWidth <= 768) {
            document.addEventListener('click', function(e) {
                if (isOpen && !chatbotPopup.contains(e.target) && e.target !== chatbotToggle) {
                    closeChatbot();
                }
            });
        }

        // Theo dõi khi support modal mở/đóng
        if (supportModal) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'style') {
                        const displayStyle = window.getComputedStyle(supportModal).display;
                        if (displayStyle === 'flex') {
                            // Nếu support modal mở, đóng chatbot
                            if (isOpen) {
                                closeChatbot();
                            }
                            // Ẩn chatbot button tạm thời
                            chatbotToggle.style.opacity = '0.3';
                            chatbotToggle.style.pointerEvents = 'none';
                        } else {
                            // Hiện lại chatbot button
                            chatbotToggle.style.opacity = '1';
                            chatbotToggle.style.pointerEvents = 'auto';
                        }
                    }
                });
            });

            observer.observe(supportModal, { attributes: true });
        }
    }

    // Toggle mở/đóng chatbot
    function toggleChatbot() {
        console.log('Toggle chatbot clicked');

        // Kiểm tra nếu support modal đang mở
        if (supportModal && window.getComputedStyle(supportModal).display === 'flex') {
            console.log('Support modal is open, cannot open chatbot');
            return;
        }

        if (!isOpen) {
            openChatbot();
        } else {
            closeChatbot();
        }
    }

    // Mở chatbot
    function openChatbot() {
        chatbotWindow.classList.add('active');
        isOpen = true;
        unreadCount = 0;
        updateUnreadBadge();

        // Focus vào input
        setTimeout(() => {
            chatbotMessage.focus();
            scrollToBottom();
        }, 100);
    }

    // Đóng chatbot
    function closeChatbot() {
        chatbotWindow.classList.remove('active');
        isOpen = false;
        isMinimized = false;
        chatbotWindow.classList.remove('minimized');
    }

    // Thu nhỏ chatbot
    function minimizeChatbot() {
        isMinimized = !isMinimized;
        if (isMinimized) {
            chatbotWindow.classList.add('minimized');
        } else {
            chatbotWindow.classList.remove('minimized');
            chatbotMessage.focus();
        }
    }

    // Hiển thị welcome message
    function showWelcomeMessage() {
        const welcomeHtml = `
            <div class="chatbot-welcome">
                <div class="chatbot-welcome-icon">🤖</div>
                <h6>Xin chào! Tôi là trợ lý ảo BeeFast</h6>
                <p class="small mb-3">Tôi có thể giúp bạn tìm sản phẩm, kiểm tra giá và tư vấn cấu hình.</p>
                <p class="small text-muted mb-2">Hãy thử hỏi:</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button class="btn btn-sm btn-outline-success quick-question" data-question="Sản phẩm laptop">💻 Sản phẩm</button>
                    <button class="btn btn-sm btn-outline-success quick-question" data-question="Giá BeeFast Pro X1">💰 Giá cả</button>
                    <button class="btn btn-sm btn-outline-success quick-question" data-question="Danh mục">📁 Danh mục</button>
                    <button class="btn btn-sm btn-outline-success quick-question" data-question="Xin chào">👋 Chào bot</button>
                </div>
            </div>
        `;
        chatbotContainer.innerHTML = welcomeHtml;
    }

    // Thêm tin nhắn vào chat
    function addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${type}`;

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = text.replace(/\n/g, '<br>');

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        bubbleDiv.appendChild(contentDiv);
        bubbleDiv.appendChild(timeDiv);
        messageDiv.appendChild(bubbleDiv);
        chatbotContainer.appendChild(messageDiv);

        scrollToBottom();

        // Tăng unread count nếu chat đang đóng
        if (!isOpen && type === 'bot') {
            unreadCount++;
            updateUnreadBadge();
        }
    }

    // Hiển thị loading
    function showLoading() {
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'chat-message bot';
        loadingDiv.id = 'chatbot-loading';

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'chat-loading';
        contentDiv.innerHTML = `
            <div class="spinner-small"></div>
            <span>Đang xử lý...</span>
        `;

        bubbleDiv.appendChild(contentDiv);
        loadingDiv.appendChild(bubbleDiv);
        chatbotContainer.appendChild(loadingDiv);

        scrollToBottom();
    }

    // Xóa loading
    function removeLoading() {
        const loading = document.getElementById('chatbot-loading');
        if (loading) {
            loading.remove();
        }
    }

    // Scroll xuống cuối
    function scrollToBottom() {
        chatbotContainer.scrollTop = chatbotContainer.scrollHeight;
    }

    // Update unread badge
    function updateUnreadBadge() {
        if (unreadCount > 0) {
            chatbotUnread.textContent = unreadCount > 9 ? '9+' : unreadCount;
            chatbotUnread.style.display = 'flex';
            chatbotToggle.style.animation = 'pulse 1s infinite';
        } else {
            chatbotUnread.style.display = 'none';
            chatbotToggle.style.animation = 'none';
        }
    }

    // Hàm xử lý click link trong chat
function handleLinkClick(e) {
    e.preventDefault();
    const link = e.target;

    // Chỉ xử lý nếu là link trong chatbot
    if (link.tagName === 'A' && link.href) {
        const url = link.href;

        // Kiểm tra nếu là internal link
        if (url.includes(window.location.origin)) {
            // Mở trong tab mới
            window.open(url, '_blank');
        } else {
            // External link, mở trong tab mới
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    }
}

// Thêm event listener cho link clicks trong chatbot container
document.addEventListener('click', function(e) {
    // Kiểm tra nếu click trong chatbot container
    if (chatbotContainer.contains(e.target)) {
        handleLinkClick(e);
    }
});

    // Xử lý gửi tin nhắn
    function handleSubmit(e) {
        e.preventDefault();

        const message = chatbotMessage.value.trim();
        if (!message) return;

        console.log('Sending message:', message);

        // Thêm tin nhắn người dùng
        addMessage(message, 'user');

        // Xóa input
        chatbotMessage.value = '';

        // Hiển thị loading
        showLoading();

        // Gửi đến server
        fetch("{{ route('chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data);
            removeLoading();

            if (data.success) {
                addMessage(data.message, 'bot');
            } else {
                addMessage('❌ Đã xảy ra lỗi. Vui lòng thử lại sau!', 'bot');
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            removeLoading();
            addMessage('❌ Lỗi kết nối! Vui lòng thử lại sau.', 'bot');
        });
    }
// Hàm xử lý click link trong chat
function handleLinkClick(e) {
    e.preventDefault();
    const link = e.target;

    // Chỉ xử lý nếu là link trong chatbot
    if (link.tagName === 'A' && link.href) {
        const url = link.href;

        // Kiểm tra nếu là internal link
        if (url.includes(window.location.origin)) {
            // Mở trong tab mới
            window.open(url, '_blank');
        } else {
            // External link, mở trong tab mới
            window.open(url, '_blank', 'noopener,noreferrer');
        }
    }
}

// Thêm event listener cho link clicks trong chatbot container
document.addEventListener('click', function(e) {
    // Kiểm tra nếu click trong chatbot container
    if (chatbotContainer.contains(e.target)) {
        handleLinkClick(e);
    }
});
    // Thêm function để kiểm tra xem có thể mở chatbot không
    window.canOpenChatbot = function() {
        if (supportModal && window.getComputedStyle(supportModal).display === 'flex') {
            return false;
        }
        return true;
    };
});
</script>
<!--end::Chatbot Popup-->
</html>
