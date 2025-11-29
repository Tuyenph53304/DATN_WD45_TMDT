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
                  href="{{ route('admin.users') }}"
                  class="nav-link {{ request()->routeIs('admin.users') && !request()->routeIs('admin.users.create') && !request()->routeIs('admin.users.edit') ? 'active' : '' }}"
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

