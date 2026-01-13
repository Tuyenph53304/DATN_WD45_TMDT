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

                @if($order->cancelled_request)
                  <span class="badge bg-warning ms-2">
                    <i class="bi bi-hourglass-split me-1"></i> Đang chờ xác nhận hủy
                  </span>
                @endif

                {{-- Hủy trực tiếp khi đơn hàng đang chờ xác nhận --}}
                @if($order->canCancelByCustomer() && !$order->cancelled_request && $order->status === 'pending_confirmation')
                  <button type="button" class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="bi bi-x-circle me-1"></i> Hủy đơn
                  </button>
                @endif

                {{-- Yêu cầu hủy khi đơn hàng từ đã xác nhận trở đi --}}
                @if($order->canRequestCancel() && !$order->cancelled_request)
                  <button type="button" class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    <i class="bi bi-x-circle me-1"></i> Yêu cầu hủy đơn
                  </button>
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
                @elseif($order->payment_status === 'unpaid')
                  <span class="badge bg-warning">Chưa thanh toán</span>
                @elseif($order->payment_status === 'pending')
                  <span class="badge bg-info">Đang chờ thanh toán</span>
                @elseif($order->payment_status === 'failed')
                  <span class="badge bg-danger">Thanh toán thất bại</span>
                @else
                  <span class="badge bg-secondary">{{ $order->payment_status }}</span>
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
                      @php
                        $product = $item->productVariant->product;
                        $primaryImage = $product->images->sortBy('sort_order')->first();
                        $productImage = $primaryImage 
                          ? asset('storage/' . $primaryImage->image_path)
                          : ($item->productVariant->image 
                              ? (str_starts_with($item->productVariant->image, 'http') 
                                  ? $item->productVariant->image 
                                  : asset('storage/' . $item->productVariant->image))
                              : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&h=100&fit=crop');
                      @endphp
                      <img src="{{ $productImage }}"
                           class="rounded me-3"
                           style="width: 60px; height: 60px; object-fit: cover;"
                           onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&h=100&fit=crop'">
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

      <!-- Reviews Section - Chỉ hiển thị khi đơn hàng đã completed -->
      @if($order->status === 'completed')
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-bottom">
            <h6 class="mb-0"><i class="bi bi-star me-2"></i> Đánh giá sản phẩm</h6>
          </div>
          <div class="card-body">
            @forelse($order->orderItems as $item)
              @php
                $product = $item->productVariant->product;
                $review = $reviews[$product->id] ?? null;
              @endphp
              <div class="review-item border-bottom pb-4 mb-4">
                <div class="row align-items-center mb-3">
                  <div class="col-md-2">
                    @php
                      $primaryImage = $product->images->sortBy('sort_order')->first();
                      $productImage = $primaryImage 
                        ? asset('storage/' . $primaryImage->image_path)
                        : ($item->productVariant->image 
                            ? (str_starts_with($item->productVariant->image, 'http') 
                                ? $item->productVariant->image 
                                : asset('storage/' . $item->productVariant->image))
                            : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop');
                    @endphp
                    <img src="{{ $productImage }}"
                         class="img-fluid rounded" alt="{{ $product->name }}"
                         style="max-height: 80px; object-fit: cover;"
                         onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop'">
                  </div>
                  <div class="col-md-10">
                    <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                    <div class="small text-muted">
                      @foreach($item->productVariant->attributeValues as $attrValue)
                        {{ $attrValue->value }}@if(!$loop->last), @endif
                      @endforeach
                    </div>
                  </div>
                </div>

                @if($review)
                  <div class="review-content bg-light p-3 rounded">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <div>
                        <div class="d-flex align-items-center mb-1">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning" style="font-size: 1rem;"></i>
                          @endfor
                          @if($review->is_positive)
                            <span class="badge bg-success ms-2">
                              <i class="bi bi-hand-thumbs-up"></i> Tích cực
                            </span>
                          @else
                            <span class="badge bg-danger ms-2">
                              <i class="bi bi-hand-thumbs-down"></i> Tiêu cực
                            </span>
                          @endif
                        </div>
                        <div class="small text-muted">
                          <i class="bi bi-clock me-1"></i>{{ $review->created_at->format('d/m/Y H:i') }}
                        </div>
                      </div>
                    </div>
                    @if($review->comment)
                      <p class="mb-0 text-dark">{{ $review->comment }}</p>
                    @endif
                  </div>
                @else
                  <!-- Form đánh giá inline -->
                  <div class="review-form-container bg-light p-3 rounded">
                    <h6 class="mb-3"><i class="bi bi-star me-1"></i> Đánh giá sản phẩm</h6>
                    <form action="{{ route('user.reviews.store', $order->id) }}" method="POST" class="review-form-inline" data-product-id="{{ $product->id }}">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">

                      <div class="mb-3">
                        <label class="form-label fw-semibold">Đánh giá sao <span class="text-danger">*</span></label>
                        <div class="rating-input-inline">
                          @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating_star_{{ $product->id }}" id="rating_{{ $product->id }}_{{ $i }}" value="{{ $i }}" required>
                            <label for="rating_{{ $product->id }}_{{ $i }}" class="star-label-inline">
                              <i class="bi bi-star-fill"></i>
                            </label>
                          @endfor
                          <input type="hidden" name="rating" id="rating_value_{{ $product->id }}" required>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-semibold">Bình luận (tùy chọn)</label>
                        <textarea class="form-control form-control-sm" name="comment" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." maxlength="1000"></textarea>
                        <small class="text-muted">Tối đa 1000 ký tự</small>
                      </div>

                      <div class="mb-3">
                        <label class="form-label fw-semibold">Loại đánh giá</label>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="is_positive" id="is_positive_{{ $product->id }}" value="1" checked>
                          <label class="form-check-label" for="is_positive_{{ $product->id }}">
                            <i class="bi bi-hand-thumbs-up text-success me-1"></i> Tích cực
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="is_positive" id="is_positive_off_{{ $product->id }}" value="0">
                          <label class="form-check-label" for="is_positive_off_{{ $product->id }}">
                            <i class="bi bi-hand-thumbs-down text-danger me-1"></i> Tiêu cực
                          </label>
                        </div>
                      </div>

                      <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Gửi đánh giá
                      </button>
                    </form>
                  </div>
                @endif
              </div>
            @empty
              <p class="text-muted text-center">Không có sản phẩm nào</p>
            @endforelse
          </div>
        </div>
      @endif

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

          @if($order->cancel_reason)
          <!-- Cancel Reason -->
          <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
              <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i> Lý do hủy đơn</h6>
            </div>
            <div class="card-body">
              <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $order->cancel_reason }}
              </div>
            </div>
          </div>
          @endif
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

