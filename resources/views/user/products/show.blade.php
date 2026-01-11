@extends('user.layout')

@section('title', $product->name . ' - ' . config('constants.site.name'))

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <img src="{{ $defaultVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=600&fit=crop' }}"
                            class="img-fluid w-100" alt="{{ $product->name }}" style="max-height: 500px; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

                        <!-- Price - Hiển thị giá cũ và giá mới -->
                        <div class="mb-4" id="product-price-section">
                            @if($defaultVariant)
                                @if($defaultVariant->hasDiscount())
                                    <!-- Giá cũ (gạch ngang) -->
                                    <div class="mb-2">
                                        <span class="text-muted" style="text-decoration: line-through; font-size: 1.1rem;">
                                            {{ number_format($defaultVariant->old_price) }}₫
                                        </span>
                                    </div>
                                    <!-- Giá mới (đỏ) -->
                                    <div class="mb-2">
                                        <h3 class="text-danger fw-bold" id="current-price" style="font-size: 2rem;">
                                            {{ number_format($defaultVariant->price) }}₫
                                        </h3>
                                    </div>
                                    <!-- Phần trăm giảm -->
                                    <div class="mb-2">
                                        <span class="badge bg-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                            Giảm {{ $defaultVariant->getDiscountPercent() }}%
                                        </span>
                                    </div>
                                @else
                                    <!-- Chỉ có giá mới nếu không giảm -->
                                    <h3 class="text-danger fw-bold" id="current-price" style="font-size: 2rem;">
                                        {{ number_format($defaultVariant->price) }}₫
                                    </h3>
                                @endif
                            @endif
                        </div>

                        @auth
                            @php
                                // Giả định $product là đối tượng Product hiện tại
                                $inWishlist = Auth::user()->hasInWishlist($product->id);
                            @endphp
                            <button class="btn btn-outline-danger toggle-wishlist-btn" data-product-id="{{ $product->id }}">
                                <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }} me-2" style="font-size: 1.2rem;"></i>
                                {{ $inWishlist ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger">
                                <i class="bi bi-heart me-2" style="font-size: 1.2rem;"></i>
                                Thêm vào yêu thích
                            </a>
                        @endauth
                        
                        <!-- Variants Selection -->
                        @if ($product->variants->count() > 1)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Chọn cấu hình:</h6>
                                <div class="row g-2" id="variant-selection">
                                    @foreach ($product->variants as $variant)
                                        <div class="col-12">
                                            <div class="card variant-card {{ $variant->id == $defaultVariant->id ? 'border-primary' : '' }}"
                                                data-variant-id="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                                data-stock="{{ $variant->stock }}" style="cursor: pointer;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ number_format($variant->price) }}₫</strong>
                                                            <div class="small text-muted">
                                                                @foreach ($variant->attributeValues as $attrValue)
                                                                    {{ $attrValue->value }}@if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div>
                                                            @if ($variant->stock > 0)
                                                                <span class="badge bg-success">Còn {{ $variant->stock }}
                                                                    sản phẩm</span>
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
                                    @if ($defaultVariant)
                                        @foreach ($defaultVariant->attributeValues as $attrValue)
                                            <div><strong>{{ $attrValue->attribute->name }}:</strong>
                                                {{ $attrValue->value }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Stock Status và Số lượng -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="stock-status {{ $defaultVariant && $defaultVariant->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                                    <i class="bi bi-{{ $defaultVariant && $defaultVariant->stock > 0 ? 'check' : 'x' }}-circle-fill"></i>
                                {{ $defaultVariant && $defaultVariant->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                            </span>
                                @if($defaultVariant && $defaultVariant->stock > 0)
                                    <span class="text-muted">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Còn lại: <strong id="current-stock">{{ $defaultVariant->stock }}</strong> sản phẩm
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mb-4">
                            @if ($defaultVariant && $defaultVariant->stock > 0)
                                <button class="btn btn-danger btn-lg w-100 mb-2 add-to-cart-btn"
                                    id="add-to-cart-btn"
                                    data-variant-id="{{ $defaultVariant->id }}">
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                                </button>
                                <a href="{{ route('buy.now', ['variant_id' => $defaultVariant->id, 'quantity' => 1]) }}" 
                                   class="btn btn-primary btn-lg w-100 buy-now-btn"
                                   id="buy-now-btn">
                                    <i class="bi bi-bag-check"></i> Mua ngay
                                </a>
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

            {{-- FORM VIẾT ĐÁNH GIÁ --}}
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Đánh giá sản phẩm</h5>
                </div>
                <div class="card-body">

                    @auth
                        <form action="{{ route('user.store', $product->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="rating" class="form-label">Số sao</label>
                                <select name="rating" class="form-control" required>
                                    <option value="5">★★★★★ - Rất tốt</option>
                                    <option value="4">★★★★☆ - Tốt</option>
                                    <option value="3">★★★☆☆ - Tạm được</option>
                                    <option value="2">★★☆☆☆ - Không tốt</option>
                                    <option value="1">★☆☆☆☆ - Tệ</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bình luận</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="Nhập cảm nhận của bạn..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Gửi đánh giá
                            </button>
                        </form>
                    @else
                        <p class="text-danger">Bạn cần <a href="/login">đăng nhập</a> để đánh giá.</p>
                    @endauth
                </div>
            </div>

            {{-- DANH SÁCH REVIEW --}}
            @if ($product->reviews->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Khách hàng đánh giá</h5>
                    </div>

                    <div class="card-body">
                        @foreach ($product->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <strong>{{ $review->user->name }}</strong>

                                {{-- Hiển thị sao --}}
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : '' }}"></i>
                                    @endfor
                                </div>

                                <div class="mt-2">
                                    {{ $review->comment }}
                                </div>

                                <div class="text-muted small">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>

                                {{-- Xóa review (nếu là admin hoặc chính chủ) --}}
                                @auth
                                    @if (auth()->user()->role === 'admin' || auth()->id() == $review->user_id)
                                        <form action="{{ route('user.destroy', $review->id) }}" method="POST"
                                            class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="col-12 mt-5">
                    <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
                    <div class="row g-4">
                        @foreach ($relatedProducts as $related)
                            @php
                                $relatedVariant = $related->variants->first();
                            @endphp
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card product-card h-100">
                                    <div class="position-relative overflow-hidden" style="height: 200px;">
                                        <img src="{{ $relatedVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop' }}"
                                            class="card-img-top w-100 h-100" alt="{{ $related->name }}"
                                            style="object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('products.show', $related->slug) }}"
                                                class="text-decoration-none text-dark">{{ $related->name }}</a>
                                        </h6>
                                        <div class="price-container">
                                            @if ($relatedVariant)
                                                <span
                                                    class="price-new">{{ number_format($relatedVariant->price) }}₫</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $related->slug) }}"
                                            class="btn btn-primary btn-sm w-100 mt-2">
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
                    document.querySelectorAll('.variant-card').forEach(c => c.classList.remove('border-primary',
                        'border-2'));

                    // Add active class
                    this.classList.add('border-primary', 'border-2');

                    // Update selected variant
                    selectedVariantId = this.getAttribute('data-variant-id');
                    selectedVariantPrice = this.getAttribute('data-price');
                    selectedVariantStock = this.getAttribute('data-stock');

                    // Update price
                    document.querySelector('#selected-variant-info h4').textContent = new Intl.NumberFormat(
                        'vi-VN').format(selectedVariantPrice) + '₫';

                    // Update stock status
                    const stockStatus = document.querySelector('.stock-status');
                    if (selectedVariantStock > 0) {
                        stockStatus.className = 'stock-status in-stock';
                        stockStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Còn hàng';
                        document.querySelector('.add-to-cart-btn').disabled = false;
                        document.querySelector('.add-to-cart-btn').setAttribute('data-variant-id',
                            selectedVariantId);
                    } else {
                        stockStatus.className = 'stock-status out-of-stock';
                        stockStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i> Hết hàng';
                        document.querySelector('.add-to-cart-btn').disabled = true;
                    }
                });
            });


            <!-- Related Products -->
            @if ($relatedProducts->count() > 0)
                <div class="col-12 mt-5">
                    <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
                    <div class="row g-4">
                        @foreach ($relatedProducts as $related)
                            @php
                                $relatedVariant = $related->variants->first();
                            @endphp
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card product-card h-100">
                                    <div class="position-relative overflow-hidden" style="height: 200px;">
                                        <img src="{{ $relatedVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop' }}"
                                            class="card-img-top w-100 h-100" alt="{{ $related->name }}"
                                            style="object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('products.show', $related->slug) }}"
                                                class="text-decoration-none text-dark">{{ $related->name }}</a>
                                        </h6>
                                        <div class="price-container">
                                            @if ($relatedVariant)
                                                <span class="price-new">{{ number_format($relatedVariant->price) }}₫</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $related->slug) }}"
                                            class="btn btn-primary btn-sm w-100 mt-2">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reviews Section -->
            <div class="col-12 mt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0"><i class="bi bi-star me-2"></i> Đánh giá sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        @if($totalReviews > 0)
                            <!-- Rating Summary -->
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="display-4 fw-bold text-primary">{{ number_format($averageRating, 1) }}</div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= round($averageRating) ? '-fill' : '' }} text-warning"></i>
                                        @endfor
                                    </div>
                                    <div class="text-muted small">{{ $totalReviews }} đánh giá</div>
                                </div>
                                <div class="col-md-8">
                                    @for($star = 5; $star >= 1; $star--)
                                        @php
                                            $count = $reviews->where('rating', $star)->count();
                                            $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="me-2" style="width: 60px;">{{ $star }} sao</span>
                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-muted small">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <hr>

                            <!-- Reviews List -->
                            <div class="reviews-list">
                                @forelse($reviews as $review)
                                <div class="review-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <strong class="me-2">{{ $review->user->name }}</strong>
                                                <div>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning" style="font-size: 0.9rem;"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $review->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($review->comment)
                                        <div class="mt-2">
                                            <p class="mb-0 text-dark">{{ $review->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <p class="text-muted">Chưa có đánh giá nào</p>
                                </div>
                                @endforelse
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-star" style="font-size: 3rem; color: #ddd;"></i>
                                <p class="text-muted mt-3">Chưa có đánh giá nào cho sản phẩm này</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let selectedVariantId = {{ $defaultVariant->id ?? 'null' }};
            let selectedVariantPrice = {{ $defaultVariant->price ?? 0 }};
            let selectedVariantOldPrice = {{ $defaultVariant->old_price ?? 'null' }};
            let selectedVariantStock = {{ $defaultVariant->stock ?? 0 }};
            let selectedVariantDiscountPercent = {{ $defaultVariant->hasDiscount() ? $defaultVariant->getDiscountPercent() : 0 }};

            // Variant data từ server
            const variantData = {
                @foreach($product->variants as $variant)
                {{ $variant->id }}: {
                    id: {{ $variant->id }},
                    price: {{ $variant->price }},
                    old_price: {{ $variant->old_price ?? 'null' }},
                    stock: {{ $variant->stock }},
                    discount_percent: {{ $variant->hasDiscount() ? $variant->getDiscountPercent() : 0 }},
                    attributes: [
                        @foreach($variant->attributeValues as $attrValue)
                        {
                            name: "{{ $attrValue->attribute->name }}",
                            value: "{{ $attrValue->value }}"
                        }@if(!$loop->last),@endif
                        @endforeach
                    ]
                },
                @endforeach
            };

            // Variant selection
            document.querySelectorAll('.variant-card').forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class
                    document.querySelectorAll('.variant-card').forEach(c => c.classList.remove('border-primary', 'border-2'));

                    // Add active class
                    this.classList.add('border-primary', 'border-2');

                    // Update selected variant
                    const variantId = parseInt(this.getAttribute('data-variant-id'));
                    const variant = variantData[variantId];
                    
                    selectedVariantId = variant.id;
                    selectedVariantPrice = variant.price;
                    selectedVariantOldPrice = variant.old_price;
                    selectedVariantStock = variant.stock;
                    selectedVariantDiscountPercent = variant.discount_percent;

                    // Update price section
                    updatePriceDisplay();

                    // Update stock status
                    updateStockDisplay();

                    // Update variant details
                    updateVariantDetails(variant);

                    // Update buttons
                    updateButtons();
                });
            });

            // Function to update price display
            function updatePriceDisplay() {
                const priceSection = document.querySelector('#product-price-section');
                let html = '';

                if (selectedVariantOldPrice && selectedVariantOldPrice > selectedVariantPrice) {
                    // Có giảm giá
                    html = `
                        <div class="mb-2">
                            <span class="text-muted" style="text-decoration: line-through; font-size: 1.1rem;">
                                ${new Intl.NumberFormat('vi-VN').format(selectedVariantOldPrice)}₫
                            </span>
                        </div>
                        <div class="mb-2">
                            <h3 class="text-danger fw-bold" id="current-price" style="font-size: 2rem;">
                                ${new Intl.NumberFormat('vi-VN').format(selectedVariantPrice)}₫
                            </h3>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                Giảm ${selectedVariantDiscountPercent}%
                            </span>
                        </div>
                    `;
                } else {
                    // Không giảm giá
                    html = `
                        <h3 class="text-danger fw-bold" id="current-price" style="font-size: 2rem;">
                            ${new Intl.NumberFormat('vi-VN').format(selectedVariantPrice)}₫
                        </h3>
                    `;
                }

                priceSection.innerHTML = html;
            }

            // Function to update stock display
            function updateStockDisplay() {
                    const stockStatus = document.querySelector('.stock-status');
                const stockCount = document.querySelector('#current-stock');
                
                    if (selectedVariantStock > 0) {
                        stockStatus.className = 'stock-status in-stock';
                        stockStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Còn hàng';
                    if (stockCount) {
                        stockCount.textContent = selectedVariantStock;
                    }
                    } else {
                        stockStatus.className = 'stock-status out-of-stock';
                        stockStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i> Hết hàng';
                    if (stockCount) {
                        stockCount.textContent = '0';
                    }
                }
            }

            // Function to update variant details
            function updateVariantDetails(variant) {
                const variantDetails = document.querySelector('#variant-details');
                if (variantDetails && variant.attributes) {
                    let html = '';
                    variant.attributes.forEach(attr => {
                        html += `<div><strong>${attr.name}:</strong> ${attr.value}</div>`;
                    });
                    variantDetails.innerHTML = html;
                }
            }

            // Function to update buttons
            function updateButtons() {
                const addToCartBtn = document.querySelector('#add-to-cart-btn');
                const buyNowBtn = document.querySelector('#buy-now-btn');

                if (selectedVariantStock > 0) {
                    if (addToCartBtn) {
                        addToCartBtn.disabled = false;
                        addToCartBtn.setAttribute('data-variant-id', selectedVariantId);
                    }
                    if (buyNowBtn) {
                        buyNowBtn.href = '{{ route("buy.now") }}?variant_id=' + selectedVariantId + '&quantity=1';
                        buyNowBtn.style.display = 'block';
                    }
                } else {
                    if (addToCartBtn) {
                        addToCartBtn.disabled = true;
                    }
                    if (buyNowBtn) {
                        buyNowBtn.style.display = 'none';
                    }
                }
            }

            // Add to cart
            document.querySelector('#add-to-cart-btn')?.addEventListener('click', function() {
                // Kiểm tra số lượng
                if (selectedVariantStock <= 0) {
                    alert('Sản phẩm đã hết hàng!');
                    return;
                }

                const btn = this;
                const originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang thêm...';

                fetch('{{ route('api.cart.add') }}', {
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

                            // Cập nhật stock ngay lập tức
                            if (data.updated_stock !== undefined) {
                                selectedVariantStock = data.updated_stock;
                                variantData[selectedVariantId].stock = data.updated_stock;
                                
                                // Cập nhật hiển thị stock
                                updateStockDisplay();
                                
                                // Cập nhật stock trong variant card
                                const variantCard = document.querySelector(`[data-variant-id="${selectedVariantId}"]`);
                                if (variantCard) {
                                    const stockBadge = variantCard.querySelector('.badge');
                                    if (stockBadge) {
                                        if (data.updated_stock > 0) {
                                            stockBadge.className = 'badge bg-success';
                                            stockBadge.textContent = `Còn ${data.updated_stock} sản phẩm`;
                                        } else {
                                            stockBadge.className = 'badge bg-danger';
                                            stockBadge.textContent = 'Hết hàng';
                                        }
                                    }
                                }
                            }

                            // Update cart count in header if exists
                            const cartBadge = document.querySelector('.cart-count-badge');
                            if (cartBadge) {
                                cartBadge.textContent = data.cart_count || 0;
                            }

                            setTimeout(() => {
                                btn.innerHTML = originalText;
                                btn.classList.remove('btn-success');
                                btn.classList.add('btn-danger');
                                btn.disabled = selectedVariantStock <= 0;
                            }, 2000);
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                            btn.innerHTML = originalText;
                            btn.disabled = selectedVariantStock <= 0;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                        btn.innerHTML = originalText;
                        btn.disabled = selectedVariantStock <= 0;
                    });
            });
        </script>
    @endpush
@endsection
