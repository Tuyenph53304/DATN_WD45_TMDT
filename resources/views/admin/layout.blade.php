<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Panel - ' . config('constants.site.name'))</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      --info-gradient: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
      --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
      --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
      --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --card-hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar */
    .admin-sidebar {
      background: var(--sidebar-bg);
      min-height: 100vh;
      padding: 0;
      position: fixed;
      left: 0;
      top: 0;
      width: 280px;
      z-index: 1000;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .sidebar-header {
      padding: 25px 20px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-header h4 {
      color: white;
      font-weight: 700;
      font-size: 1.5rem;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sidebar-header h4 i {
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 1.8rem;
    }

    .sidebar-nav {
      padding: 20px 0;
    }

    .sidebar-nav .nav-item {
      margin: 5px 15px;
    }

    .sidebar-nav .nav-link {
      color: rgba(255, 255, 255, 0.7);
      padding: 14px 20px;
      border-radius: 12px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }

    .sidebar-nav .nav-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background: var(--primary-gradient);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .sidebar-nav .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transform: translateX(5px);
    }

    .sidebar-nav .nav-link:hover::before {
      transform: scaleY(1);
    }

    .sidebar-nav .nav-link.active {
      background: rgba(102, 126, 234, 0.2);
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .sidebar-nav .nav-link.active::before {
      transform: scaleY(1);
    }

    .sidebar-nav .nav-link i {
      font-size: 1.2rem;
      width: 24px;
      text-align: center;
    }

    /* Main Content */
    .admin-main {
      margin-left: 280px;
      min-height: 100vh;
      transition: all 0.3s ease;
    }

    /* Header */
    .admin-header {
      background: white;
      padding: 20px 35px;
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
      position: sticky;
      top: 0;
      z-index: 100;
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.95);
    }

    .admin-header .page-title {
      font-size: 1.75rem;
      font-weight: 700;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .search-box {
      position: relative;
    }

    .search-box input {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 10px 15px 10px 45px;
      width: 300px;
      transition: all 0.3s ease;
    }

    .search-box input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      outline: none;
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
    }

    .notification-btn {
      position: relative;
      width: 45px;
      height: 45px;
      border-radius: 12px;
      background: #f1f5f9;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      color: #64748b;
    }

    .notification-btn:hover {
      background: var(--primary-gradient);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ef4444;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    .user-dropdown {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 15px;
      border-radius: 12px;
      background: #f1f5f9;
      cursor: pointer;
      transition: all 0.3s ease;
      border: none;
    }

    .user-dropdown:hover {
      background: var(--primary-gradient);
      color: white;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--primary-gradient);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
    }

    /* Content Area */
    .admin-content {
      padding: 35px;
      background: transparent;
    }

    /* Cards */
    .stat-card {
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.8);
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--primary-gradient);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--card-hover-shadow);
    }

    .stat-card.primary::before { background: var(--primary-gradient); }
    .stat-card.success::before { background: var(--success-gradient); }
    .stat-card.warning::before { background: var(--warning-gradient); }
    .stat-card.info::before { background: var(--info-gradient); }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
      margin-bottom: 15px;
    }

    .stat-icon.primary { background: var(--primary-gradient); }
    .stat-icon.success { background: var(--success-gradient); }
    .stat-icon.warning { background: var(--warning-gradient); }
    .stat-icon.info { background: var(--info-gradient); }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #1e293b;
      margin: 10px 0;
    }

    .stat-label {
      color: #64748b;
      font-weight: 500;
      font-size: 0.95rem;
    }

    /* Modern Card */
    .modern-card {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(255, 255, 255, 0.8);
      margin-bottom: 30px;
    }

    .modern-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 20px;
      border-bottom: 2px solid #f1f5f9;
    }

    .modern-card-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1e293b;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .modern-card-title i {
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Buttons */
    .btn-modern {
      border-radius: 12px;
      padding: 12px 24px;
      font-weight: 600;
      transition: all 0.3s ease;
      border: none;
    }

    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-gradient {
      background: var(--primary-gradient);
      color: white;
    }

    .btn-gradient:hover {
      background: var(--primary-gradient);
      color: white;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    /* Table */
    .modern-table {
      width: 100%;
    }

    .modern-table thead {
      background: #f8fafc;
    }

    .modern-table thead th {
      padding: 15px;
      font-weight: 600;
      color: #475569;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.5px;
      border: none;
    }

    .modern-table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr:hover {
      background: #f8fafc;
      transform: scale(1.01);
    }

    .modern-table tbody td {
      padding: 18px 15px;
      color: #334155;
    }

    /* Badge */
    .badge-modern {
      padding: 6px 14px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.85rem;
    }

    /* Support Button */
    .support-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 65px;
      height: 65px;
      border-radius: 50%;
      background: var(--primary-gradient);
      border: none;
      color: white;
      font-size: 1.5rem;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      z-index: 999;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .support-btn:hover {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
      color: white;
    }

    /* Alerts */
    .alert-modern {
      border-radius: 15px;
      border: none;
      padding: 18px 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 25px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .admin-sidebar {
        transform: translateX(-100%);
      }

      .admin-main {
        margin-left: 0;
      }

      .search-box input {
        width: 200px;
      }
    }
  </style>

  @stack('styles')
</head>
<body>
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-header">
      <h4>
        <i class="bi bi-speedometer2"></i>
        BeeFast
      </h4>
    </div>
    <nav class="sidebar-nav">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Quản lý Users</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Quản lý Sản phẩm</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="bi bi-folder-fill"></i>
            <span>Quản lý Danh mục</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Quản lý Đơn hàng</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.vouchers.index') }}" class="nav-link {{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i>
            <span>Quản lý Voucher</span>
          </a>
        </li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="admin-main">
    <!-- Header -->
    <header class="admin-header d-flex justify-content-between align-items-center">
      <div>
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
      </div>
      <div class="header-actions">
        <div class="search-box">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Tìm kiếm..." class="form-control">
        </div>
        <button class="notification-btn" type="button">
          <i class="bi bi-bell"></i>
          <span class="notification-badge">3</span>
        </button>
        <div class="dropdown">
          <button class="user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
            <i class="bi bi-chevron-down"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 15px; margin-top: 10px;">
            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person me-2"></i> Hồ sơ</a></li>
            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i> Trang chủ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Content -->
    <div class="admin-content">
      @if(session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show">
          <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-modern alert-dismissible fade show">
          <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  <!-- Customer Support Button -->
  <button type="button" class="support-btn" data-bs-toggle="modal" data-bs-target="#adminSupportModal">
    <i class="bi bi-headset"></i>
  </button>

  <!-- Customer Support Modal -->
  <div class="modal fade" id="adminSupportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
        <div class="modal-header text-white" style="background: var(--primary-gradient); padding: 25px 30px;">
          <h5 class="modal-title" style="font-weight: 700; font-size: 1.5rem;">
            <i class="bi bi-headset me-2"></i> Chăm sóc khách hàng
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="padding: 30px;">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="stat-card" style="text-align: center;">
                <div class="stat-icon primary mx-auto">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <h6 class="mt-3 mb-2">Hotline</h6>
                <p class="mb-3"><strong>{{ config('constants.contact.hotline') }}</strong></p>
                <a href="tel:{{ str_replace(' ', '', config('constants.contact.hotline')) }}" class="btn btn-gradient btn-modern">
                  <i class="bi bi-telephone me-1"></i> Gọi ngay
                </a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="stat-card" style="text-align: center;">
                <div class="stat-icon success mx-auto">
                  <i class="bi bi-envelope-fill"></i>
                </div>
                <h6 class="mt-3 mb-2">Email</h6>
                <p class="mb-3"><strong>{{ config('constants.contact.email') }}</strong></p>
                <a href="mailto:{{ config('constants.contact.email') }}" class="btn btn-modern" style="background: var(--success-gradient); color: white;">
                  <i class="bi bi-envelope me-1"></i> Gửi email
                </a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="stat-card" style="text-align: center;">
                <div class="stat-icon warning mx-auto">
                  <i class="bi bi-chat-dots-fill"></i>
                </div>
                <h6 class="mt-3 mb-2">Zalo</h6>
                <a href="{{ config('constants.contact.zalo') }}" target="_blank" class="btn btn-modern" style="background: var(--warning-gradient); color: white;">
                  <i class="bi bi-chat-dots me-1"></i> Chat Zalo
                </a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="stat-card" style="text-align: center;">
                <div class="stat-icon info mx-auto">
                  <i class="bi bi-messenger"></i>
                </div>
                <h6 class="mt-3 mb-2">Messenger</h6>
                <a href="{{ config('constants.contact.messenger') }}" target="_blank" class="btn btn-modern" style="background: var(--info-gradient); color: white;">
                  <i class="bi bi-messenger me-1"></i> Chat Messenger
                </a>
              </div>
            </div>
          </div>
          <hr class="my-4">
          <div class="p-3" style="background: #f8fafc; border-radius: 15px;">
            <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i> Thông tin liên hệ</h6>
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i> {{ config('constants.contact.address') }}</li>
              <li class="mb-0"><i class="bi bi-clock-fill text-primary me-2"></i> {{ config('constants.contact.working_hours') }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
