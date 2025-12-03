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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
      <header class="bg-white shadow-sm sticky-top">
        <!-- Top Bar -->
        <div class="bg-primary text-white py-2">
          <div class="container-fluid">
            <div class="row align-items-center">
              <div class="col-md-6">
                <small><i class="bi bi-telephone-fill"></i> Hotline: 1900 1234 | <i class="bi bi-envelope-fill"></i> support@laptopstore.vn</small>
              </div>
              <div class="col-md-6 text-end">
                <small>Miễn phí vận chuyển cho đơn hàng trên 5 triệu</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
          <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
              <i class="bi bi-laptop text-primary" style="font-size: 2.5rem;"></i>
              <div class="ms-2">
                <h4 class="mb-0 fw-bold text-primary">LaptopStore</h4>
                <small class="text-muted">Cửa hàng laptop uy tín</small>
              </div>
            </a>

            <!-- Search Bar -->
            <div class="flex-grow-1 mx-4 d-none d-lg-block">
              <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Tìm kiếm laptop, phụ kiện..." aria-label="Search">
                <button class="btn btn-primary" type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </form>
            </div>

            <!-- Right Menu -->
            <div class="d-flex align-items-center">
              <a href="#" class="nav-link me-3 position-relative d-none d-md-block">
                <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
              </a>
              <a href="#" class="nav-link me-3 position-relative">
                <i class="bi bi-cart-fill text-primary" style="font-size: 1.5rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
              </a>
              @auth
                <div class="dropdown">
                  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1" style="font-size: 1.5rem;"></i>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
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
                </div>
              @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                  <i class="bi bi-box-arrow-in-right"></i> <span class="d-none d-md-inline">Đăng nhập</span>
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                  <i class="bi bi-person-plus"></i> <span class="d-none d-md-inline">Đăng ký</span>
                </a>
              @endauth
            </div>
          </div>
        </nav>

        <!-- Category Menu -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
          <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
              <span class="navbar-toggler-icon"></span> Danh mục
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-fill"></i> Trang chủ
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-laptop"></i> Laptop Gaming
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">ASUS ROG</a></li>
                    <li><a class="dropdown-item" href="#">MSI Gaming</a></li>
                    <li><a class="dropdown-item" href="#">Acer Predator</a></li>
                    <li><a class="dropdown-item" href="#">Lenovo Legion</a></li>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-briefcase"></i> Laptop Văn phòng
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Dell</a></li>
                    <li><a class="dropdown-item" href="#">HP</a></li>
                    <li><a class="dropdown-item" href="#">Lenovo ThinkPad</a></li>
                    <li><a class="dropdown-item" href="#">MacBook</a></li>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-palette"></i> Laptop Đồ họa
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">MacBook Pro</a></li>
                    <li><a class="dropdown-item" href="#">Dell XPS</a></li>
                    <li><a class="dropdown-item" href="#">HP ZBook</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">
                    <i class="bi bi-tag-fill"></i> Khuyến mãi
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">
                    <i class="bi bi-info-circle"></i> Giới thiệu
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">
                    <i class="bi bi-telephone"></i> Liên hệ
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
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
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
      <footer class="bg-dark text-white mt-5">
        <div class="container-fluid py-5">
          <div class="row">
            <!-- About -->
            <div class="col-lg-3 col-md-6 mb-4">
              <h5 class="mb-3">
                <i class="bi bi-laptop text-primary"></i> LaptopStore
              </h5>
              <p class="text-muted">Chuyên cung cấp laptop chính hãng, giá tốt nhất thị trường. Cam kết chất lượng và dịch vụ tốt nhất.</p>
              <div class="d-flex gap-2">
                <a href="#" class="text-white"><i class="bi bi-facebook" style="font-size: 1.5rem;"></i></a>
                <a href="#" class="text-white"><i class="bi bi-instagram" style="font-size: 1.5rem;"></i></a>
                <a href="#" class="text-white"><i class="bi bi-youtube" style="font-size: 1.5rem;"></i></a>
                <a href="#" class="text-white"><i class="bi bi-tiktok" style="font-size: 1.5rem;"></i></a>
              </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
              <h6 class="mb-3">Liên kết nhanh</h6>
              <ul class="list-unstyled">
                <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Trang chủ</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Giới thiệu</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Sản phẩm</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Khuyến mãi</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Tin tức</a></li>
              </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-6 mb-4">
              <h6 class="mb-3">Hỗ trợ</h6>
              <ul class="list-unstyled">
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Hướng dẫn mua hàng</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Chính sách đổi trả</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Bảo hành</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Vận chuyển</a></li>
                <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Thanh toán</a></li>
              </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-2 col-md-6 mb-4">
              <h6 class="mb-3">Liên hệ</h6>
              <ul class="list-unstyled text-muted">
                <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary"></i> 123 Đường ABC, Quận XYZ, TP.HCM</li>
                <li class="mb-2"><i class="bi bi-telephone-fill text-primary"></i> 1900 1234</li>
                <li class="mb-2"><i class="bi bi-envelope-fill text-primary"></i> support@laptopstore.vn</li>
                <li class="mb-2"><i class="bi bi-clock-fill text-primary"></i> 8:00 - 22:00 (Tất cả các ngày)</li>
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
              <p class="text-muted mb-0">&copy; 2024 LaptopStore. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-end">
              <img src="https://img.vietqr.io/image/970422-1234567890-compact2.png" alt="Payment" height="30" class="me-2">
              <small class="text-muted">Chấp nhận thanh toán online</small>
            </div>
          </div>
        </div>
      </footer>
      <!--end::Footer-->

      <!-- Customer Support Modal Button -->
      <button type="button" class="btn support-button position-fixed bottom-0 end-0 m-4" data-bs-toggle="modal" data-bs-target="#supportModal">
        <i class="bi bi-headset"></i>
      </button>

      <!-- Customer Support Modal -->
      <div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="supportModalLabel">
                <i class="bi bi-headset"></i> Chăm sóc khách hàng
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="text-center mb-4">
                <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                <h6 class="mt-2">Hỗ trợ trực tuyến 24/7</h6>
                <p class="text-muted">Chúng tôi luôn sẵn sàng hỗ trợ bạn!</p>
              </div>

              <div class="d-grid gap-2">
                <a href="tel:19001234" class="btn btn-outline-primary">
                  <i class="bi bi-telephone-fill"></i> Gọi ngay: 1900 1234
                </a>
                <a href="https://zalo.me/0123456789" target="_blank" class="btn btn-outline-success">
                  <i class="bi bi-chat-dots-fill"></i> Chat Zalo
                </a>
                <a href="https://m.me/laptopstore" target="_blank" class="btn btn-outline-primary">
                  <i class="bi bi-messenger"></i> Facebook Messenger
                </a>
                <a href="mailto:support@laptopstore.vn" class="btn btn-outline-danger">
                  <i class="bi bi-envelope-fill"></i> Email: support@laptopstore.vn
                </a>
              </div>

              <hr>

              <div>
                <h6 class="mb-3">Câu hỏi thường gặp</h6>
                <div class="accordion" id="faqAccordion">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Chính sách đổi trả như thế nào?
                      </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Chúng tôi hỗ trợ đổi trả trong vòng 7 ngày kể từ ngày nhận hàng với điều kiện sản phẩm còn nguyên vẹn, chưa sử dụng.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Thời gian bảo hành bao lâu?
                      </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Tất cả sản phẩm được bảo hành chính hãng từ 12-24 tháng tùy theo nhà sản xuất.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Phí vận chuyển là bao nhiêu?
                      </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div class="accordion-body">
                        Miễn phí vận chuyển cho đơn hàng trên 5 triệu. Dưới 5 triệu phí ship 30.000đ.
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

