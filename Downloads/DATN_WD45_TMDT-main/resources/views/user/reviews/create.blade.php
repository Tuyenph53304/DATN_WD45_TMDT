@extends('user.layout')

@section('title', 'Đánh giá đơn hàng - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-star me-2"></i> Đánh giá sản phẩm - Đơn hàng #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
          @if(count($productsToReview) == 0)
            <div class="alert alert-info">
              <i class="bi bi-info-circle me-2"></i> Bạn đã đánh giá tất cả sản phẩm trong đơn hàng này!
            </div>
            <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-primary">
              <i class="bi bi-arrow-left me-1"></i> Quay lại đơn hàng
            </a>
          @else
            <p class="text-muted mb-4">Vui lòng đánh giá các sản phẩm trong đơn hàng của bạn:</p>

            @foreach($productsToReview as $item)
              @php
                $product = $item['product'];
                $orderItem = $item['order_item'];
              @endphp
              <div class="card mb-4 border">
                <div class="card-body">
                  <div class="row align-items-center mb-3">
                    <div class="col-md-2">
                      <img src="{{ $orderItem->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop' }}"
                           class="img-fluid rounded" alt="{{ $product->name }}"
                           style="max-height: 100px; object-fit: cover;">
                    </div>
                    <div class="col-md-10">
                      <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                      <div class="small text-muted">
                        @foreach($orderItem->productVariant->attributeValues as $attrValue)
                          {{ $attrValue->value }}@if(!$loop->last), @endif
                        @endforeach
                      </div>
                      <div class="small text-muted">Số lượng: {{ $orderItem->quantity }}</div>
                    </div>
                  </div>

                  <form action="{{ route('user.reviews.store', $order->id) }}" method="POST" class="review-form" data-product-id="{{ $product->id }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-3">
                      <label class="form-label fw-bold">Đánh giá sao <span class="text-danger">*</span></label>
                      <div class="rating-input">
                        @for($i = 5; $i >= 1; $i--)
                          <input type="radio" name="rating_{{ $product->id }}" id="rating{{ $product->id }}_{{ $i }}" value="{{ $i }}" required>
                          <label for="rating{{ $product->id }}_{{ $i }}" class="star-label">
                            <i class="bi bi-star-fill"></i>
                          </label>
                        @endfor
                        <input type="hidden" name="rating" id="rating_value_{{ $product->id }}" required>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label fw-bold">Bình luận (tùy chọn)</label>
                      <textarea class="form-control" name="comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." maxlength="1000"></textarea>
                      <small class="text-muted">Tối đa 1000 ký tự</small>
                    </div>

                    <div class="mb-3">
                      <label class="form-label fw-bold">Loại đánh giá</label>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_positive" id="is_positive_{{ $product->id }}" value="1" checked>
                        <label class="form-check-label" for="is_positive_{{ $product->id }}">
                          <i class="bi bi-hand-thumbs-up text-success"></i> Đánh giá tích cực
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_positive" id="is_positive_off_{{ $product->id }}" value="0">
                        <label class="form-check-label" for="is_positive_off_{{ $product->id }}">
                          <i class="bi bi-hand-thumbs-down text-danger"></i> Đánh giá tiêu cực
                        </label>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-check-circle me-1"></i> Gửi đánh giá
                    </button>
                  </form>
                </div>
              </div>
            @endforeach

            <div class="text-center mt-4">
              <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại đơn hàng
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
.rating-input {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  gap: 5px;
}

.rating-input input[type="radio"] {
  display: none;
}

.rating-input label {
  font-size: 2rem;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
  color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Handle rating selection
  document.querySelectorAll('.review-form').forEach(form => {
    const productId = form.getAttribute('data-product-id');
    const ratingRadios = form.querySelectorAll(`input[name="rating_${productId}"]`);
    const ratingValueInput = form.querySelector(`#rating_value_${productId}`);
    const isPositiveRadio1 = form.querySelector(`#is_positive_${productId}`);
    const isPositiveRadio0 = form.querySelector(`#is_positive_off_${productId}`);
    
    // Handle rating radio change
    ratingRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        ratingValueInput.value = this.value;
        // Auto set is_positive based on rating
        if (this.value >= 4) {
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
      const selectedRating = form.querySelector(`input[name="rating_${productId}"]:checked`);
      if (!selectedRating) {
        e.preventDefault();
        alert('Vui lòng chọn số sao đánh giá');
        return false;
      }
    });
  });
});
</script>
@endpush
@endsection
