@extends('admin.layout')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css">
<style>
  .chart-container {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--card-shadow);
    margin-bottom: 30px;
    position: relative;
    height: 400px;
  }

  .chart-container canvas {
    max-height: 350px;
  }

  .period-selector {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .period-btn {
    padding: 8px 20px;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #64748b;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
  }

  .period-btn:hover {
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
  }

  .period-btn.active {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
  }

  .stat-mini-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-left: 4px solid;
    transition: all 0.3s ease;
  }

  .stat-mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
  }

  .stat-mini-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    margin: 8px 0;
  }

  .stat-mini-label {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 500;
  }

  .stat-trend {
    font-size: 0.85rem;
    margin-top: 5px;
  }

  .stat-trend.up {
    color: #10b981;
  }

  .stat-trend.down {
    color: #ef4444;
  }
</style>
@endpush

@section('content')
<!-- Period Selector -->
<div class="period-selector">
  <a href="{{ route('admin.dashboard', ['period' => 'day']) }}" 
     class="period-btn {{ ($period ?? 'month') == 'day' ? 'active' : '' }}">
    <i class="bi bi-calendar-day me-1"></i> Ngày
  </a>
  <a href="{{ route('admin.dashboard', ['period' => 'week']) }}" 
     class="period-btn {{ ($period ?? 'month') == 'week' ? 'active' : '' }}">
    <i class="bi bi-calendar-week me-1"></i> Tuần
  </a>
  <a href="{{ route('admin.dashboard', ['period' => 'month']) }}" 
     class="period-btn {{ ($period ?? 'month') == 'month' ? 'active' : '' }}">
    <i class="bi bi-calendar-month me-1"></i> Tháng
  </a>
  <a href="{{ route('admin.dashboard', ['period' => 'year']) }}" 
     class="period-btn {{ ($period ?? 'month') == 'year' ? 'active' : '' }}">
    <i class="bi bi-calendar-year me-1"></i> Năm
  </a>
</div>

<!-- Main Statistics Cards -->
<div class="row g-4 mb-4">
  <!-- Users -->
  <div class="col-lg-3 col-md-6">
    <div class="stat-card primary">
      <div class="stat-icon primary">
        <i class="bi bi-people-fill"></i>
      </div>
      <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
      <div class="stat-label">Tổng số Users</div>
      <div class="stat-trend up">
        <i class="bi bi-arrow-up"></i> 
        @if(($period ?? 'month') == 'day')
          Hôm nay: +{{ $newUsersToday ?? 0 }}
        @elseif(($period ?? 'month') == 'week')
          Tuần này: +{{ $newUsersThisWeek ?? 0 }}
        @elseif(($period ?? 'month') == 'month')
          Tháng này: +{{ $newUsersThisMonth ?? 0 }}
        @else
          Năm này: +{{ $newUsersThisYear ?? 0 }}
        @endif
      </div>
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
      <div class="stat-value">{{ number_format($totalOrders ?? 0) }}</div>
      <div class="stat-label">Tổng số Đơn hàng</div>
      <div class="stat-trend up">
        <i class="bi bi-arrow-up"></i> 
        @if(($period ?? 'month') == 'day')
          Hôm nay: {{ $ordersToday ?? 0 }}
        @elseif(($period ?? 'month') == 'week')
          Tuần này: {{ $ordersThisWeek ?? 0 }}
        @elseif(($period ?? 'month') == 'month')
          Tháng này: {{ $ordersThisMonth ?? 0 }}
        @else
          Năm này: {{ $ordersThisYear ?? 0 }}
        @endif
      </div>
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
      <div class="stat-trend up">
        <i class="bi bi-arrow-up"></i> 
        @if(($period ?? 'month') == 'day')
          Hôm nay: {{ number_format(($todayRevenue ?? 0) / 1000, 0) }}K
        @elseif(($period ?? 'month') == 'week')
          Tuần này: {{ number_format(($weekRevenue ?? 0) / 1000000, 1) }}M
        @elseif(($period ?? 'month') == 'month')
          Tháng này: {{ number_format(($monthRevenue ?? 0) / 1000000, 1) }}M
        @else
          Năm này: {{ number_format(($yearRevenue ?? 0) / 1000000, 1) }}M
        @endif
      </div>
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
      <div class="stat-value">{{ number_format($totalProducts ?? 0) }}</div>
      <div class="stat-label">Tổng số Sản phẩm</div>
      <div class="stat-trend">
        <i class="bi bi-check-circle"></i> Đang hoạt động: {{ $activeProducts ?? 0 }}
      </div>
      <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-modern mt-3 w-100" style="background: #f1f5f9; color: #475569;">
        Xem chi tiết <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</div>

