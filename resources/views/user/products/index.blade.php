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
                            <!-- Category Filter -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Danh mục</h6>
                                <div class="list-group">
                                    <a href="{{ route('products.index') }}"
                                        class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                        Tất cả
                                    </a>
                                    @foreach ($categories as $category)
                                        <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                            class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-funnel-fill me-2"></i> Bộ lọc Sản phẩm
                                </div>
                                <div class="card-body">
                                    @forelse ($availableFilters as $attributeName => $values)
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
                                    @empty
                                        <p class="text-muted">Không có bộ lọc biến thể nào.</p>
                                    @endforelse

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
                                <div class="card product-card h-100">
                                    <div class="position-relative overflow-hidden" style="height: 240px;">
                                        <img src="{{ $defaultVariant->image ?? 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop' }}"
                                            class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                                            style="object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge bg-success">Mới</span>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title fw-bold">
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="text-decoration-none text-dark">{{ $product->name }}</a>
                                        </h6>
                                        <p class="text-muted small mb-2">
                                            @if ($defaultVariant && $defaultVariant->attributeValues->count() > 0)
                                                @foreach ($defaultVariant->attributeValues->take(3) as $attrValue)
                                                    {{ $attrValue->value }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ Str::limit($product->description, 50) }}
                                            @endif
                                        </p>

                                        <!-- Price -->
                                        <div class="price-container mb-3">
                                            @if ($minPrice && $maxPrice && $minPrice != $maxPrice)
                                                <span class="price-new">{{ number_format($minPrice) }}₫ -
                                                    {{ number_format($maxPrice) }}₫</span>
                                            @elseif($defaultVariant)
                                                <span class="price-new">{{ number_format($defaultVariant->price) }}₫</span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        <div class="mb-3">
                                            <span
                                                class="stock-status {{ $defaultVariant && $defaultVariant->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                                                <i
                                                    class="bi bi-{{ $defaultVariant && $defaultVariant->stock > 0 ? 'check' : 'x' }}-circle-fill"></i>
                                                {{ $defaultVariant && $defaultVariant->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                            </span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="product-actions">
                                            @if ($defaultVariant && $defaultVariant->stock > 0)
                                                <button class="btn btn-primary btn-sm flex-grow-1 add-to-cart-btn"
                                                    data-variant-id="{{ $defaultVariant->id }}">
                                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm flex-grow-1" disabled>
                                                    <i class="bi bi-x-circle"></i> Hết hàng
                                                </button>
                                            @endif
                                            <a href="{{ route('products.show', $product->slug) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> Xem
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links() }}
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
