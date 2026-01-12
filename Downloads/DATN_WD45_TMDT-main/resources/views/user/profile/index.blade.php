@extends('user.layout')

@section('title', 'Thông tin tài khoản - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action active">
              <i class="bi bi-person me-2"></i> Thông tin tài khoản
            </a>
            <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-receipt me-2"></i> Đơn hàng của tôi
            </a>
            <a href="{{ route('user.profile.edit') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-pencil me-2"></i> Chỉnh sửa thông tin
            </a>
            <form action="{{ route('logout') }}" method="POST" class="list-group-item p-0">
              @csrf
              <button type="submit" class="list-group-item list-group-item-action border-0 w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
      <!-- Profile Header -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px;">
                <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-4">
              <h4 class="mb-1">{{ $user->name }}</h4>
              <p class="text-muted mb-0">{{ $user->email }}</p>
              <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="text-end">
              <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i> Chỉnh sửa
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics -->
      <div class="row mb-4">
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
              <h3 class="mt-2 mb-0">{{ $totalOrders }}</h3>
              <p class="text-muted mb-0">Tổng đơn hàng</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <i class="bi bi-currency-exchange text-success" style="font-size: 2rem;"></i>
              <h3 class="mt-2 mb-0">{{ number_format($totalSpent) }}₫</h3>
              <p class="text-muted mb-0">Tổng đã chi</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
              <h3 class="mt-2 mb-0">{{ $pendingOrders }}</h3>
              <p class="text-muted mb-0">Đơn chờ xử lý</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Personal Information -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Thông tin cá nhân</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Họ và tên:</div>
            <div class="col-md-9">{{ $user->name }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Email:</div>
            <div class="col-md-9">{{ $user->email }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Số điện thoại:</div>
            <div class="col-md-9">{{ $user->phone_number ?? 'Chưa cập nhật' }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-semibold">Địa chỉ:</div>
            <div class="col-md-9">{{ $user->address ?? 'Chưa cập nhật' }}</div>
          </div>
          <div class="row">
            <div class="col-md-3 fw-semibold">Ngày tham gia:</div>
            <div class="col-md-9">{{ $user->created_at->format('d/m/Y') }}</div>
          </div>
        </div>
      </div>

      <!-- Shipping Addresses -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Địa chỉ giao hàng</h5>
          <a href="#" class="btn btn-sm btn-primary">
            <i class="bi bi-plus me-1"></i> Thêm địa chỉ
          </a>
        </div>
        <div class="card-body">
          @if($user->shippingAddresses->count() > 0)
            @foreach($user->shippingAddresses as $address)
            <div class="border rounded p-3 mb-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-semibold mb-1">
                    {{ $address->full_name }}
                    @if($address->default)
                      <span class="badge bg-primary ms-2">Mặc định</span>
                    @endif
                  </div>
                  <div class="text-muted small mb-1">
                    <i class="bi bi-telephone me-1"></i> {{ $address->phone }}
                  </div>
                  <div class="text-muted small">
                    <i class="bi bi-geo-alt me-1"></i> {{ $address->address }}, {{ $address->city }}
                  </div>
                </div>
                <div>
                  <a href="#" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <p class="text-muted text-center py-3">Bạn chưa có địa chỉ giao hàng nào.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

