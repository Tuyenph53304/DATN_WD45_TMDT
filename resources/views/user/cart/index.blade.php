@extends('user.layout')

@section('title', 'Giỏ hàng - ' . config('constants.site.name'))

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <h2 class="fw-bold mb-4"><i class="bi bi-cart"></i> Giỏ hàng của tôi</h2>

      @if($cartItems->count() > 0)
      <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8 mb-4">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              @foreach($cartItems as $item)
              <div class="cart-item border-bottom pb-4 mb-4" data-item-id="{{ $item->id }}">
                <div class="row align-items-center">
                  <!-- Product Image -->
                  <div class="col-md-2 mb-3 mb-md-0">
                    <img src="{{ $item->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop' }}"
                         class="img-fluid rounded"
                         alt="{{ $item->productVariant->product->name }}"
                         style="max-height: 120px; object-fit: cover;">
                  </div>

                  <!-- Product Info -->
                  <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">
                      <a href="{{ route('products.show', $item->productVariant->product->slug) }}" class="text-decoration-none text-dark">
                        {{ $item->productVariant->product->name }}
                      </a>
                    </h6>
                    <div class="small text-muted">
                      @foreach($item->productVariant->attributeValues as $attrValue)
                        <div><strong>{{ $attrValue->attribute->name }}:</strong> {{ $attrValue->value }}</div>
                      @endforeach
                    </div>
                  </div>

                  <!-- Price -->
                  <div class="col-md-2 mb-3 mb-md-0 text-center">
                    <div class="fw-bold text-primary">{{ number_format($item->productVariant->price) }}₫</div>
                    <small class="text-muted">đơn vị</small>
                  </div>

                  <!-- Quantity -->
                  <div class="col-md-2 mb-3 mb-md-0">
                    <div class="input-group">
                      <button class="btn btn-outline-secondary btn-sm quantity-decrease" type="button" data-item-id="{{ $item->id }}">-</button>
                      <input type="number" class="form-control form-control-sm text-center quantity-input"
                             value="{{ $item->quantity }}"
                             min="1"
                             max="{{ $item->productVariant->stock }}"
                             data-item-id="{{ $item->id }}"
                             readonly>
                      <button class="btn btn-outline-secondary btn-sm quantity-increase" type="button" data-item-id="{{ $item->id }}">+</button>
                    </div>
                    <small class="text-muted d-block text-center mt-1">Còn {{ $item->productVariant->stock }} sản phẩm</small>
                  </div>

                  <!-- Total & Actions -->
                  <div class="col-md-2 text-center">
                    <div class="fw-bold text-danger mb-2 item-total">{{ number_format($item->productVariant->price * $item->quantity) }}₫</div>
                    <button class="btn btn-sm btn-outline-danger remove-item-btn" data-item-id="{{ $item->id }}">
                      <i class="bi bi-trash"></i> Xóa
                    </button>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0"><i class="bi bi-receipt"></i> Tóm tắt đơn hàng</h5>
            </div>
            <div class="card-body">
              <!-- Voucher Section -->
              <div class="mb-4">
                @if($voucher)
                <div class="alert alert-success p-2 mb-2">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="fw-bold">{{ $voucher->code }}</small>
                      @if($voucher->type === 'percentage')
                        <div class="small">Giảm {{ $voucher->value }}%</div>
                      @else
                        <div class="small">Giảm {{ number_format($voucher->value) }}₫</div>
                      @endif
                    </div>
                    <form action="{{ route('voucher.remove') }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x"></i>
                      </button>
                    </form>
                  </div>
                </div>
                @else
                <div class="input-group mb-2">
                  <input type="text" class="form-control" id="voucher-code-input" placeholder="Nhập mã giảm giá">
                  <button class="btn btn-outline-primary" type="button" id="apply-voucher-btn">
                    <i class="bi bi-ticket-perforated"></i> Áp dụng
                  </button>
                </div>
                <div id="voucher-message" class="small"></div>
                @endif
              </div>

              <div class="d-flex justify-content-between mb-3">
                <span>Tạm tính:</span>
                <span id="subtotal">{{ number_format($total) }}₫</span>
              </div>
              @if($voucher && $discount > 0)
              <div class="d-flex justify-content-between mb-3">
                <span>Giảm giá:</span>
                <span class="text-success" id="discount-amount">-{{ number_format($discount) }}₫</span>
              </div>
              @endif
              <div class="d-flex justify-content-between mb-3">
                <span>Phí vận chuyển:</span>
                <span class="text-success">Miễn phí</span>
              </div>
              <hr>
              <div class="d-flex justify-content-between mb-4">
                <strong>Tổng cộng:</strong>
                <strong class="text-danger fs-5" id="grand-total">{{ number_format($finalTotal ?? $total) }}₫</strong>
              </div>

              <!-- Checkout Form -->
              <form action="{{ route('payment.checkout') }}" method="POST" id="checkout-form">
                @csrf

                <!-- Shipping Address -->
                @php
                  $shippingAddresses = \App\Models\ShippingAddress::where('user_id', Auth::id())->get();
                @endphp
                @if($shippingAddresses->count() > 0)
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Địa chỉ giao hàng:</h6>
                  @php
                    $hasDefault = $shippingAddresses->where('default', true)->first();
                    $firstAddress = $shippingAddresses->first();
                  @endphp
                  @foreach($shippingAddresses as $index => $address)
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_address_id" id="address-{{ $address->id }}" value="{{ $address->id }}" {{ ($address->default || (!$hasDefault && $index === 0)) ? 'checked' : '' }} required>
                    <label class="form-check-label" for="address-{{ $address->id }}">
                      <div class="fw-semibold">
                        {{ $address->full_name }}
                        @if($address->default)
                          <span class="badge bg-primary ms-2">Mặc định</span>
                        @endif
                      </div>
                      <div class="small text-muted">{{ $address->address }}, {{ $address->city }}</div>
                      <div class="small text-muted">{{ $address->phone }}</div>
                    </label>
                  </div>
                  @endforeach
                  <div id="address-error" class="text-danger small mt-2" style="display: none;">
                    <i class="bi bi-exclamation-circle"></i> Vui lòng chọn địa chỉ giao hàng
                  </div>
                </div>
                @else
                <div class="alert alert-warning mb-4">
                  <i class="bi bi-exclamation-triangle"></i> Bạn chưa có địa chỉ giao hàng.
                  <a href="#" class="alert-link">Thêm địa chỉ mới</a>
                </div>
                @endif

                <!-- Payment Method Selection -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Phương thức thanh toán:</h6>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment-momo" value="momo" checked>
                    <label class="form-check-label" for="payment-momo">
                      <i class="bi bi-phone"></i> Thanh toán qua MoMo
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment-cod" value="cod">
                    <label class="form-check-label" for="payment-cod">
                      <i class="bi bi-cash-coin"></i> Thanh toán khi nhận hàng (COD)
                    </label>
                  </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 btn-lg mb-2" id="checkout-btn" {{ $shippingAddresses->count() == 0 ? 'disabled' : '' }}>
                  <i class="bi bi-credit-card" id="payment-icon"></i> <span id="payment-text">Thanh toán MoMo</span>
                </button>
              </form>
              <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
              </a>
            </div>
          </div>
        </div>
      </div>
      @else
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
          <h4 class="mt-3">Giỏ hàng của bạn đang trống</h4>
          <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
          <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg mt-3">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
          </a>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Payment method change handler
  document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentIcon = document.getElementById('payment-icon');
    const paymentText = document.getElementById('payment-text');
    const checkoutBtn = document.getElementById('checkout-btn');

    paymentMethods.forEach(method => {
      method.addEventListener('change', function() {
        if (this.value === 'cod') {
          paymentIcon.className = 'bi bi-cash-coin';
          paymentText.textContent = 'Đặt hàng (Thanh toán khi nhận)';
          checkoutBtn.classList.remove('btn-danger');
          checkoutBtn.classList.add('btn-success');
        } else {
          paymentIcon.className = 'bi bi-credit-card';
          paymentText.textContent = 'Thanh toán MoMo';
          checkoutBtn.classList.remove('btn-success');
          checkoutBtn.classList.add('btn-danger');
        }
      });
    });
  });

  // Update quantity
  function updateQuantity(itemId, quantity) {
    const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
    const maxStock = parseInt(input.getAttribute('max'));

    if (quantity < 1) quantity = 1;
    if (quantity > maxStock) {
      alert('Số lượng không được vượt quá tồn kho');
      quantity = maxStock;
    }

    fetch(`/api/cart/${itemId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        input.value = quantity;
        updateItemTotal(itemId);
        updateCartTotal();
      } else {
        alert(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
  }

  // Update item total
  function updateItemTotal(itemId) {
    const item = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
    const price = parseFloat(item.querySelector('.text-primary').textContent.replace(/[^\d]/g, ''));
    const quantity = parseInt(item.querySelector('.quantity-input').value);
    const total = price * quantity;
    item.querySelector('.item-total').textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
  }

  // Update cart total
  function updateCartTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-total').forEach(el => {
      subtotal += parseFloat(el.textContent.replace(/[^\d]/g, ''));
    });
    document.getElementById('subtotal').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + '₫';

    // Check if voucher is applied
    const voucherApplied = document.querySelector('.alert-success');
    if (voucherApplied) {
      // Reload page to recalculate with voucher
      location.reload();
    } else {
      document.getElementById('grand-total').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + '₫';
    }
  }

  // Apply voucher
  document.getElementById('apply-voucher-btn')?.addEventListener('click', function() {
    const code = document.getElementById('voucher-code-input').value.trim();
    const messageDiv = document.getElementById('voucher-message');
    const btn = this;

    if (!code) {
      messageDiv.innerHTML = '<span class="text-danger">Vui lòng nhập mã voucher</span>';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang kiểm tra...';
    messageDiv.innerHTML = '';

    fetch('{{ route("api.voucher.validate") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Apply voucher via form submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("voucher.apply") }}';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        const codeInput = document.createElement('input');
        codeInput.type = 'hidden';
        codeInput.name = 'code';
        codeInput.value = code;

        form.appendChild(csrfInput);
        form.appendChild(codeInput);
        document.body.appendChild(form);
        form.submit();
      } else {
        messageDiv.innerHTML = '<span class="text-danger">' + data.message + '</span>';
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-ticket-perforated"></i> Áp dụng';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      messageDiv.innerHTML = '<span class="text-danger">Có lỗi xảy ra. Vui lòng thử lại.</span>';
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-ticket-perforated"></i> Áp dụng';
    });
  });

  // Allow Enter key to apply voucher
  document.getElementById('voucher-code-input')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      document.getElementById('apply-voucher-btn')?.click();
    }
  });

  // Quantity decrease
  document.querySelectorAll('.quantity-decrease').forEach(btn => {
    btn.addEventListener('click', function() {
      const itemId = this.getAttribute('data-item-id');
      const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
      const currentQty = parseInt(input.value);
      updateQuantity(itemId, currentQty - 1);
    });
  });

  // Quantity increase
  document.querySelectorAll('.quantity-increase').forEach(btn => {
    btn.addEventListener('click', function() {
      const itemId = this.getAttribute('data-item-id');
      const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
      const currentQty = parseInt(input.value);
      updateQuantity(itemId, currentQty + 1);
    });
  });

  // Remove item
  document.querySelectorAll('.remove-item-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        return;
      }

      const itemId = this.getAttribute('data-item-id');
      const btn = this;
      btn.disabled = true;

      fetch(`/api/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.querySelector(`.cart-item[data-item-id="${itemId}"]`).remove();
          updateCartTotal();

          // Reload if cart is empty
          if (document.querySelectorAll('.cart-item').length === 0) {
            location.reload();
          }
        } else {
          alert(data.message || 'Có lỗi xảy ra');
          btn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
        btn.disabled = false;
      });
    });
  });

  // Validate shipping address before checkout
  document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
    const selectedAddress = document.querySelector('input[name="shipping_address_id"]:checked');
    const errorDiv = document.getElementById('address-error');

    if (!selectedAddress) {
      e.preventDefault();
      if (errorDiv) {
        errorDiv.style.display = 'block';
      }

      // Scroll to error
      if (errorDiv) {
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }

      // Highlight address section
      const addressSection = document.querySelector('.mb-4:has(input[name="shipping_address_id"])');
      if (addressSection) {
        addressSection.style.border = '2px solid #dc3545';
        addressSection.style.borderRadius = '8px';
        addressSection.style.padding = '10px';
        setTimeout(() => {
          addressSection.style.border = '';
          addressSection.style.padding = '';
        }, 3000);
      }

      return false;
    } else {
      if (errorDiv) {
        errorDiv.style.display = 'none';
      }
    }
  });

  // Hide error when address is selected
  document.querySelectorAll('input[name="shipping_address_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
      const errorDiv = document.getElementById('address-error');
      if (errorDiv) {
        errorDiv.style.display = 'none';
      }

      // Enable checkout button
      const checkoutBtn = document.getElementById('checkout-btn');
      if (checkoutBtn) {
        checkoutBtn.disabled = false;
      }
    });
  });
</script>
@endpush
@endsection

