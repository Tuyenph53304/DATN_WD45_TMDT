@extends('user.layout')

@section('title', 'Thanh toán thành công - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-lg">
        <div class="card-body text-center p-5">
          <!-- Success Icon -->
          <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success" style="width: 100px; height: 100px;">
              <i class="bi bi-check-circle-fill text-white" style="font-size: 4rem;"></i>
            </div>
          </div>

          @if($order->payment_method === 'cod')
            <h2 class="fw-bold text-success mb-3">Đặt hàng thành công!</h2>
            <p class="text-muted mb-4">Cảm ơn bạn đã đặt hàng tại {{ config('constants.site.name') }}</p>
          @else
            <h2 class="fw-bold text-success mb-3">Thanh toán thành công!</h2>
            <p class="text-muted mb-4">Cảm ơn bạn đã mua sắm tại {{ config('constants.site.name') }}</p>
          @endif

          <!-- Payment Transaction Card -->
          <div class="card border-success mb-4" style="border-width: 2px;">
            <div class="card-header bg-success text-white">
              <h5 class="mb-0"><i class="bi bi-credit-card"></i> Thông tin giao dịch</h5>
            </div>
            <div class="card-body">
              @if($order->transaction_id)
              <div class="row mb-2">
                <div class="col-5 fw-semibold">Mã giao dịch:</div>
                <div class="col-7">
                  <code class="text-primary">{{ $order->transaction_id }}</code>
                </div>
              </div>
              @endif
              <div class="row mb-2">
                <div class="col-5 fw-semibold">Mã đơn hàng:</div>
                <div class="col-7">
                  <strong>#{{ $order->id }}</strong>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-5 fw-semibold">Số tiền thanh toán:</div>
                <div class="col-7">
                  <span class="text-danger fw-bold fs-5">{{ number_format($order->final_amount) }}₫</span>
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-5 fw-semibold">Thời gian thanh toán:</div>
                <div class="col-7">{{ $order->updated_at->format('d/m/Y H:i:s') }}</div>
              </div>
              <div class="row">
                <div class="col-5 fw-semibold">Trạng thái:</div>
                <div class="col-7">
                  @if($order->payment_status === 'paid')
                    <span class="badge bg-success fs-6">
                      <i class="bi bi-check-circle"></i> Đã thanh toán
                    </span>
                  @elseif($order->payment_status === 'unpaid')
                    <span class="badge bg-warning fs-6">
                      <i class="bi bi-hourglass-split"></i> Chưa thanh toán
                    </span>
                  @else
                    <span class="badge bg-info fs-6">
                      <i class="bi bi-clock"></i> {{ $order->payment_status }}
                    </span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- Order Info -->
          <div class="card bg-light mb-4">
            <div class="card-body text-start">
              <h5 class="fw-bold mb-3"><i class="bi bi-receipt"></i> Thông tin đơn hàng</h5>
              <div class="row mb-2">
                <div class="col-4 fw-semibold">Mã đơn hàng:</div>
                <div class="col-8">#{{ $order->id }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 fw-semibold">Ngày đặt:</div>
                <div class="col-8">{{ $order->created_at->format('d/m/Y H:i') }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-4 fw-semibold">Tổng tiền:</div>
                <div class="col-8 text-danger fw-bold">{{ number_format($order->final_amount) }}₫</div>
              </div>
              @if($order->voucher_code)
              <div class="row mb-2">
                <div class="col-4 fw-semibold">Mã giảm giá:</div>
                <div class="col-8">{{ $order->voucher_code }} (Giảm {{ number_format($order->discount_amount) }}₫)</div>
              </div>
              @endif
              <div class="row mb-2">
                <div class="col-4 fw-semibold">Phương thức:</div>
                <div class="col-8">
                  @if($order->payment_method === 'cod')
                    <i class="bi bi-cash-coin"></i> Thanh toán khi nhận hàng (COD)
                  @else
                    <i class="bi bi-phone"></i> MoMo
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-4 fw-semibold">Trạng thái thanh toán:</div>
                <div class="col-8">
                  @if($order->payment_status === 'paid')
                    <span class="badge bg-success">Đã thanh toán</span>
                  @elseif($order->payment_status === 'unpaid')
                    <span class="badge bg-warning">Chưa thanh toán</span>
                  @else
                    <span class="badge bg-info">{{ $order->payment_status }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="card mb-4">
            <div class="card-header bg-primary text-white">
              <h6 class="mb-0"><i class="bi bi-box-seam"></i> Sản phẩm đã mua</h6>
            </div>
            <div class="card-body">
              @foreach($order->orderItems as $item)
              <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <img src="{{ $item->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&h=100&fit=crop' }}"
                     class="rounded me-3"
                     style="width: 80px; height: 80px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-1">{{ $item->productVariant->product->name }}</h6>
                  <div class="small text-muted">
                    @foreach($item->productVariant->attributeValues as $attrValue)
                      {{ $attrValue->value }}@if(!$loop->last), @endif
                    @endforeach
                  </div>
                  <div class="mt-1">
                    <span class="fw-bold">{{ number_format($item->price) }}₫</span> x {{ $item->quantity }}
                  </div>
                </div>
                <div class="text-end">
                  <div class="fw-bold">{{ number_format($item->price * $item->quantity) }}₫</div>
                </div>
              </div>
              @endforeach
            </div>
          </div>

          <!-- Shipping Address -->
          @if($order->shippingAddress)
          <div class="card mb-4">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Địa chỉ giao hàng</h6>
            </div>
            <div class="card-body text-start">
              <div class="fw-semibold">{{ $order->shippingAddress->full_name }}</div>
              <div class="text-muted">{{ $order->shippingAddress->phone }}</div>
              <div class="text-muted">{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}</div>
            </div>
          </div>
          @endif

          <!-- Actions -->
          <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
              <i class="bi bi-house"></i> Về trang chủ
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
              <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

