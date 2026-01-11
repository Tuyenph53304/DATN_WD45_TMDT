@extends('user.layout')

@section('title', 'Xác nhận đặt hàng - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <h2 class="fw-bold mb-4"><i class="bi bi-check-circle"></i> Xác nhận đặt hàng</h2>

      <form action="{{ route('payment.checkout') }}" method="POST" id="checkout-form">
        @csrf

        <div class="row">
          <!-- Left Column: Order Details -->
          <div class="col-lg-8 mb-4">
            <!-- Shipping Address Section -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Địa chỉ giao hàng</h5>
              </div>
              <div class="card-body">
                @if($shippingAddresses->count() > 0)
                  @php
                    $hasDefault = $shippingAddresses->where('default', true)->first();
                    $firstAddress = $shippingAddresses->first();
                  @endphp
                  @foreach($shippingAddresses as $index => $address)
                  <div class="form-check mb-3 p-3 border rounded">
                    <input class="form-check-input"
                           type="radio"
                           name="shipping_address_id"
                           id="address-{{ $address->id }}"
                           value="{{ $address->id }}"
                           data-address-name="{{ $address->full_name }}"
                           data-address-phone="{{ $address->phone }}"
                           data-address-address="{{ $address->address }}"
                           data-address-city="{{ $address->city }}"
                           {{ ($address->default || (!$hasDefault && $index === 0)) ? 'checked' : '' }}
                           required>
                    <label class="form-check-label w-100" for="address-{{ $address->id }}">
                      <div class="d-flex justify-content-between align-items-start">
                        <div>
                          <div class="fw-semibold mb-1">
                            {{ $address->full_name }}
                            @if($address->default)
                              <span class="badge bg-primary ms-2">Mặc định</span>
                            @endif
                          </div>
                          <div class="text-muted small mb-1">
                            <i class="bi bi-telephone me-1"></i> {{ $address->phone }}
                          </div>
                          <div class="text-muted small">
                            <i class="bi bi-geo-alt me-1"></i> {{ $address->address }}, {{ $address->city }}
                          </div>
                        </div>
                        <a href="{{ route('user.profile.edit') }}" class="btn btn-sm btn-outline-primary">
                          <i class="bi bi-pencil"></i> Sửa
                        </a>
                      </div>
                    </label>
                  </div>
                  @endforeach
                  <div class="mt-3">
                    <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-plus-circle me-1"></i> Thêm địa chỉ mới
                    </a>
                  </div>
                @else
                  <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Bạn chưa có địa chỉ giao hàng.
                    <a href="{{ route('user.profile.edit') }}" class="alert-link">Thêm địa chỉ mới</a>
                  </div>
                @endif
                <div id="address-error" class="text-danger small mt-2" style="display: none;">
                  <i class="bi bi-exclamation-circle"></i> Vui lòng chọn địa chỉ giao hàng
                </div>
              </div>
            </div>

            <!-- Receiver Information Section -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i> Thông tin người nhận hàng</h5>
              </div>
              <div class="card-body">
                @php
                  $selectedAddress = $shippingAddresses->where('default', true)->first() ?? $shippingAddresses->first();
                @endphp
                @if($selectedAddress)
                  <!-- Lựa chọn: Mặc định hoặc Chỉnh sửa -->
                  <div class="mb-4">
                    <div class="form-check mb-3">
                      <input class="form-check-input" type="radio" name="address_option" id="address-default" value="default" checked>
                      <label class="form-check-label" for="address-default">
                        <strong>Sử dụng địa chỉ mặc định</strong>
                        <div class="text-muted small mt-1">
                          {{ $selectedAddress->full_name }} - {{ $selectedAddress->phone }}<br>
                          {{ $selectedAddress->address }}, {{ $selectedAddress->city }}
                        </div>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="address_option" id="address-custom" value="custom">
                      <label class="form-check-label" for="address-custom">
                        <strong>Chỉnh sửa theo ý muốn</strong>
                      </label>
                    </div>
                  </div>

                  <!-- Form chỉnh sửa (ẩn mặc định) -->
                  <div id="custom-address-form" style="display: none;">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="receiver_name" id="receiver_name" value="{{ $selectedAddress->full_name }}">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="receiver_phone" id="receiver_phone" value="{{ $selectedAddress->phone }}">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="receiver_address" id="receiver_address" value="{{ $selectedAddress->address }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Thành phố/Tỉnh <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="receiver_city" id="receiver_city" value="{{ $selectedAddress->city }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Ghi chú (tùy chọn)</label>
                      <textarea class="form-control" name="receiver_note" id="receiver_note" rows="3" placeholder="Ghi chú cho người giao hàng..."></textarea>
                    </div>
                  </div>

                  <!-- Hidden fields cho địa chỉ mặc định -->
                  <input type="hidden" name="receiver_name_default" id="receiver_name_default" value="{{ $selectedAddress->full_name }}">
                  <input type="hidden" name="receiver_phone_default" id="receiver_phone_default" value="{{ $selectedAddress->phone }}">
                  <input type="hidden" name="receiver_address_default" id="receiver_address_default" value="{{ $selectedAddress->address }}">
                  <input type="hidden" name="receiver_city_default" id="receiver_city_default" value="{{ $selectedAddress->city }}">
                @else
                  <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Vui lòng thêm địa chỉ giao hàng trước.
                    <a href="{{ route('user.profile.edit') }}" class="alert-link">Thêm địa chỉ mới</a>
                  </div>
                @endif
              </div>
            </div>

            <!-- Order Items -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Sản phẩm đặt hàng</h5>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Giá</th>
                        <th class="text-end">Thành tiền</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($cartItems as $item)
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <img src="{{ $item->productVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=100&h=100&fit=crop' }}"
                                 class="rounded me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                              <div class="fw-semibold">{{ $item->productVariant->product->name }}</div>
                              <div class="small text-muted">
                                @foreach($item->productVariant->attributeValues as $attrValue)
                                  {{ $attrValue->value }}@if(!$loop->last), @endif
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->productVariant->price) }}₫</td>
                        <td class="text-end fw-bold">{{ number_format($item->productVariant->price * $item->quantity) }}₫</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Order Summary & Payment -->
          <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px;">
              <div class="card-header bg-white border-bottom">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Tóm tắt đơn hàng</h5>
              </div>
              <div class="card-body">
                <!-- Voucher Section -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Mã giảm giá:</h6>
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

                <div class="d-flex justify-content-between mb-2">
                  <span>Tạm tính:</span>
                  <span id="checkout-subtotal">{{ number_format($subtotal) }}₫</span>
                </div>
                @if($voucher)
                <div class="d-flex justify-content-between mb-2">
                  <span>Giảm giá ({{ $voucher->code }}):</span>
                  <span class="text-danger" id="checkout-discount">-{{ number_format($discount) }}₫</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-4">
                  <strong>Tổng cộng:</strong>
                  <strong class="text-danger fs-5" id="checkout-total">{{ number_format($finalAmount) }}₫</strong>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Phương thức thanh toán:</h6>

                  <!-- MoMo Payment Options -->
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment-momo" value="momo" checked>
                    <label class="form-check-label" for="payment-momo">
                      <i class="bi bi-phone"></i> Thanh toán qua MoMo
                    </label>
                  </div>

                  <!-- MoMo Payment Type (hiển thị khi chọn MoMo) -->
                  <div id="momo-payment-types" class="ms-4 mb-3" style="display: block;">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="momo_payment_type" id="momo-wallet" value="wallet" checked>
                      <label class="form-check-label" for="momo-wallet">
                        <i class="bi bi-wallet2"></i> Ví MoMo
                      </label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="momo_payment_type" id="momo-card" value="card">
                      <label class="form-check-label" for="momo-card">
                        <i class="bi bi-credit-card"></i> Thẻ tín dụng/Ghi nợ
                      </label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="radio" name="momo_payment_type" id="momo-atm" value="atm">
                      <label class="form-check-label" for="momo-atm">
                        <i class="bi bi-bank"></i> Thẻ ATM nội địa
                      </label>
                    </div>

                    <!-- Thẻ test MoMo Demo -->
                    <div class="alert alert-info small mt-2 mb-0" id="test-cards-info" style="display: none;">
                      <strong><i class="bi bi-info-circle"></i> Thẻ test MoMo Demo:</strong><br>
                      <small>
                        <strong>Thẻ thành công:</strong> 9704 0000 0000 0018<br>
                        <strong>Thẻ khóa:</strong> 9704 0000 0000 0026<br>
                        <strong>Thẻ hết hạn:</strong> 9704 0000 0000 0034<br>
                        <strong>Thẻ giới hạn:</strong> 9704 0000 0000 0042<br>
                        <strong>CVV:</strong> 111 | <strong>OTP:</strong> OTP| <strong>EXDATE:</strong> 03/07
                      </small>
                    </div>
                  </div>

                  <!-- COD Option -->
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment-cod" value="cod">
                    <label class="form-check-label" for="payment-cod">
                      <i class="bi bi-cash-coin"></i> Thanh toán khi nhận hàng (COD)
                    </label>
                  </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 btn-lg mb-2" id="checkout-btn">
                  <i class="bi bi-credit-card" id="payment-icon"></i> <span id="payment-text">Thanh toán MoMo</span>
                </button>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                  <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                </a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle custom address form
    const addressOptions = document.querySelectorAll('input[name="address_option"]');
    const customForm = document.getElementById('custom-address-form');
    const customInputs = customForm.querySelectorAll('input[type="text"], textarea');

    addressOptions.forEach(option => {
      option.addEventListener('change', function() {
        if (this.value === 'custom') {
          customForm.style.display = 'block';
          customInputs.forEach(input => {
            input.required = true;
          });
        } else {
          customForm.style.display = 'none';
          customInputs.forEach(input => {
            input.required = false;
          });
        }
      });
    });

    // Update receiver info when address changes
    const addressRadios = document.querySelectorAll('input[name="shipping_address_id"]');
    addressRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        updateReceiverInfo(this);
      });
    });

    // Initialize receiver info on page load
    const initialAddress = document.querySelector('input[name="shipping_address_id"]:checked');
    if (initialAddress) {
      updateReceiverInfo(initialAddress);
    }

    // Payment method change handler
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentIcon = document.getElementById('payment-icon');
    const paymentText = document.getElementById('payment-text');
    const momoPaymentTypes = document.getElementById('momo-payment-types');
    const testCardsInfo = document.getElementById('test-cards-info');
    const momoPaymentTypeInputs = document.querySelectorAll('input[name="momo_payment_type"]');

    paymentMethods.forEach(method => {
      method.addEventListener('change', function() {
        if (this.value === 'cod') {
          paymentIcon.className = 'bi bi-cash-coin';
          paymentText.textContent = 'Đặt hàng (COD)';
          if (momoPaymentTypes) momoPaymentTypes.style.display = 'none';
          if (testCardsInfo) testCardsInfo.style.display = 'none';
        } else {
          paymentIcon.className = 'bi bi-credit-card';
          paymentText.textContent = 'Thanh toán MoMo';
          if (momoPaymentTypes) momoPaymentTypes.style.display = 'block';
          updatePaymentText();
        }
      });
    });

    // MoMo payment type change handler
    if (momoPaymentTypeInputs.length > 0) {
      momoPaymentTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
          updatePaymentText();
          // Hiển thị thông tin thẻ test khi chọn thẻ
          if (testCardsInfo) {
            if (this.value === 'card' || this.value === 'atm') {
              testCardsInfo.style.display = 'block';
            } else {
              testCardsInfo.style.display = 'none';
            }
          }
        });
      });
    }

    function updatePaymentText() {
      const selectedType = document.querySelector('input[name="momo_payment_type"]:checked');
      if (selectedType && paymentText) {
        const typeLabels = {
          'wallet': 'Thanh toán MoMo (Ví)',
          'card': 'Thanh toán MoMo (Thẻ)',
          'atm': 'Thanh toán MoMo (ATM)'
        };
        paymentText.textContent = typeLabels[selectedType.value] || 'Thanh toán MoMo';
      }
    }

    // Form validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
      const selectedAddress = document.querySelector('input[name="shipping_address_id"]:checked');
      if (!selectedAddress) {
        e.preventDefault();
        document.getElementById('address-error').style.display = 'block';
        return false;
      }

      // Nếu chọn chỉnh sửa, validate các trường custom
      const addressOption = document.querySelector('input[name="address_option"]:checked');
      if (addressOption && addressOption.value === 'custom') {
        const name = document.getElementById('receiver_name').value.trim();
        const phone = document.getElementById('receiver_phone').value.trim();
        const address = document.getElementById('receiver_address').value.trim();
        const city = document.getElementById('receiver_city').value.trim();

        if (!name || !phone || !address || !city) {
          e.preventDefault();
          alert('Vui lòng điền đầy đủ thông tin người nhận hàng');
          return false;
        }
      }
    });

    function updateReceiverInfo(radioElement) {
      // Get data from data attributes
      const name = radioElement.getAttribute('data-address-name');
      const phone = radioElement.getAttribute('data-address-phone');
      const address = radioElement.getAttribute('data-address-address');
      const city = radioElement.getAttribute('data-address-city');

      // Update form fields
      const nameField = document.getElementById('receiver_name');
      const phoneField = document.getElementById('receiver_phone');
      const addressField = document.getElementById('receiver_address');
      const cityField = document.getElementById('receiver_city');

      if (nameField && name) nameField.value = name;
      if (phoneField && phone) phoneField.value = phone;
      if (addressField && address) addressField.value = address;
      if (cityField && city) cityField.value = city;
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
  });
</script>
@endpush
@endsection

