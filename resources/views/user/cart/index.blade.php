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
                  <!-- Checkbox -->
                  <div class="col-md-1 mb-3 mb-md-0 text-center">
                    <input type="checkbox" class="form-check-input cart-item-checkbox"
                           data-item-id="{{ $item->id }}"
                           data-price="{{ $item->productVariant->price }}"
                           data-quantity="{{ $item->quantity }}"
                           checked>
                  </div>
                  <!-- Product Image -->
                  <div class="col-md-2 mb-3 mb-md-0">
                    @php
                      $product = $item->productVariant->product;
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
                         class="img-fluid rounded"
                         alt="{{ $item->productVariant->product->name }}"
                         style="max-height: 120px; object-fit: cover;"
                         onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=200&h=200&fit=crop'">
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

              <div class="d-flex justify-content-between mb-3">
                <span>Tạm tính:</span>
                <span id="subtotal">{{ number_format($total) }}₫</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span>Đã chọn:</span>
                <span id="selected-total">{{ number_format($total) }}₫</span>
              </div>
              <div class="mb-3 small text-muted" id="selected-items-info" style="display: none;">
                <div class="fw-bold mb-1">Sản phẩm sẽ thanh toán:</div>
                <div id="selected-items-list"></div>
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

              <!-- Checkout Button -->
              <form action="{{ route('checkout') }}" method="GET" id="checkout-form-with-selection">
                <input type="hidden" name="selected_items" id="selected-items-input" value="">
                @php
                  $shippingAddresses = \App\Models\ShippingAddress::where('user_id', Auth::id())->get();
                @endphp
                <button type="submit" class="btn btn-danger w-100 btn-lg mb-2" id="checkout-btn" {{ $shippingAddresses->count() == 0 ? 'disabled' : '' }}>
                  <i class="bi bi-arrow-right"></i>
                  <span id="checkout-text">
                    Thanh toán: <span id="checkout-amount">{{ number_format($total) }}</span>₫
                  </span>
                </button>
                @if($shippingAddresses->count() == 0)
                <div class="alert alert-warning small mt-2 mb-0">
                  <i class="bi bi-exclamation-triangle"></i> Vui lòng thêm địa chỉ giao hàng trước khi thanh toán.
                </div>
                @endif
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
  // Initialize selected total on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateSelectedTotal();
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
        // Update checkbox data-quantity
        const checkbox = document.querySelector(`.cart-item-checkbox[data-item-id="${itemId}"]`);
        if (checkbox) {
          checkbox.setAttribute('data-quantity', quantity);
        }
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

    // Update selected total
    updateSelectedTotal();

    // Check if voucher is applied
    const voucherApplied = document.querySelector('.alert-success');
    if (voucherApplied) {
      // Reload page to recalculate with voucher
      location.reload();
    } else {
      document.getElementById('grand-total').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + '₫';
    }
  }

  // Update selected total based on checkboxes
  function updateSelectedTotal() {
    let selectedTotal = 0;
    const selectedItems = [];

    const checkedBoxes = document.querySelectorAll('.cart-item-checkbox:checked');

    checkedBoxes.forEach(checkbox => {
      const itemId = checkbox.getAttribute('data-item-id');
      const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
      const quantity = parseInt(checkbox.getAttribute('data-quantity')) || 0;

      if (itemId && itemId.trim() !== '') {
      selectedTotal += price * quantity;
        selectedItems.push(itemId.trim());
      }
    });

    const selectedItemsString = selectedItems.join(',');

    // Update UI
    const selectedTotalEl = document.getElementById('selected-total');
    if (selectedTotalEl) {
      selectedTotalEl.textContent = new Intl.NumberFormat('vi-VN').format(selectedTotal) + '₫';
    }

    // Update hidden input
    const selectedItemsInput = document.getElementById('selected-items-input');
    if (selectedItemsInput) {
      selectedItemsInput.value = selectedItemsString;
      // Debug logging
      console.log('updateSelectedTotal - checked boxes:', checkedBoxes.length);
      console.log('updateSelectedTotal - selected items:', selectedItemsString);
      console.log('updateSelectedTotal - input value:', selectedItemsInput.value);
    }

    // Update selected items info display
    const selectedItemsInfo = document.getElementById('selected-items-info');
    const selectedItemsList = document.getElementById('selected-items-list');
    if (selectedItemsInfo && selectedItemsList) {
      if (selectedItems.length > 0) {
        // Get product names for display
        const itemsList = [];
        checkedBoxes.forEach(checkbox => {
          const itemId = checkbox.getAttribute('data-item-id');
          const cartItem = checkbox.closest('.cart-item');
          if (cartItem) {
            const nameElement = cartItem.querySelector('h6 a');
            if (nameElement) {
              const productName = nameElement.textContent.trim();
              const quantity = checkbox.getAttribute('data-quantity');
              itemsList.push(`• ${productName} (x${quantity})`);
            }
          }
        });
        selectedItemsList.innerHTML = itemsList.join('<br>');
        selectedItemsInfo.style.display = 'block';
      } else {
        selectedItemsInfo.style.display = 'none';
      }
    }

    // Update checkout amount
    const checkoutAmount = document.getElementById('checkout-amount');
    if (checkoutAmount) {
      checkoutAmount.textContent = new Intl.NumberFormat('vi-VN').format(selectedTotal);
    }

    // Enable/disable checkout button (only based on selected items)
    // Note: Button may also be disabled due to no shipping addresses (handled by PHP)
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
      // Only disable if no items selected, but don't enable if disabled due to no addresses
      if (selectedItems.length === 0) {
        checkoutBtn.disabled = true;
      } else {
        // Check if there's a warning about no addresses
        const addressWarning = document.querySelector('.alert-warning');
        if (!addressWarning) {
          // Only enable if no address warning (meaning addresses exist)
          checkoutBtn.disabled = false;
    }
      }
    }

    return selectedItemsString;
  }

  // Handle checkbox change
  document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      updateSelectedTotal();
    });

    // Also update on click to ensure it's captured
    checkbox.addEventListener('click', function() {
      setTimeout(() => {
        updateSelectedTotal();
      }, 10);
    });
  });

  // Helper function to get all checked items with details
  function getCheckedItems() {
    const allBoxes = document.querySelectorAll('.cart-item-checkbox');
    const checkedItems = [];

    allBoxes.forEach(checkbox => {
      if (checkbox.checked === true) {
        const itemId = checkbox.getAttribute('data-item-id');
        const price = checkbox.getAttribute('data-price');
        const quantity = checkbox.getAttribute('data-quantity');

        // Find the cart item element to get product name
        const cartItem = checkbox.closest('.cart-item');
        let productName = 'Unknown';
        if (cartItem) {
          const nameElement = cartItem.querySelector('h6 a');
          if (nameElement) {
            productName = nameElement.textContent.trim();
          }
        }

        if (itemId && itemId.trim() !== '') {
          checkedItems.push({
            id: itemId.trim(),
            price: price,
            quantity: quantity,
            name: productName
          });
        }
      }
    });

    return checkedItems;
  }

  // Ensure selected_items is updated before form submit
  const checkoutForm = document.getElementById('checkout-form-with-selection');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
      // Prevent default to ensure we update value first
      e.preventDefault();

      console.log('=== FORM SUBMIT START ===');

      // Get ALL checkboxes first
      const allBoxes = document.querySelectorAll('.cart-item-checkbox');
      console.log('Total checkboxes found:', allBoxes.length);

      // Get checked checkboxes - use multiple methods to be absolutely sure
      const checkedBoxes1 = document.querySelectorAll('.cart-item-checkbox:checked');
      const checkedBoxes2 = Array.from(allBoxes).filter(cb => cb.checked === true);
      const checkedBoxes = checkedBoxes1.length > 0 ? checkedBoxes1 : checkedBoxes2;

      console.log('Checked boxes (method 1):', checkedBoxes1.length);
      console.log('Checked boxes (method 2):', checkedBoxes2.length);
      console.log('Using checked boxes:', checkedBoxes.length);

      // Build selected items array directly from checked boxes
      const selectedItems = [];
      checkedBoxes.forEach((checkbox, index) => {
        const itemId = checkbox.getAttribute('data-item-id');
        const isChecked = checkbox.checked;
        console.log(`Checkbox ${index}: id="${itemId}", checked=${isChecked}, type=${checkbox.type}`);

        if (itemId && itemId.trim() !== '' && isChecked) {
          selectedItems.push(itemId.trim());
        }
      });

      console.log('Selected items array:', selectedItems);
      console.log('Selected items count:', selectedItems.length);

      // Update the hidden input with selected items
      const selectedItemsInput = document.getElementById('selected-items-input');
      if (selectedItemsInput) {
        const itemsString = selectedItems.join(',');
        selectedItemsInput.value = itemsString;
        console.log('✓ Hidden input value set to:', selectedItemsInput.value);
        console.log('✓ Input element exists:', !!selectedItemsInput);
        console.log('✓ Input name attribute:', selectedItemsInput.name);
      } else {
        console.error('✗ ERROR: selected-items-input element not found!');
        alert('Lỗi: Không tìm thấy form input. Vui lòng tải lại trang.');
        return false;
      }

      // Also update total display
      updateSelectedTotal();

      // Log final state
      console.log('=== FINAL STATE BEFORE SUBMIT ===');
      console.log('Form action:', this.action);
      console.log('Form method:', this.method);
      console.log('Selected items to send:', selectedItemsInput.value);
      console.log('Form will submit to:', this.action + '?selected_items=' + encodeURIComponent(selectedItemsInput.value));

      // Submit the form
      this.submit();
    });
  } else {
    console.error('ERROR: checkout-form-with-selection form not found!');
  }


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

  // Remove item - Xóa trực tiếp không cần xác nhận
  document.querySelectorAll('.remove-item-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const itemId = this.getAttribute('data-item-id');
      const btn = this;
      const cartItem = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);

      // Disable button và hiển thị loading
      btn.disabled = true;
      btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang xóa...';

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
          // Xóa item khỏi DOM với animation
          if (cartItem) {
            cartItem.style.transition = 'opacity 0.3s, transform 0.3s';
            cartItem.style.opacity = '0';
            cartItem.style.transform = 'translateX(-20px)';
            setTimeout(() => {
              cartItem.remove();
              updateCartTotal();

              // Reload if cart is empty
              if (document.querySelectorAll('.cart-item').length === 0) {
                location.reload();
              }
            }, 300);
          }

          // Update cart count in header if exists
          const cartBadge = document.querySelector('.cart-count-badge');
          if (cartBadge) {
            cartBadge.textContent = data.cart_count || 0;
          }
        } else {
          alert(data.message || 'Có lỗi xảy ra');
          btn.disabled = false;
          btn.innerHTML = '<i class="bi bi-trash"></i> Xóa';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-trash"></i> Xóa';
      });
    });
  });

</script>
@endpush
@endsection

