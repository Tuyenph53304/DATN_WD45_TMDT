@extends('user.layout')

@section('title', $product->name . ' - ' . config('constants.site.name'))

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        @php
                            $sortedImages = $product->images->sortBy('sort_order');
                            $primaryImage = $sortedImages->first();
                            
                            // Xác định ảnh chính để hiển thị
                            if ($primaryImage) {
                                $productImage = asset('storage/' . $primaryImage->image_path);
                            } elseif ($defaultVariant && $defaultVariant->image) {
                                $productImage = str_starts_with($defaultVariant->image, 'http') 
                                    ? $defaultVariant->image 
                                    : asset('storage/' . $defaultVariant->image);
                            } else {
                                $productImage = 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=600&fit=crop';
                            }
                        @endphp
                        
                        <!-- Main Product Image -->
                        <div class="product-main-image mb-3" style="text-align: center; background: #f8f9fa; border-radius: 10px; padding: 20px;">
                            <img id="main-product-image" src="{{ $productImage }}"
                                class="img-fluid" alt="{{ $product->name }}" 
                                style="max-height: 500px; max-width: 100%; object-fit: contain;"
                                onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=600&fit=crop'">
                        </div>
                        
                        <!-- Image Gallery Thumbnails -->
                        @if($product->images->count() > 0)
                        <div class="product-image-gallery mt-3">
                            <h6 class="mb-2"><strong>Xem thêm ảnh:</strong></h6>
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($sortedImages as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }} - Ảnh {{ $loop->index + 1 }}"
                                     class="img-thumbnail product-thumbnail" 
                                     style="width: 80px; height: 80px; object-fit: cover; cursor: pointer; border: 2px solid #dee2e6; transition: all 0.3s ease;"
                                     onclick="document.getElementById('main-product-image').src = this.src"
                                     onmouseover="this.style.borderColor='#667eea'"
                                     onmouseout="this.style.borderColor='#dee2e6'"
                                     onerror="this.style.display='none'">
                                @endforeach
                            </div>
                        </div>
                        @elseif($defaultVariant && $defaultVariant->image)
                        <!-- Fallback to variant image if no product images -->
                        <div class="text-muted small mt-2">
                            <i class="bi bi-info-circle"></i> Chỉ có ảnh biến thể
                        </div>
                        @endif
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
                                    @php
                                        // Mapping icon cho các attribute
                                        $attributeIcons = [
                                            'CPU' => 'bi-cpu',
                                            'Processor' => 'bi-cpu',
                                            'RAM' => 'bi-memory',
                                            'Memory' => 'bi-memory',
                                            'Storage' => 'bi-hdd',
                                            'SSD' => 'bi-hdd',
                                            'HDD' => 'bi-hdd',
                                            'GPU' => 'bi-gpu-card',
                                            'Graphics' => 'bi-gpu-card',
                                            'VGA' => 'bi-gpu-card',
                                            'Display' => 'bi-display',
                                            'Screen' => 'bi-display',
                                            'Màn hình' => 'bi-display',
                                            'Battery' => 'bi-battery-full',
                                            'Pin' => 'bi-battery-full',
                                            'OS' => 'bi-windows',
                                            'Operating System' => 'bi-windows',
                                            'Hệ điều hành' => 'bi-windows',
                                            'Color' => 'bi-palette',
                                            'Màu sắc' => 'bi-palette',
                                            'Weight' => 'bi-rulers',
                                            'Trọng lượng' => 'bi-rulers',
                                        ];
                                        
                                        function getAttributeIcon($attributeName, $attributeIcons) {
                                            $name = strtolower($attributeName);
                                            foreach ($attributeIcons as $key => $icon) {
                                                if (str_contains(strtolower($key), $name) || str_contains($name, strtolower($key))) {
                                                    return $icon;
                                                }
                                            }
                                            return 'bi-gear'; // Icon mặc định
                                        }
                                    @endphp
                                    @foreach ($product->variants as $variant)
                                        <div class="col-12">
                                            <div class="card variant-card {{ $variant->id == $defaultVariant->id ? 'border-primary border-2' : 'border' }}"
                                                data-variant-id="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                                data-stock="{{ $variant->stock }}" 
                                                style="cursor: pointer; transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <strong class="text-danger fs-5">{{ number_format($variant->price) }}₫</strong>
                                                            </div>
                                                            <div class="variant-specs">
                                                                @foreach ($variant->attributeValues->sortBy(function($item) {
                                                                    $order = ['CPU', 'Processor', 'RAM', 'Memory', 'Storage', 'SSD', 'HDD', 'GPU', 'Graphics', 'VGA', 'Display', 'Screen', 'Battery', 'OS'];
                                                                    $name = $item->attribute->name ?? '';
                                                                    $index = array_search($name, $order);
                                                                    return $index !== false ? $index : 999;
                                                                }) as $attrValue)
                                                                    @php
                                                                        $attrName = $attrValue->attribute->name ?? '';
                                                                        $icon = getAttributeIcon($attrName, $attributeIcons);
                                                                    @endphp
                                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                                        <i class="bi {{ $icon }} text-primary" style="font-size: 1rem; min-width: 20px;"></i>
                                                                        <span class="small">
                                                                            <strong>{{ $attrName }}:</strong> 
                                                                            <span class="text-muted">{{ $attrValue->value }}</span>
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="ms-3">
                                                            @if ($variant->stock > 0)
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
                                <strong><i class="bi bi-info-circle me-2"></i>Thông tin cấu hình:</strong>
                                <div id="variant-details" class="mt-2">
                                    @if ($defaultVariant)
                                        @foreach ($defaultVariant->attributeValues->sortBy(function($item) {
                                            $order = ['CPU', 'Processor', 'RAM', 'Memory', 'Storage', 'SSD', 'HDD', 'GPU', 'Graphics', 'VGA', 'Display', 'Screen', 'Battery', 'OS'];
                                            $name = $item->attribute->name ?? '';
                                            $index = array_search($name, $order);
                                            return $index !== false ? $index : 999;
                                        }) as $attrValue)
                                            @php
                                                $attrName = $attrValue->attribute->name ?? '';
                                                $icon = getAttributeIcon($attrName, $attributeIcons);
                                            @endphp
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="bi {{ $icon }} text-primary" style="font-size: 1.1rem; min-width: 24px;"></i>
                                                <span>
                                                    <strong>{{ $attrName }}:</strong> 
                                                    <span class="text-muted">{{ $attrValue->value }}</span>
                                                </span>
                                            </div>
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
                                        @php
                                          $relatedProductImage = $related->images->sortBy('sort_order')->first();
                                          $relatedImageUrl = $relatedProductImage 
                                            ? asset('storage/' . $relatedProductImage->image_path)
                                            : ($relatedVariant && $relatedVariant->image
                                                ? (str_starts_with($relatedVariant->image, 'http')
                                                    ? $relatedVariant->image
                                                    : asset('storage/' . $relatedVariant->image))
                                                : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop');
                                        @endphp
                                        <img src="{{ $relatedImageUrl }}"
                                            class="card-img-top w-100 h-100" alt="{{ $related->name }}"
                                            style="object-fit: cover;"
                                            onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop'">
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
                            name: "{{ addslashes($attrValue->attribute->name ?? '') }}",
                            value: "{{ addslashes($attrValue->value) }}"
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

            // Icon mapping cho attributes
            const attributeIcons = {
                'CPU': 'bi-cpu',
                'Processor': 'bi-cpu',
                'RAM': 'bi-memory',
                'Memory': 'bi-memory',
                'Storage': 'bi-hdd',
                'SSD': 'bi-hdd',
                'HDD': 'bi-hdd',
                'GPU': 'bi-gpu-card',
                'Graphics': 'bi-gpu-card',
                'VGA': 'bi-gpu-card',
                'Display': 'bi-display',
                'Screen': 'bi-display',
                'Màn hình': 'bi-display',
                'Battery': 'bi-battery-full',
                'Pin': 'bi-battery-full',
                'OS': 'bi-windows',
                'Operating System': 'bi-windows',
                'Hệ điều hành': 'bi-windows',
                'Color': 'bi-palette',
                'Màu sắc': 'bi-palette',
                'Weight': 'bi-rulers',
                'Trọng lượng': 'bi-rulers',
            };

            function getAttributeIcon(attributeName) {
                const name = attributeName.toLowerCase();
                for (const [key, icon] of Object.entries(attributeIcons)) {
                    if (name.includes(key.toLowerCase()) || key.toLowerCase().includes(name)) {
                        return icon;
                    }
                }
                return 'bi-gear'; // Icon mặc định
            }

            // Sắp xếp attributes theo thứ tự ưu tiên
            function sortAttributes(attributes) {
                const order = ['CPU', 'Processor', 'RAM', 'Memory', 'Storage', 'SSD', 'HDD', 'GPU', 'Graphics', 'VGA', 'Display', 'Screen', 'Battery', 'OS'];
                return attributes.sort((a, b) => {
                    const indexA = order.findIndex(o => a.name.toLowerCase().includes(o.toLowerCase()));
                    const indexB = order.findIndex(o => b.name.toLowerCase().includes(o.toLowerCase()));
                    return (indexA === -1 ? 999 : indexA) - (indexB === -1 ? 999 : indexB);
                });
            }

            // Function to update variant details
            function updateVariantDetails(variant) {
                const variantDetails = document.querySelector('#variant-details');
                if (variantDetails && variant.attributes) {
                    let html = '';
                    const sortedAttributes = sortAttributes([...variant.attributes]);
                    
                    sortedAttributes.forEach(attr => {
                        const icon = getAttributeIcon(attr.name);
                        html += `
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi ${icon} text-primary" style="font-size: 1.1rem; min-width: 24px;"></i>
                                <span>
                                    <strong>${attr.name}:</strong> 
                                    <span class="text-muted">${attr.value}</span>
                                </span>
                            </div>
                        `;
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