<!-- Cancel Order Modal -->
@if(($order->canCancelByCustomer() || $order->canRequestCancel()) && !$order->cancelled_request)
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" id="cancelOrderForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="cancelOrderModalLabel">
            <i class="bi bi-exclamation-triangle text-warning me-2"></i> Xác nhận hủy đơn hàng
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-3">Bạn có chắc chắn muốn hủy đơn hàng #<strong>{{ $order->id }}</strong>?</p>
          <p class="text-muted small mb-4">Vui lòng chọn lý do hủy đơn hàng:</p>

          <div class="mb-3">
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason1" value="Thay đổi ý định mua hàng">
              <label class="form-check-label" for="reason1">
                Thay đổi ý định mua hàng
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason2" value="Tìm thấy sản phẩm khác phù hợp hơn">
              <label class="form-check-label" for="reason2">
                Tìm thấy sản phẩm khác phù hợp hơn
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason3" value="Giá cả không phù hợp">
              <label class="form-check-label" for="reason3">
                Giá cả không phù hợp
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason4" value="Không còn nhu cầu sử dụng">
              <label class="form-check-label" for="reason4">
                Không còn nhu cầu sử dụng
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason5" value="Đặt nhầm sản phẩm">
              <label class="form-check-label" for="reason5">
                Đặt nhầm sản phẩm
              </label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="cancel_reason_option" id="reason6" value="other">
              <label class="form-check-label" for="reason6">
                Lý do khác
              </label>
            </div>
          </div>

          <div class="mb-3" id="otherReasonGroup" style="display: none;">
            <label for="other_reason" class="form-label">Vui lòng nhập lý do khác:</label>
            <textarea class="form-control" id="other_reason" rows="3" placeholder="Nhập lý do hủy đơn hàng..." maxlength="1000"></textarea>
            <small class="text-muted">Tối đa 1000 ký tự</small>
          </div>

          <input type="hidden" id="final_cancel_reason" name="cancel_reason" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-x-circle me-1"></i> Xác nhận hủy đơn
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@push('styles')
<style>
.rating-input-inline {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  gap: 5px;
}

