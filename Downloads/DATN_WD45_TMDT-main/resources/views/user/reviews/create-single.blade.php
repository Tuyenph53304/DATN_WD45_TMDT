@extends('user.layout')

@section('title', 'Đánh giá sản phẩm - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-star me-2"></i> Đánh giá sản phẩm - Đơn hàng #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
          <!-- Product Info -->
          <div class="card mb-4 border">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-3">
                  <img src="{{ $orderItem->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop' }}"
                       class="img-fluid rounded" alt="{{ $product->name }}"
                       style="max-height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-9">
                  <h6 class="fw-bold mb-2">{{ $product->name }}</h6>
                  <div class="small text-muted mb-2">
                    @foreach($orderItem->productVariant->attributeValues as $attrValue)
                      {{ $attrValue->value }}@if(!$loop->last), @endif
                    @endforeach
                  </div>
                  <div class="small text-muted">Số lượng: {{ $orderItem->quantity }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Review Form -->
          <form action="{{ route('user.reviews.store', $order->id) }}" method="POST" id="reviewForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-4">
              <label class="form-label fw-bold">Đánh giá sao <span class="text-danger">*</span></label>
              <div class="rating-input">
                @for($i = 5; $i >= 1; $i--)
                  <input type="radio" name="rating_star" id="rating_{{ $i }}" value="{{ $i }}" required>
                  <label for="rating_{{ $i }}" class="star-label">
                    <i class="bi bi-star-fill"></i>
                  </label>
                @endfor
                <input type="hidden" name="rating" id="rating_value" required>
              </div>
              <small class="text-muted d-block mt-2">Vui lòng chọn số sao từ 1 đến 5</small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Bình luận (tùy chọn)</label>
              <textarea class="form-control" name="comment" rows="5" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." maxlength="1000"></textarea>
              <small class="text-muted">Tối đa 1000 ký tự</small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Loại đánh giá</label>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="is_positive" id="is_positive" value="1" checked>
                <label class="form-check-label" for="is_positive">
                  <i class="bi bi-hand-thumbs-up text-success me-1"></i> Đánh giá tích cực
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_positive" id="is_positive_off" value="0">
                <label class="form-check-label" for="is_positive_off">
                  <i class="bi bi-hand-thumbs-down text-danger me-1"></i> Đánh giá tiêu cực
                </label>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Gửi đánh giá
              </button>
              <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
              </a>
            </div>
          </form>
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
  gap: 10px;
}

.rating-input input[type="radio"] {
  display: none;
}

.rating-input label {
  font-size: 2.5rem;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s, transform 0.1s;
}

.rating-input label:hover {
  color: #ffc107;
  transform: scale(1.1);
}

.rating-input label:hover ~ label {
  color: #ffc107;
}

.rating-input input[type="radio"]:checked ~ label {
  color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('reviewForm');
  const ratingRadios = form.querySelectorAll('input[name="rating_star"]');
  const ratingValueInput = form.querySelector('#rating_value');
  const isPositiveRadio1 = form.querySelector('#is_positive');
  const isPositiveRadio0 = form.querySelector('#is_positive_off');
  
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
    const selectedRating = form.querySelector('input[name="rating_star"]:checked');
    if (!selectedRating) {
      e.preventDefault();
      alert('Vui lòng chọn số sao đánh giá');
      return false;
    }
  });
});
</script>
@endpush
@endsection

