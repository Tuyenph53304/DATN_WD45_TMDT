@extends('user.layout')

@section('title', $product->name . ' - ' . config('constants.site.name'))

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <!-- Product Images -->
    <div class="col-lg-6 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <img src="{{ $defaultVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=600&fit=crop' }}" class="img-fluid w-100" alt="{{ $product->name }}" style="max-height: 500px; object-fit: contain;">
        </div>
      </div>
    </div>

    <!-- Product Info -->
    <div class="col-lg-6 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
          <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

          <!-- Price -->
          <div class="mb-4">
            @if($product->variants->count() > 1)
              <h4 class="text-primary fw-bold">
                {{ number_format($product->variants->min('price')) }}₫ - {{ number_format($product->variants->max('price')) }}₫
              </h4>
            @elseif($defaultVariant)
              <h4 class="text-primary fw-bold">{{ number_format($defaultVariant->price) }}₫</h4>
            @endif
          </div>

          <!-- Variants Selection -->
          @if($product->variants->count() > 1)
          <div class="mb-4">
            <h6 class="fw-bold mb-3">Chọn cấu hình:</h6>
            <div class="row g-2" id="variant-selection">
              @foreach($product->variants as $variant)
              <div class="col-12">
                <div class="card variant-card {{ $variant->id == $defaultVariant->id ? 'border-primary' : '' }}"
                     data-variant-id="{{ $variant->id }}"
                     data-price="{{ $variant->price }}"
                     data-stock="{{ $variant->stock }}"
                     style="cursor: pointer;">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <strong>{{ number_format($variant->price) }}₫</strong>
                        <div class="small text-muted">
                          @foreach($variant->attributeValues as $attrValue)
                            {{ $attrValue->value }}@if(!$loop->last), @endif
                          @endforeach
                        </div>
                      </div>
                      <div>
                        @if($variant->stock > 0)
                          <span class="badge bg-success">Còn {{ $variant->stock }} sản phẩm</span>
                        @else
                          <span class="badge bg-danger">Hết hàng</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Selected Variant Info -->
          <div class="mb-4" id="selected-variant-info">
            <div class="alert alert-info">
              <strong>Thông tin cấu hình:</strong>
              <div id="variant-details">
                @if($defaultVariant)
                  @foreach($defaultVariant->attributeValues as $attrValue)
                    <div><strong>{{ $attrValue->attribute->name }}:</strong> {{ $attrValue->value }}</div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>

          <!-- Stock Status -->
          <div class="mb-4">
            <span class="stock-status {{ $defaultVariant && $defaultVariant->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
              <i class="bi bi-{{ $defaultVariant && $defaultVariant->stock > 0 ? 'check' : 'x' }}-circle-fill"></i>
              {{ $defaultVariant && $defaultVariant->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
            </span>
          </div>

          <!-- Actions -->
          <div class="mb-4">
            @if($defaultVariant && $defaultVariant->stock > 0)
            <button class="btn btn-danger btn-lg w-100 mb-2 add-to-cart-btn" data-variant-id="{{ $defaultVariant->id }}">
              <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
            </button>
            @else
            <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>
              <i class="bi bi-x-circle"></i> Hết hàng
            </button>
            @endif
          </div>

          <!-- Features -->
          <div class="product-features">
            <div class="feature-badge mb-2"><i class="bi bi-check-circle"></i> Bảo hành 24 tháng</div>
            <div class="feature-badge mb-2"><i class="bi bi-truck"></i> Miễn phí vận chuyển</div>
            <div class="feature-badge"><i class="bi bi-shield-check"></i> Hỗ trợ 24/7</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Description -->
    <div class="col-12 mt-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="mb-0"><i class="bi bi-info-circle"></i> Mô tả sản phẩm</h5>
        </div>
        <div class="card-body">
          <p>{{ $product->description ?? 'Chưa có mô tả cho sản phẩm này.' }}</p>
        </div>
      </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="col-12 mt-5">
      <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
      <div class="row g-4">
        @foreach($relatedProducts as $related)
        @php
          $relatedVariant = $related->variants->first();
        @endphp
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="card product-card h-100">
            <div class="position-relative overflow-hidden" style="height: 200px;">
              <img src="{{ $relatedVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop' }}" class="card-img-top w-100 h-100" alt="{{ $related->name }}" style="object-fit: cover;">
            </div>
            <div class="card-body">
              <h6 class="card-title">
                <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark">{{ $related->name }}</a>
              </h6>
              <div class="price-container">
                @if($relatedVariant)
                  <span class="price-new">{{ number_format($relatedVariant->price) }}₫</span>
                @endif
              </div>
              <a href="{{ route('products.show', $related->slug) }}" class="btn btn-primary btn-sm w-100 mt-2">
                <i class="bi bi-eye"></i> Xem chi tiết
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  let selectedVariantId = {{ $defaultVariant->id ?? 'null' }};
  let selectedVariantPrice = {{ $defaultVariant->price ?? 0 }};
  let selectedVariantStock = {{ $defaultVariant->stock ?? 0 }};

  // Variant selection
  document.querySelectorAll('.variant-card').forEach(card => {
    card.addEventListener('click', function() {
      // Remove active class
      document.querySelectorAll('.variant-card').forEach(c => c.classList.remove('border-primary', 'border-2'));

      // Add active class
      this.classList.add('border-primary', 'border-2');

      // Update selected variant
      selectedVariantId = this.getAttribute('data-variant-id');
      selectedVariantPrice = this.getAttribute('data-price');
      selectedVariantStock = this.getAttribute('data-stock');

      // Update price
      document.querySelector('#selected-variant-info h4').textContent = new Intl.NumberFormat('vi-VN').format(selectedVariantPrice) + '₫';

      // Update stock status
      const stockStatus = document.querySelector('.stock-status');
      if (selectedVariantStock > 0) {
        stockStatus.className = 'stock-status in-stock';
        stockStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Còn hàng';
        document.querySelector('.add-to-cart-btn').disabled = false;
        document.querySelector('.add-to-cart-btn').setAttribute('data-variant-id', selectedVariantId);
      } else {
        stockStatus.className = 'stock-status out-of-stock';
        stockStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i> Hết hàng';
        document.querySelector('.add-to-cart-btn').disabled = true;
      }
    });
  });

  // Add to cart
  document.querySelector('.add-to-cart-btn')?.addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang thêm...';

    fetch('{{ route("api.cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        product_variant_id: selectedVariantId,
        quantity: 1
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        btn.innerHTML = '<i class="bi bi-check-circle"></i> Đã thêm vào giỏ';
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-success');

        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.classList.remove('btn-success');
          btn.classList.add('btn-danger');
          btn.disabled = false;
        }, 2000);
      } else {
        alert(data.message || 'Có lỗi xảy ra');
        btn.innerHTML = originalText;
        btn.disabled = false;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Có lỗi xảy ra. Vui lòng thử lại.');
      btn.innerHTML = originalText;
      btn.disabled = false;
    });
  });
</script>
@endpush
@endsection