.rating-input-inline input[type="radio"] {
  display: none;
}

.rating-input-inline label {
  font-size: 1.5rem;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s, transform 0.1s;
}

.rating-input-inline label:hover {
  color: #ffc107;
  transform: scale(1.1);
}

.rating-input-inline label:hover ~ label {
  color: #ffc107;
}

.rating-input-inline input[type="radio"]:checked ~ label {
  color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle rating for inline review forms
  document.querySelectorAll('.review-form-inline').forEach(form => {
    const productId = form.getAttribute('data-product-id');
    const ratingRadios = form.querySelectorAll(`input[name="rating_star_${productId}"]`);
    const ratingValueInput = form.querySelector(`#rating_value_${productId}`);
    const isPositiveRadio1 = form.querySelector(`#is_positive_${productId}`);
    const isPositiveRadio0 = form.querySelector(`#is_positive_off_${productId}`);

    // Handle rating radio change
    ratingRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        ratingValueInput.value = this.value;

        // Auto set is_positive based on rating
        if (parseInt(this.value) >= 4) {
          isPositiveRadio1.checked = true;
          isPositiveRadio0.checked = false;
        } else {
          isPositiveRadio1.checked = false;
          isPositiveRadio0.checked = true;
        }
      });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
      const selectedRating = form.querySelector(`input[name="rating_star_${productId}"]:checked`);
      if (!selectedRating) {
        e.preventDefault();
        alert('Vui lòng chọn số sao đánh giá');
        return false;
      }
    });
  });

  const cancelModal = document.getElementById('cancelOrderModal');
  if (cancelModal) {
    const cancelForm = document.getElementById('cancelOrderForm');
    const reasonRadios = document.querySelectorAll('input[name="cancel_reason_option"]');
    const otherReasonGroup = document.getElementById('otherReasonGroup');
    const otherReasonTextarea = document.getElementById('other_reason');
    const finalCancelReason = document.getElementById('final_cancel_reason');

    // Show/hide other reason textarea
    reasonRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        if (this.id === 'reason6' && this.value === 'other' && this.checked) {
          otherReasonGroup.style.display = 'block';
        } else {
          otherReasonGroup.style.display = 'none';
          otherReasonTextarea.value = '';
        }
      });
    });

    // Handle form submission
    cancelForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const selectedReason = document.querySelector('input[name="cancel_reason_option"]:checked');
      if (!selectedReason) {
        alert('Vui lòng chọn lý do hủy đơn hàng');
        return false;
      }

      let finalReason = selectedReason.value;

      // If "Lý do khác" is selected, use the textarea value
      if (selectedReason.id === 'reason6' && selectedReason.value === 'other') {
        const otherReason = otherReasonTextarea.value.trim();
        if (!otherReason) {
          alert('Vui lòng nhập lý do hủy đơn hàng');
          otherReasonTextarea.focus();
          return false;
        }
        if (otherReason.length > 1000) {
          alert('Lý do hủy đơn hàng không được vượt quá 1000 ký tự');
          otherReasonTextarea.focus();
          return false;
        }
        finalReason = otherReason;
      }

      // Set final reason value
      finalCancelReason.value = finalReason;

      // Submit form
      this.submit();
    });

    // Reset form when modal is closed
    cancelModal.addEventListener('hidden.bs.modal', function() {
      cancelForm.reset();
      otherReasonGroup.style.display = 'none';
      otherReasonTextarea.required = false;
      otherReasonTextarea.value = '';
    });
  }
});
</script>
@endpush
@endsection
