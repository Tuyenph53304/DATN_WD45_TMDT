@extends('user.layout')

@section('title', 'Đơn hàng của tôi - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-person me-2"></i> Thông tin tài khoản
            </a>
            <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action active">
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
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Đơn hàng của tôi</h5>
        </div>
        <div class="card-body">
          @if($orders->count() > 0)
            @foreach($orders as $order)
            <div class="border rounded p-3 mb-3">
              <div class="row align-items-center">
                <div class="col-md-3">
                  <div class="small text-muted">Mã đơn hàng</div>
                  <div class="fw-bold">#{{ $order->id }}</div>
                </div>
                <div class="col-md-2">
                  <div class="small text-muted">Ngày đặt</div>
                  <div>{{ $order->created_at->format('d/m/Y') }}</div>
                </div>
                <div class="col-md-2">
                  <div class="small text-muted">Tổng tiền</div>
                  <div class="fw-bold text-danger">{{ number_format($order->final_amount) }}₫</div>
                </div>
                <div class="col-md-2">
                  <div class="small text-muted">Trạng thái</div>
                  @php
                    $statusConfig = config('constants.order_status.' . $order->status, null);
                    $statusLabel = $statusConfig['label'] ?? $order->status;
                    $statusColor = $statusConfig['color'] ?? '#6B7280';
                  @endphp
                  <span class="badge" style="background-color: {{ $statusColor }};">{{ $statusLabel }}</span>
                </div>
                <div class="col-md-2">
                  <div class="small text-muted">Thanh toán</div>
                  @if($order->payment_status === 'paid')
                    <span class="badge bg-success">Đã thanh toán</span>
                  @else
                    <span class="badge bg-warning">Chưa thanh toán</span>
                  @endif
                </div>
                <div class="col-md-1 text-end">
                  <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
              </div>
              <hr class="my-2">
              <div class="small">
                <strong>Sản phẩm:</strong>
                @foreach($order->orderItems->take(3) as $item)
                  {{ $item->productVariant->product->name }}@if(!$loop->last), @endif
                @endforeach
                @if($order->orderItems->count() > 3)
                  và {{ $order->orderItems->count() - 3 }} sản phẩm khác
                @endif
              </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
              {{ $orders->links() }}
            </div>
          @else
            <div class="text-center py-5">
              <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
              <h5 class="mt-3">Bạn chưa có đơn hàng nào</h5>
              <p class="text-muted">Hãy bắt đầu mua sắm ngay!</p>
              <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

