@extends('admin.layout')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Dashboard')

@push('styles')
<style>
  .chart-container {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--card-shadow);
    margin-bottom: 30px;
  }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="row g-4 mb-4">
  <!-- Users -->
  <div class="col-lg-3 col-md-6">
    <div class="stat-card primary">
      <div class="stat-icon primary">
        <i class="bi bi-people-fill"></i>
      </div>
      <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
      <div class="stat-label">Tổng số Users</div>
      <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-modern mt-3 w-100" style="background: #f1f5f9; color: #475569;">
        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <!-- Orders -->
  <div class="col-lg-3 col-md-6">
    <div class="stat-card success">
      <div class="stat-icon success">
        <i class="bi bi-receipt"></i>
      </div>
      <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
      <div class="stat-label">Tổng số Đơn hàng</div>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-modern mt-3 w-100" style="background: #f1f5f9; color: #475569;">
        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <!-- Revenue -->
  <div class="col-lg-3 col-md-6">
    <div class="stat-card warning">
      <div class="stat-icon warning">
        <i class="bi bi-currency-exchange"></i>
      </div>
      <div class="stat-value">{{ number_format(($totalRevenue ?? 0) / 1000000, 1) }}M</div>
      <div class="stat-label">Tổng Doanh thu</div>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-modern mt-3 w-100" style="background: #f1f5f9; color: #475569;">
        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>

  <!-- Products -->
  <div class="col-lg-3 col-md-6">
    <div class="stat-card info">
      <div class="stat-icon info">
        <i class="bi bi-box-seam"></i>
      </div>
      <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
      <div class="stat-label">Tổng số Sản phẩm</div>
      <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-modern mt-3 w-100" style="background: #f1f5f9; color: #475569;">
        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</div>

<!-- Additional Statistics -->
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-md-6">
    <div class="stat-card" style="border-left: 4px solid #ef4444;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-value" style="font-size: 1.5rem;">{{ $pendingOrders ?? 0 }}</div>
          <div class="stat-label">Đơn chờ xử lý</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
          <i class="bi bi-clock-history"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-card" style="border-left: 4px solid #3b82f6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-value" style="font-size: 1.5rem;">{{ $processingOrders ?? 0 }}</div>
          <div class="stat-label">Đơn đang xử lý</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
          <i class="bi bi-gear"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-card" style="border-left: 4px solid #10b981;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-value" style="font-size: 1.5rem;">{{ $completedOrders ?? 0 }}</div>
          <div class="stat-label">Đơn hoàn thành</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
          <i class="bi bi-check-circle"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-card" style="border-left: 4px solid #f59e0b;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-value" style="font-size: 1.5rem;">{{ number_format(($todayRevenue ?? 0) / 1000000, 1) }}M</div>
          <div class="stat-label">Doanh thu hôm nay</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
          <i class="bi bi-calendar-day"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders -->
<div class="modern-card">
  <div class="modern-card-header">
    <h3 class="modern-card-title">
      <i class="bi bi-receipt"></i>
      Đơn hàng gần đây
    </h3>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-gradient btn-modern">
      Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="table-responsive">
    <table class="table modern-table">
      <thead>
        <tr>
          <th>Mã đơn</th>
          <th>Khách hàng</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Thanh toán</th>
          <th>Ngày đặt</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($recentOrders) && $recentOrders->count() > 0)
          @foreach($recentOrders as $order)
          <tr>
            <td><strong>#{{ $order->id }}</strong></td>
            <td>{{ $order->user->name ?? 'N/A' }}</td>
            <td><strong>{{ number_format($order->final_amount) }}₫</strong></td>
            <td>
              @php
                $statusConfig = config('constants.order_status.' . $order->status, null);
                $statusLabel = $statusConfig['label'] ?? $order->status;
                $statusColor = $statusConfig['color'] ?? '#6B7280';
              @endphp
              <span class="badge badge-modern" style="background-color: {{ $statusColor }};">{{ $statusLabel }}</span>
            </td>
            <td>
              @if($order->payment_status === 'paid')
                <span class="badge badge-modern bg-success">Đã thanh toán</span>
              @else
                <span class="badge badge-modern bg-warning">Chưa thanh toán</span>
              @endif
            </td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-modern" style="background: var(--info-gradient); color: white;">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @endforeach
        @else
        <tr>
          <td colspan="7" class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
            <p class="mt-3 mb-0">Chưa có đơn hàng nào</p>
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection
