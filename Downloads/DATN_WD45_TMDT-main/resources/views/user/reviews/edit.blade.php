@extends('user.layout')

@section('title', 'Chỉnh sửa đánh giá - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-star me-2"></i> Chỉnh sửa đánh giá</h5>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <h6 class="fw-bold">{{ $review->product->name }}</h6>
            <p class="text-muted small mb-0">Đơn hàng #{{ $review->order->id }}</p>
          </div>

          <form action="{{ route('user.reviews.update', $review->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
              <label class="form-label fw-bold">Đánh giá sao <span class="text-danger">*</span></label>
              <div class="rating-input">
                @for($i = 5; $i >= 1; $i--)
                  <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }} required>
                  <label for="rating{{ $i }}" class="star-label">
                    <i class="bi bi-star-fill"></i>
                  </label>
                @endfor
              </div>
            </div>

            <div class="mb-4">
              <label for="comment" class="form-label fw-bold">Bình luận (tùy chọn)</label>
              <textarea class="form-control" id="comment" name="comment" rows="5" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." maxlength="1000">{{ $review->comment }}</textarea>
              <small class="text-muted">Tối đa 1000 ký tự</small>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold">Loại đánh giá</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_positive" id="is_positive_1" value="1" {{ $review->is_positive ? 'checked' : '' }}>
                <label class="form-check-label" for="is_positive_1">
                  <i class="bi bi-hand-thumbs-up text-success"></i> Đánh giá tích cực
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="is_positive" id="is_positive_0" value="0" {{ !$review->is_positive ? 'checked' : '' }}>
                <label class="form-check-label" for="is_positive_0">
                  <i class="bi bi-hand-thumbs-down text-danger"></i> Đánh giá tiêu cực
                </label>
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Cập nhật đánh giá
              </button>
              <a href="{{ route('user.orders.show', $review->order_id) }}" class="btn btn-outline-secondary">
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
@endsection