<!-- Additional Statistics Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #ef4444;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ $pendingOrders ?? 0 }}</div>
          <div class="stat-mini-label">Đơn chờ xử lý</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-clock-history"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #3b82f6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ ($confirmedOrders ?? 0) + ($shippingOrders ?? 0) }}</div>
          <div class="stat-mini-label">Đơn đang xử lý</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-gear"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #10b981;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ $completedOrders ?? 0 }}</div>
          <div class="stat-mini-label">Đơn hoàn thành</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-check-circle"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #f59e0b;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ number_format(($avgOrderValue ?? 0) / 1000, 0) }}K</div>
          <div class="stat-mini-label">Giá trị đơn TB</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-cart-check"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- More Statistics -->
<div class="row g-4 mb-4">
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #8b5cf6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ number_format($conversionRate ?? 0, 1) }}%</div>
          <div class="stat-mini-label">Tỷ lệ chuyển đổi</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-graph-up-arrow"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #06b6d4;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ $activeVouchers ?? 0 }}</div>
          <div class="stat-mini-label">Voucher đang hoạt động</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-ticket-perforated"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #ec4899;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ $totalCategories ?? 0 }}</div>
          <div class="stat-mini-label">Danh mục sản phẩm</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-folder-fill"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-mini-card" style="border-left-color: #14b8a6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-mini-value">{{ $cancelledOrders ?? 0 }}</div>
          <div class="stat-mini-label">Đơn đã hủy</div>
        </div>
        <div class="stat-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="bi bi-x-circle"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
  <!-- Revenue Chart -->
  <div class="col-lg-8">
    <div class="chart-container">
      <h5 class="mb-3" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-graph-up text-primary me-2"></i>
        Doanh thu theo thời gian
      </h5>
      <canvas id="revenueChart"></canvas>
    </div>
  </div>

  <!-- Orders by Status Chart -->
  <div class="col-lg-4">
    <div class="chart-container">
      <h5 class="mb-3" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-pie-chart text-success me-2"></i>
        Đơn hàng theo trạng thái
      </h5>
      <canvas id="ordersByStatusChart"></canvas>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <!-- Orders Chart -->
  <div class="col-lg-6">
    <div class="chart-container">
      <h5 class="mb-3" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-bar-chart text-info me-2"></i>
        Đơn hàng theo thời gian
      </h5>
      <canvas id="ordersChart"></canvas>
    </div>
  </div>

  <!-- Payment Method Chart -->
  <div class="col-lg-6">
    <div class="chart-container">
      <h5 class="mb-3" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-credit-card text-warning me-2"></i>
        Phương thức thanh toán
      </h5>
      <canvas id="paymentMethodChart"></canvas>
    </div>
  </div>
</div>

