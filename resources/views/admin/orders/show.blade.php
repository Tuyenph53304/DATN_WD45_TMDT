@extends('admin.layout')

@section('title', 'Chi tiết Đơn hàng - Admin Panel')
@section('page-title', 'Chi tiết Đơn hàng #' . $order->id)

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-receipt me-2"></i> Thông tin Đơn hàng</h3>
        <div class="card-tools">
          <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-8">
            <table class="table table-bordered">
              <tr>
                <th width="200">Mã đơn hàng</th>
                <td>#{{ $order->id }}</td>
              </tr>
              <tr>
                <th>Khách hàng</th>
                <td>{{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})</td>
              </tr>
              <tr>
                <th>Tổng tiền</th>
                <td><strong>{{ number_format($order->total_price) }}₫</strong></td>
              </tr>
              @if($order->voucher_code)
              <tr>
                <th>Mã giảm giá</th>
                <td>{{ $order->voucher_code }} (Giảm {{ number_format($order->discount_amount) }}₫)</td>
              </tr>
              @endif
              <tr>
                <th>Thành tiền</th>
                <td><strong class="text-danger">{{ number_format($order->final_amount) }}₫</strong></td>
              </tr>
              <tr>
                <th>Trạng thái</th>
                <td>
                  @php
                    $isLocked = $order->isFinalStatus();
                    $statusConfig = config('constants.order_status.' . $order->status, null);
                    $statusLabel = $statusConfig['label'] ?? $order->status;
                    $statusColor = $statusConfig['color'] ?? '#6B7280';
                    $statusBadge = [
                      'pending_confirmation' => 'bg-warning',
                      'confirmed' => 'bg-info',
                      'shipping' => 'bg-primary',
                      'delivered' => 'bg-info',
                      'completed' => 'bg-success',
                      'cancelled' => 'bg-danger',
                      'delivery_failed' => 'bg-danger',
                    ];
                    $badgeClass = $statusBadge[$order->status] ?? 'bg-secondary';
                    $canTransitionTo = $statusConfig['can_transition_to'] ?? [];
                  @endphp

                  <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge {{ $badgeClass }}" style="background-color: {{ $statusColor }};">
                      {{ $statusLabel }}
                    </span>

                    @if($order->cancelled_request)
                      <span class="badge bg-warning">
                        <i class="bi bi-hourglass-split me-1"></i> Khách hàng yêu cầu hủy
                      </span>
                    @endif

                    @if($isLocked)
                      <small class="text-danger fw-bold">
                        <i class="bi bi-lock-fill me-1"></i> Không thể sửa
                      </small>
                    @else
                      @if($order->status === 'pending_confirmation')
                        {{-- Khi pending_confirmation, luôn hiển thị nút xác nhận đơn hàng (khách hàng sẽ hủy trực tiếp) --}}
                        <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle me-1"></i> Xác nhận đơn hàng
                          </button>
                        </form>
                      @elseif($order->cancelled_request)
                        {{-- Khi có yêu cầu hủy từ confirmed trở đi, hiển thị nút xác nhận hủy --}}
                        <form action="{{ route('admin.orders.confirmCancel', $order) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xác nhận hủy đơn hàng này?')">
                            <i class="bi bi-x-circle me-1"></i> Xác nhận hủy
                          </button>
                        </form>
                      @else
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <select name="status" class="form-select form-select-sm d-inline-block w-auto" style="min-width: 180px;" onchange="this.form.submit()">
                            <option value="">-- Chuyển trạng thái --</option>
                            @foreach($canTransitionTo as $nextStatus)
                              @php
                                $nextStatusConfig = config('constants.order_status.' . $nextStatus, null);
                                $nextStatusLabel = $nextStatusConfig['label'] ?? $nextStatus;
                              @endphp
                              <option value="{{ $nextStatus }}">→ {{ $nextStatusLabel }}</option>
                            @endforeach
                          </select>
                        </form>
                      @endif
                    @endif
                  </div>

                  @if($isLocked)
                    <div class="alert alert-warning mt-2 mb-0 py-2 px-3">
                      <i class="bi bi-exclamation-triangle me-2"></i>
                      <strong>Luồng mua hàng đã kết thúc.</strong> Không thể cập nhật trạng thái đơn hàng đã {{ $statusLabel }}.
                    </div>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Thanh toán</th>
                <td>
                  @if($order->payment_status === 'paid')
                    <span class="badge bg-success">Đã thanh toán</span>
                  @elseif($order->payment_status === 'unpaid')
                    <span class="badge bg-warning">Chưa thanh toán</span>
                  @elseif($order->payment_status === 'pending')
                    <span class="badge bg-info">Đang chờ thanh toán</span>
                  @elseif($order->payment_status === 'failed')
                    <span class="badge bg-danger">Thanh toán thất bại</span>
                  @else
                    <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                  @endif
                </td>
              </tr>
              <tr>
                <th>Phương thức</th>
                <td>{{ $order->payment_method === 'cod' ? 'COD' : 'MoMo' }}</td>
              </tr>
              @if($order->transaction_id)
              <tr>
                <th>Mã giao dịch</th>
                <td><code>{{ $order->transaction_id }}</code></td>
              </tr>
              @endif
              <tr>
                <th>Ngày đặt</th>
                <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
              </tr>
              @if($order->cancel_reason)
              <tr>
                <th>Lý do hủy</th>
                <td>
                  <div class="alert alert-warning mb-0 py-2">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ $order->cancel_reason }}
                  </div>
                </td>
              </tr>
              @endif
              <tr>
                <th>Địa chỉ giao hàng</th>
                <td>
                  @if($order->shippingAddress)
                    <div>
                      <strong>{{ $order->shippingAddress->full_name }}</strong><br>
                      <i class="bi bi-telephone me-1"></i> {{ $order->shippingAddress->phone }}<br>
                      <i class="bi bi-geo-alt me-1"></i> {{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}
                    </div>
                  @else
                    <p class="text-muted mb-0">Không có thông tin địa chỉ</p>
                  @endif
                </td>
              </tr>
            </table>
          </div>
        </div>

        <h5 class="mt-4 mb-3">Sản phẩm trong đơn:</h5>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->orderItems as $item)
              <tr>
                <td>
                  {{ $item->productVariant->product->name }}
                  @if($item->productVariant->attributeValues->count() > 0)
                    <br><small class="text-muted">
                      @foreach($item->productVariant->attributeValues as $attrValue)
                        {{ $attrValue->value }}@if(!$loop->last), @endif
                      @endforeach
                    </small>
                  @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price) }}₫</td>
                <td>{{ number_format($item->price * $item->quantity) }}₫</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
