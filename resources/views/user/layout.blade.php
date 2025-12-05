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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
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
                                    style="border-radius: var(--radius-lg); min-width: 220px; z-index: 9999;">
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
                        <ul class="navbar-nav w-100 d-flex justify-content-between">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }} px-3 py-2"
                                    href="{{ route('home') }}">
                                    <i class="bi bi-house-fill me-2"></i> Trang chủ
                                </a>
                            </li>
                            @foreach (config('constants.categories') as $key => $category)
                                <li class="nav-item dropdown dropdown-mega">
                                    <a class="nav-link dropdown-toggle px-3 py-2" href="#" role="button"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <i class="bi {{ $category['icon'] }} me-2"></i> {{ $category['name'] }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-mega-menu shadow-lg border-0 p-4"
                                        style="border-radius: var(--radius-xl); min-width: 600px;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6 class="fw-bold mb-3 text-primary">Danh mục phổ biến</h6>
                                                <ul class="list-unstyled">
                                                    @foreach ($category['subcategories'] as $sub)
                                                        <li class="mb-2">
                                                            <a href="#"
                                                                class="text-decoration-none d-flex align-items-center p-2 rounded"
                                                                style="transition: all 0.3s;">
                                                                <i class="bi bi-chevron-right me-2 text-primary"></i>
                                                                <span>{{ $sub }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-3 text-primary">Sản phẩm nổi bật</h6>
                                                <div class="row g-3">
                                                    @for ($i = 1; $i <= 4; $i++)
                                                        <div class="col-6">
                                                            <a href="#" class="text-decoration-none">
                                                                <div class="card border-0 shadow-sm h-100"
                                                                    style="border-radius: var(--radius-lg); transition: all 0.3s;">
                                                                    <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=150&fit=crop"
                                                                        class="card-img-top" alt="Product"
                                                                        style="height: 100px; object-fit: cover; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                                                                    <div class="card-body p-2">
                                                                        <small class="text-muted d-block"
                                                                            style="font-size: 0.75rem; line-height: 1.3;">Laptop
                                                                            {{ $sub ?? 'Gaming' }}
                                                                            {{ $i }}</small>
                                                                        <strong class="text-danger"
                                                                            style="font-size: 0.85rem;">{{ number_format(15000000 + $i * 1000000) }}₫</strong>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </ul>
                                </li>
                            @endforeach
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2" href="#">
                                    <i class="bi bi-tag-fill me-2"></i> Khuyến mãi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2" href="#">
                                    <i class="bi bi-newspaper me-2"></i> Tin tức
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2" href="#">
                                    <i class="bi bi-info-circle me-2"></i> Giới thiệu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2" href="#">
                                    <i class="bi bi-telephone me-2"></i> Liên hệ
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
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js">
    </script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.min.js') }}?v={{ filemtime(public_path('js/adminlte.min.js')) }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    @stack('scripts')

    <!-- Initialize Bootstrap Dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownToggle = document.getElementById('userDropdown');
            var dropdownMenu = dropdownToggle?.nextElementSibling;

            if (dropdownToggle && dropdownMenu) {
                // Ensure dropdown is hidden initially
                dropdownMenu.style.display = 'none';

                // Try Bootstrap first
                if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                    try {
                        var dropdown = new bootstrap.Dropdown(dropdownToggle);
                        return;
                    } catch (e) {
                        console.log('Bootstrap dropdown failed, using fallback');
                    }
                }

                // Fallback: Manual dropdown toggle
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

</html>