<!-- Top Products and Recent Orders -->
<div class="row g-4 mb-4">
  <!-- Top Products -->
  <div class="col-lg-6">
    <div class="modern-card">
      <div class="modern-card-header">
        <h3 class="modern-card-title">
          <i class="bi bi-trophy-fill"></i>
          Top sản phẩm bán chạy
        </h3>
      </div>
      <div class="table-responsive">
        <table class="table modern-table">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên sản phẩm</th>
              <th>Đã bán</th>
              <th>Doanh thu</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($topProducts) && $topProducts->count() > 0)
              @foreach($topProducts as $index => $product)
              <tr>
                <td><strong>#{{ $index + 1 }}</strong></td>
                <td>{{ $product->name }}</td>
                <td><span class="badge badge-modern bg-primary">{{ $product->total_sold }}</span></td>
                <td><strong>{{ number_format($product->total_revenue) }}₫</strong></td>
              </tr>
              @endforeach
            @else
            <tr>
              <td colspan="4" class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">Chưa có dữ liệu</p>
              </td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Recent Orders -->
  <div class="col-lg-6">
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
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($recentOrders) && $recentOrders->count() > 0)
              @foreach($recentOrders->take(5) as $order)
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
                  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-modern" style="background: var(--info-gradient); color: white;">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            @else
            <tr>
              <td colspan="5" class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">Chưa có đơn hàng nào</p>
              </td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  // Revenue Chart
  const revenueCtx = document.getElementById('revenueChart');
  const period = '{{ $period ?? "month" }}';
  
  let revenueLabels = [];
  let revenueData = [];
  
  @if(($period ?? 'month') == 'day')
    @if(isset($dailyRevenue) && $dailyRevenue->count() > 0)
      revenueLabels = {!! json_encode($dailyRevenue->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d/m'); })) !!};
      revenueData = {!! json_encode($dailyRevenue->pluck('revenue')) !!};
    @endif
  @elseif(($period ?? 'month') == 'week')
    @if(isset($weeklyRevenue) && $weeklyRevenue->count() > 0)
      revenueLabels = {!! json_encode($weeklyRevenue->map(function($item) { return 'Tuần ' . $item->week; })) !!};
      revenueData = {!! json_encode($weeklyRevenue->pluck('revenue')) !!};
    @endif
  @elseif(($period ?? 'month') == 'month')
    @if(isset($monthlyRevenue) && $monthlyRevenue->count() > 0)
      revenueLabels = {!! json_encode($monthlyRevenue->pluck('month')->map(function($month) { return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('m/Y'); })) !!};
      revenueData = {!! json_encode($monthlyRevenue->pluck('revenue')) !!};
    @endif
  @else
    @if(isset($yearlyRevenue) && $yearlyRevenue->count() > 0)
      revenueLabels = {!! json_encode($yearlyRevenue->pluck('year')) !!};
      revenueData = {!! json_encode($yearlyRevenue->pluck('revenue')) !!};
    @endif
  @endif

  if (revenueCtx && revenueLabels.length > 0) {
    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Doanh thu (₫)',
          data: revenueData,
          borderColor: 'rgb(102, 126, 234)',
          backgroundColor: 'rgba(102, 126, 234, 0.1)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value) + '₫';
              }
            }
          }
        }
      }
    });
  }

  // Orders by Status Chart
  const ordersByStatusCtx = document.getElementById('ordersByStatusChart');
  @if(isset($ordersByStatus) && $ordersByStatus->count() > 0)
    const statusLabels = {!! json_encode($ordersByStatus->pluck('status')->map(function($status) {
      $config = config('constants.order_status.' . $status, null);
      return $config ? $config['label'] : $status;
    })) !!};
    const statusData = {!! json_encode($ordersByStatus->pluck('count')) !!};
    const statusColors = {!! json_encode($ordersByStatus->map(function($item) {
      $config = config('constants.order_status.' . $item->status, null);
      return $config ? $config['color'] : '#6B7280';
    })) !!};

    if (ordersByStatusCtx && statusLabels.length > 0) {
      new Chart(ordersByStatusCtx, {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusData,
            backgroundColor: statusColors,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
            }
          }
        }
      });
    }
  @endif

  // Orders Chart
  const ordersCtx = document.getElementById('ordersChart');
  
  let ordersLabels = [];
  let ordersData = [];
  
  @if(($period ?? 'month') == 'day')
    @if(isset($dailyRevenue) && $dailyRevenue->count() > 0)
      ordersLabels = {!! json_encode($dailyRevenue->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d/m'); })) !!};
      ordersData = {!! json_encode($dailyRevenue->pluck('orders')) !!};
    @endif
  @elseif(($period ?? 'month') == 'week')
    @if(isset($weeklyRevenue) && $weeklyRevenue->count() > 0)
      ordersLabels = {!! json_encode($weeklyRevenue->map(function($item) { return 'Tuần ' . $item->week; })) !!};
      ordersData = {!! json_encode($weeklyRevenue->pluck('orders')) !!};
    @endif
  @elseif(($period ?? 'month') == 'month')
    @if(isset($monthlyRevenue) && $monthlyRevenue->count() > 0)
      ordersLabels = {!! json_encode($monthlyRevenue->pluck('month')->map(function($month) { return \Carbon\Carbon::createFromFormat('Y-m', $month)->format('m/Y'); })) !!};
      ordersData = {!! json_encode($monthlyRevenue->pluck('orders')) !!};
    @endif
  @else
    @if(isset($yearlyRevenue) && $yearlyRevenue->count() > 0)
      ordersLabels = {!! json_encode($yearlyRevenue->pluck('year')) !!};
      ordersData = {!! json_encode($yearlyRevenue->pluck('orders')) !!};
    @endif
  @endif

  if (ordersCtx && ordersLabels.length > 0) {
    new Chart(ordersCtx, {
      type: 'bar',
      data: {
        labels: ordersLabels,
        datasets: [{
          label: 'Số đơn hàng',
          data: ordersData,
          backgroundColor: 'rgba(79, 172, 254, 0.8)',
          borderColor: 'rgb(79, 172, 254)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  }

  // Payment Method Chart
  const paymentMethodCtx = document.getElementById('paymentMethodChart');
  @if(isset($ordersByPaymentMethod) && $ordersByPaymentMethod->count() > 0)
    const paymentLabels = {!! json_encode($ordersByPaymentMethod->pluck('payment_method')) !!};
    const paymentData = {!! json_encode($ordersByPaymentMethod->pluck('count')) !!};
    const paymentColors = ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'];

    if (paymentMethodCtx && paymentLabels.length > 0) {
      new Chart(paymentMethodCtx, {
        type: 'pie',
        data: {
          labels: paymentLabels,
          datasets: [{
            data: paymentData,
            backgroundColor: paymentColors.slice(0, paymentLabels.length),
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.parsed || 0;
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                  return label + ': ' + value + ' (' + percentage + '%)';
                }
              }
            }
          }
        }
      });
    }
  @endif
</script>
@endpush
