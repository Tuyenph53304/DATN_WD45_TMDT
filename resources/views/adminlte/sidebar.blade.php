<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
      <!--begin::Brand Link-->
      <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <!--begin::Brand Image-->
        <img
          src="{{ asset('assets/img/AdminLTELogo.png') }}"
          alt="AdminLTE Logo"
          class="brand-image opacity-75 shadow"
        />
        <!--end::Brand Image-->
        <!--begin::Brand Text-->
        <span class="brand-text fw-light">AdminLTE 4</span>
        <!--end::Brand Text-->
      </a>
      <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
          class="nav sidebar-menu flex-column"
          data-lte-toggle="treeview"
          role="menu"
          data-accordion="false"
        >
          <!--begin::Dashboard Menu Item-->
          <li class="nav-item">
            <a
              href="{{ route('admin.dashboard') }}"
              class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-speedometer2"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <!--end::Dashboard Menu Item-->

          <!--begin::Users Management Menu Item-->
          <li class="nav-item {{ request()->routeIs('admin.users*') ? 'menu-open' : '' }}">
            <a
              href="#"
              class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-people-fill"></i>
              <p>
                Quản lý Users
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a
                  href="{{ route('admin.users.index') }}"
                  class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Danh sách Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('admin.users.create') }}"
                  class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-person-plus-fill"></i>
                  <p>Tạo User mới</p>
                </a>
              </li>
            </ul>
          </li>
          <!--end::Users Management Menu Item-->

          <!--begin::Products Management Menu Item-->
          <li class="nav-item {{ request()->routeIs('admin.products*') ? 'menu-open' : '' }}">
            <a
              href="#"
              class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-box-seam"></i>
              <p>
                Quản lý Sản phẩm
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a
                  href="{{ route('admin.products.index') }}"
                  class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Danh sách Sản phẩm</p>
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('admin.products.create') }}"
                  class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-plus-circle"></i>
                  <p>Tạo Sản phẩm mới</p>
                </a>
              </li>
            </ul>
          </li>
          <!--end::Products Management Menu Item-->

          <!--begin::Categories Management Menu Item-->
          <li class="nav-item {{ request()->routeIs('admin.categories*') ? 'menu-open' : '' }}">
            <a
              href="#"
              class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-folder-fill"></i>
              <p>
                Quản lý Danh mục
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a
                  href="{{ route('admin.categories.index') }}"
                  class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Danh sách Danh mục</p>
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('admin.categories.create') }}"
                  class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-plus-circle"></i>
                  <p>Tạo Danh mục mới</p>
                </a>
              </li>
            </ul>
          </li>
          <!--end::Categories Management Menu Item-->

          <!--begin::Orders Management Menu Item-->
          <li class="nav-item {{ request()->routeIs('admin.orders*') ? 'menu-open' : '' }}">
            <a
              href="#"
              class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-receipt"></i>
              <p>
                Quản lý Đơn hàng
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a
                  href="{{ route('admin.orders.index') }}"
                  class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Danh sách Đơn hàng</p>
                </a>
              </li>
            </ul>
          </li>
          <!--end::Orders Management Menu Item-->

          <!--begin::Vouchers Management Menu Item-->
          <li class="nav-item {{ request()->routeIs('admin.vouchers*') ? 'menu-open' : '' }}">
            <a
              href="#"
              class="nav-link {{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}"
            >
              <i class="nav-icon bi bi-ticket-perforated"></i>
              <p>
                Quản lý Voucher
                <i class="nav-arrow bi bi-chevron-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a
                  href="{{ route('admin.vouchers.index') }}"
                  class="nav-link {{ request()->routeIs('admin.vouchers.index') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-list-ul"></i>
                  <p>Danh sách Voucher</p>
                </a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('admin.vouchers.create') }}"
                  class="nav-link {{ request()->routeIs('admin.vouchers.create') ? 'active' : '' }}"
                >
                  <i class="nav-icon bi bi-plus-circle"></i>
                  <p>Tạo Voucher mới</p>
                </a>
              </li>
            </ul>
          </li>
          <!--end::Vouchers Management Menu Item-->

          <!--begin::Home Menu Item-->
          <li class="nav-item">
            <a
              href="{{ route('home') }}"
              class="nav-link"
            >
              <i class="nav-icon bi bi-house-door"></i>
              <p>Trang chủ</p>
            </a>
          </li>
          <!--end::Home Menu Item-->
        </ul>
        <!--end::Sidebar Menu-->
      </nav>
    </div>
    <!--end::Sidebar Wrapper-->
  </aside>
  <!--end::Sidebar-->

