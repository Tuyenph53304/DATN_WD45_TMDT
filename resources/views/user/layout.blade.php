<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'Trang chủ')</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="@yield('title', 'Trang chủ')" />
    <meta name="author" content="AdminLTE" />
    <meta
      name="description"
      content="Trang web thương mại điện tử"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}?v={{ filemtime(public_path('css/adminlte.min.css')) }}" />
    <!--end::Required Plugin(AdminLTE)-->
    @stack('styles')
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body shadow-sm">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="{{ route('home') }}" class="navbar-brand">
                <img
                  src="{{ asset('assets/img/AdminLTELogo.png') }}"
                  alt="Logo"
                  class="brand-image"
                  style="height: 40px;"
                />
                <span class="brand-text fw-bold ms-2">E-Commerce</span>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i> Trang chủ
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-box-seam"></i> Sản phẩm
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-cart-fill"></i> Giỏ hàng
                <span class="badge text-bg-primary ms-1">0</span>
              </a>
            </li>
            @auth
              <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                  <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Tài khoản</a></li>
                  <li><a class="dropdown-item" href="#"><i class="bi bi-receipt"></i> Đơn hàng</a></li>
                  @if(Auth::user()->isAdmin())
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer"></i> Admin Panel</a></li>
                  @endif
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Đăng xuất</button>
                    </form>
                  </li>
                </ul>
              </li>
            @else
              <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">
                  <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('register') }}" class="btn btn-primary ms-2">
                  <i class="bi bi-person-plus"></i> Đăng ký
                </a>
              </li>
            @endauth
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
      <footer class="app-footer bg-body-secondary mt-5">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <strong>Copyright &copy; 2024 <a href="#">E-Commerce</a>.</strong>
              All rights reserved.
            </div>
            <div class="col-md-6 text-end">
              <b>Version</b> 1.0.0
            </div>
          </div>
        </div>
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-NRZchA7P2CnyKjXgCBo6E2pEjB25E9K+1x1h8kMHzqE="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEel66kUFT4PmyU/iibgs7cKtu77u1Y6e7MkLz79Dp0nuX6D1"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.min.js') }}?v={{ filemtime(public_path('js/adminlte.min.js')) }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    @stack('scripts')
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>

