@extends('user.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->id . ' - ' . config('constants.site.name'))

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
      <!-- Order Header -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Đơn hàng #{{ $order->id }}</h5>
          <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="small text-muted">Ngày đặt hàng</div>
              <div class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="col-md-6">
              <div class="small text-muted">Trạng thái đơn hàng</div>
              <div>
                @php
                  $statusConfig = config('constants.order_status.' . $order->status, null);
                  $statusLabel = $statusConfig['label'] ?? $order->status;
                  $statusColor = $statusConfig['color'] ?? '#6B7280';
                @endphp
                <span class="badge" style="background-color: {{ $statusColor }};">{{ $statusLabel }}</span>
                
                @if($order->canCancelByCustomer())
                  <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                      <i class="bi bi-x-circle me-1"></i> Hủy đơn
                    </button>
                  </form>
                @endif
                
                @if($order->canConfirmByCustomer())
                  <form action="{{ route('user.orders.confirmReceived', $order->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bạn đã nhận được hàng?')">
                      <i class="bi bi-check-circle me-1"></i> Xác nhận nhận hàng
                    </button>
                  </form>
                @endif
                
                @if($order->canReturnByCustomer())
                  <form action="{{ route('user.orders.return', $order->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Bạn có chắc muốn hoàn hàng?')">
                      <i class="bi bi-arrow-return-left me-1"></i> Hoàn hàng
                    </button>
                  </form>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="small text-muted">Phương thức thanh toán</div>
              <div>
                @if($order->payment_method === 'cod')
                  <i class="bi bi-cash-coin"></i> Thanh toán khi nhận hàng (COD)
                @else
                  <i class="bi bi-phone"></i> MoMo
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="small text-muted">Trạng thái thanh toán</div>
              <div>
                @if($order->payment_status === 'paid')
                  <span class="badge bg-success">Đã thanh toán</span>
                @else
                  <span class="badge bg-warning">Chưa thanh toán</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i> Sản phẩm</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Sản phẩm</th>
                  <th>Thuộc tính</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Giá</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img src="{{ $item->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&h=100&fit=crop' }}"
                           class="rounded me-3"
                           style="width: 60px; height: 60px; object-fit: cover;">
                      <div>
                        <div class="fw-semibold">{{ $item->productVariant->product->name }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="small text-muted">
                      @foreach($item->productVariant->attributeValues as $attrValue)
                        {{ $attrValue->value }}@if(!$loop->last), @endif
                      @endforeach
                    </div>
                  </td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td class="text-end">{{ number_format($item->price) }}₫</td>
                  <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity) }}₫</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="row">
        <div class="col-md-6 mb-4">
          <!-- Shipping Address -->
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
              <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Địa chỉ giao hàng</h6>
            </div>
            <div class="card-body">
              @if($order->shippingAddress)
                <div class="fw-semibold mb-2">{{ $order->shippingAddress->full_name }}</div>
                <div class="text-muted small mb-1">
                  <i class="bi bi-telephone me-1"></i> {{ $order->shippingAddress->phone }}
                </div>
                <div class="text-muted small">
                  <i class="bi bi-geo-alt me-1"></i> {{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}
                </div>
              @else
                <p class="text-muted">Không có thông tin địa chỉ</p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <!-- Order Total -->
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom">
              <h6 class="mb-0"><i class="bi bi-calculator me-2"></i> Tổng thanh toán</h6>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                <span>Tạm tính:</span>
                <span>{{ number_format($order->total_price) }}₫</span>
              </div>
              @if($order->voucher_code)
              <div class="d-flex justify-content-between mb-2">
                <span>Mã giảm giá ({{ $order->voucher_code }}):</span>
                <span class="text-danger">-{{ number_format($order->discount_amount) }}₫</span>
              </div>
              @endif
              <hr>
              <div class="d-flex justify-content-between">
                <span class="fw-bold">Tổng cộng:</span>
                <span class="fw-bold text-danger fs-5">{{ number_format($order->final_amount) }}₫</span>
              </div>
              @if($order->transaction_id)
              <div class="mt-3 pt-3 border-top">
                <div class="small text-muted">Mã giao dịch:</div>
                <code class="small">{{ $order->transaction_id }}</code>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

