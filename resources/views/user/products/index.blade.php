@extends('user.layout')

@section('title', 'Sản phẩm - ' . config('constants.site.name'))

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-funnel"></i> Bộ lọc</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('products.index') }}">
                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 border-bottom pb-2">Danh mục</h6>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('products.index', request()->except(['category', 'page'])) }}"
                                        class="list-group-item list-group-item-action border-0 ps-0 {{ !request('category') ? 'text-primary fw-bold' : '' }}">
                                        <i class="bi bi-chevron-right small"></i> Tất cả sản phẩm
                                    </a>
                                    @foreach ($categories as $category)
                                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $category->id])) }}"
                                            class="list-group-item list-group-item-action border-0 ps-0 {{ request('category') == $category->id ? 'text-primary fw-bold' : '' }}">
                                            <i class="bi bi-chevron-right small"></i> {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-funnel-fill me-2"></i> Bộ lọc Sản phẩm
                                </div>
                                <div class="card-body">
                                    @foreach ($availableFilters as $attributeName => $values)
                                        <div class="mb-4 pb-3 border-bottom">
                                            <h6 class="fw-bold text-dark text-capitalize">{{ $attributeName }}</h6>
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($values as $attrValue)
                                                    @php
                                                        // Kiểm tra xem giá trị này có đang được chọn không
                                                        $isChecked = in_array(
                                                            $attrValue->value,
                                                            request($attributeName, []),
                                                        );
                                                    @endphp
                                                    <div class="form-check">
                                                        {{-- Tên input PHẢI khớp với tên Attribute trong DB --}}
                                                        <input class="form-check-input" type="checkbox"
                                                            name="{{ $attributeName }}[]" value="{{ $attrValue->value }}"
                                                            id="filter-{{ $attributeName }}-{{ Str::slug($attrValue->value) }}"
                                                            @checked($isChecked)>
                                                        <label class="form-check-label"
                                                            for="filter-{{ $attributeName }}-{{ Str::slug($attrValue->value) }}">
                                                            {{ $attrValue->value }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                
                                    @endforeach

                                    <!-- Search -->
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3">Tìm kiếm</h6>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Nhập tên sản phẩm..." value="{{ request('search') }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mt-3">
                                        <i class="bi bi-filter me-2"></i> Áp dụng Bộ lọc
                                    </button>

                                    @if (!empty(request()->except(['page', 'search', 'category'])))
                                        <a href="{{ route('products.index', ['search' => request('search'), 'category' => request('category')]) }}"
                                            class="btn btn-outline-secondary w-100 mt-2">
                                            <i class="bi bi-x-circle me-2"></i> Xóa Bộ lọc
                                        </a>
                                    @endif

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold">Sản phẩm</h3>
                    <div class="text-muted">
                        Tìm thấy {{ $products->total() }} sản phẩm
                    </div>
                </div>

                @if ($products->count() > 0)
                    <div class="row g-4">
                        @foreach ($products as $product)
                            @php
                                $defaultVariant = $product->variants->first();
                                $minPrice = $product->variants->min('price');
                                $maxPrice = $product->variants->max('price');
                            @endphp
                            <div class="col-lg-4 col-md-6">
                                @if ($defaultVariant)
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                        <div class="card product-card h-100" style="transition: all 0.3s; cursor: pointer;">
                                            <div class="position-relative overflow-hidden" style="height: 240px;">
                                                <img src="{{ $defaultVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop' }}"
                                                    class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                                                    style="object-fit: cover; transition: transform 0.3s;">
                                                @if ($defaultVariant->hasDiscount())
                                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                                        -{{ $defaultVariant->getDiscountPercent() }}%
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <!-- Tên sản phẩm -->
                                                <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>

                                                <!-- Mô tả -->
                                                <p class="text-muted small mb-2"
                                                    style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao', 80) }}
                                                </p>

                                                <!-- Rating -->
                                                <div class="product-rating mb-2">
                                                    @php
                                                        $avgRating = $product->average_rating ?? 0;
                                                        $totalReviews = $product->total_reviews ?? 0;
                                                    @endphp
                                                    <div class="stars">
                                                        @for ($star = 1; $star <= 5; $star++)
                                                            <i class="bi bi-star{{ $star <= round($avgRating) ? '-fill' : '' }}"
                                                                style="color: {{ $star <= round($avgRating) ? '#ffc107' : '#ddd' }};"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="rating-text">({{ $totalReviews }})</span>
                                                </div>

                                                <!-- Price Section -->
                                                <div class="price-container mb-3">
                                                    @php
                                                        $oldPrice = $defaultVariant->old_price ?? 0;
                                                        $hasDiscount = $defaultVariant->hasDiscount();
                                                    @endphp
                                                    @if ($hasDiscount || $oldPrice > 0)
                                                        <!-- Giá cũ (gạch ngang) -->
                                                        <div class="mb-1">
                                                            <span class="text-muted"
                                                                style="text-decoration: line-through; font-size: 0.9rem;">
                                                                {{ number_format($oldPrice > 0 ? $oldPrice : 0) }}₫
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <!-- Giá mới (đỏ) -->
                                                    <div class="mb-1">
                                                        <span class="text-danger fw-bold" style="font-size: 1.2rem;">
                                                            {{ number_format($defaultVariant->price) }}₫
                                                        </span>
                                                    </div>
                                                    @if ($hasDiscount)
                                                        <!-- Phần trăm giảm -->
                                                        <div class="mb-2">
                                                            <span class="badge bg-danger">
                                                                Giảm {{ $defaultVariant->getDiscountPercent() }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Specs -->
                                                @if ($defaultVariant->attributeValues->count() > 0)
                                                    <div class="product-specs mb-3">
                                                        @foreach ($defaultVariant->attributeValues->take(3) as $attrValue)
                                                            <div class="spec-item">
                                                                <i class="bi bi-check-circle"></i>
                                                                <span>{{ $attrValue->attribute->name ?? '' }}:
                                                                    {{ $attrValue->value }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
                            {{ $products->links() }}
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Không tìm thấy sản phẩm nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Add to cart functionality
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const variantId = this.getAttribute('data-variant-id');
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
                                product_variant_id: variantId,
                                quantity: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                btn.innerHTML = '<i class="bi bi-check-circle"></i> Đã thêm';
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-success');

                                setTimeout(() => {
                                    btn.innerHTML = originalText;
                                    btn.classList.remove('btn-success');
                                    btn.classList.add('btn-primary');
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
            });
        </script>
    @endpush
@endsection
