@extends('user.layout')

@section('title', 'Trang chủ - ' . config('constants.site.name'))

@push('styles')
<style>
  /* Additional page-specific styles */
  .section-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-color), transparent);
    margin: 4rem 0;
  }
  .brands-section {
  background: #fff;
}

.brand-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 20px;
  align-items: center;
}

.brand-item {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 16px;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #eee;
  transition: all 0.3s ease;
  text-decoration: none;
}

.brand-item img {
  max-height: 40px;
  max-width: 100%;
  filter: grayscale(100%);
  opacity: 0.8;
  transition: all 0.3s ease;
}

.brand-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  border-color: var(--bs-primary);
}

.brand-item:hover img {
  filter: grayscale(0%);
  opacity: 1;
}

</style>
@endpush

@section('content')
<!--begin::Banner Section with Quick Links-->
<div class="row g-4 mb-5">
  <!-- Quick Links Sidebar -->
  <div class="col-lg-3 d-none d-lg-block">
    <div class="card border-0 shadow-lg h-100" style="border-radius: var(--radius-xl); overflow: hidden;">
      <div class="card-header bg-primary text-white p-3" style="background: var(--gradient-primary);">
        <h6 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i> Danh mục nhanh</h6>
      </div>
      <div class="card-body p-0">
        <ul class="list-unstyled mb-0">
          @foreach(config('constants.categories') as $key => $category)
          <li class="quick-link-item">
            <a href="#" class="d-flex align-items-center p-3 text-decoration-none border-bottom" style="transition: all 0.3s;">
              <div class="quick-link-icon me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i class="bi {{ $category['icon'] }}" style="color: {{ $category['color'] }}; font-size: 1.25rem;"></i>
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold" style="color: var(--text-primary);">{{ $category['name'] }}</div>
                <small class="text-muted">{{ count($category['subcategories']) }} thương hiệu</small>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </a>
          </li>
          @endforeach
          <li class="quick-link-item">
            <a href="#" class="d-flex align-items-center p-3 text-decoration-none">
              <div class="quick-link-icon me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(244, 63, 94, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-tag-fill" style="color: var(--danger-color); font-size: 1.25rem;"></i>
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold" style="color: var(--text-primary);">Khuyến mãi</div>
                <small class="text-muted">Giảm đến 50%</small>
              </div>
              <i class="bi bi-chevron-right text-muted"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main Banner Slider -->
  <div class="col-lg-9">
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="{{ config('constants.banner.interval') }}">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="3"></button>
      </div>
      <div class="carousel-inner" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl);">
        <div class="carousel-item active">
          <div class="position-relative" style="height: {{ config('constants.banner.height_desktop') }}; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1400&h=600&fit=crop" class="d-block w-100 h-100" alt="Banner 1" style="object-fit: cover;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="carousel-content p-4 p-lg-5">
                      <div class="badge bg-danger mb-3 px-3 py-2" style="border-radius: var(--radius-lg); font-size: 0.9rem;">MỚI NHẤT 2024</div>
                      <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1.2;">Laptop Gaming RTX 40 Series</h1>
                      <p class="lead text-white mb-4" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">Hiệu năng vượt trội, trải nghiệm gaming đỉnh cao với công nghệ mới nhất</p>
                      <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-bag-check me-2"></i> Mua ngay
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-info-circle me-2"></i> Tìm hiểu thêm
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="position-relative" style="height: {{ config('constants.banner.height_desktop') }}; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=1400&h=600&fit=crop" class="d-block w-100 h-100" alt="Banner 2" style="object-fit: cover;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="carousel-content p-4 p-lg-5">
                      <div class="badge bg-success mb-3 px-3 py-2" style="border-radius: var(--radius-lg); font-size: 0.9rem;">BÁN CHẠY</div>
                      <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1.2;">MacBook Pro M3 Max</h1>
                      <p class="lead text-white mb-4" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">Sức mạnh xử lý đồ họa chuyên nghiệp, hiệu năng vượt trội cho công việc</p>
                      <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-bag-check me-2"></i> Mua ngay
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-info-circle me-2"></i> Xem chi tiết
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="position-relative" style="height: {{ config('constants.banner.height_desktop') }}; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=1400&h=600&fit=crop" class="d-block w-100 h-100" alt="Banner 3" style="object-fit: cover;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="carousel-content p-4 p-lg-5">
                      <div class="badge bg-warning mb-3 px-3 py-2" style="border-radius: var(--radius-lg); font-size: 0.9rem;">KHUYẾN MÃI</div>
                      <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1.2;">Laptop Văn Phòng</h1>
                      <p class="lead text-white mb-4" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">Mỏng nhẹ, pin lâu, hiệu năng ổn định cho công việc hàng ngày</p>
                      <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-bag-check me-2"></i> Mua ngay
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-tag me-2"></i> Xem khuyến mãi
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="position-relative" style="height: {{ config('constants.banner.height_desktop') }}; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=1400&h=600&fit=crop" class="d-block w-100 h-100" alt="Banner 4" style="object-fit: cover;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-7">
                    <div class="carousel-content p-4 p-lg-5">
                      <div class="badge bg-info mb-3 px-3 py-2" style="border-radius: var(--radius-lg); font-size: 0.9rem;">ĐỒ HỌA</div>
                      <h1 class="display-4 fw-bold text-white mb-3" style="text-shadow: 0 4px 12px rgba(0,0,0,0.3); line-height: 1.2;">Laptop Đồ Họa Chuyên Nghiệp</h1>
                      <p class="lead text-white mb-4" style="text-shadow: 0 2px 8px rgba(0,0,0,0.3);">Hiệu năng xử lý đồ họa mạnh mẽ, màn hình sắc nét cho designer</p>
                      <div class="d-flex gap-3 flex-wrap">
                        <a href="#" class="btn btn-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-bag-check me-2"></i> Mua ngay
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4 py-3" style="border-radius: var(--radius-lg); font-weight: 600;">
                          <i class="bi bi-palette me-2"></i> Xem bộ sưu tập
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</div>
<!--end::Banner Section-->

<!--begin::Flash Sale-->
@if(config('constants.flash_sale.enabled'))
<div class="card flash-sale-card mb-5">
  <div class="card-header flash-sale-header text-white d-flex justify-content-between align-items-center position-relative">
    <h4 class="mb-0"><i class="bi {{ config('constants.sections.flash_sale.icon') }}"></i> {{ config('constants.sections.flash_sale.title') }}</h4>
    <div class="countdown-timer">
      <i class="bi bi-clock"></i> Còn lại: <span id="countdown">{{ config('constants.flash_sale.countdown_end_time') }}</span>
    </div>
  </div>
  <div class="card-body p-4">
    <div class="row g-4">
      @forelse($flashSaleProducts as $product)
      <div class="col-lg-3 col-md-4 col-sm-6">
          @if($product->defaultVariant)
        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
          <div class="card product-card h-100 position-relative" style="transition: all 0.3s; cursor: pointer;">
          <div class="position-relative overflow-hidden" style="height: 240px;">
              @php
                $primaryImage = $product->images->sortBy('sort_order')->first();
                $productImage = $primaryImage 
                  ? asset('storage/' . $primaryImage->image_path)
                  : ($product->defaultVariant && $product->defaultVariant->image 
                      ? (str_starts_with($product->defaultVariant->image, 'http') 
                          ? $product->defaultVariant->image 
                          : asset('storage/' . $product->defaultVariant->image))
                      : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop');
              @endphp
              <img src="{{ $productImage }}"
                   class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                   style="object-fit: cover; transition: transform 0.3s;"
                   onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop'">
              @if($product->defaultVariant->hasDiscount())
                <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                  -{{ $product->defaultVariant->getDiscountPercent() }}%
                </span>
              @endif
            </div>
            <div class="card-body d-flex flex-column">
              <!-- Tên sản phẩm -->
              <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>

              <!-- Mô tả -->
              <p class="text-muted small mb-2" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao', 80) }}
              </p>

              <!-- Rating -->
              <div class="product-rating mb-2">
                @php
                  $avgRating = $product->average_rating ?? 0;
                  $totalReviews = $product->total_reviews ?? 0;
                @endphp
                <div class="stars">
                  @for($star = 1; $star <= 5; $star++)
                    <i class="bi bi-star{{ $star <= round($avgRating) ? '-fill' : '' }}" style="color: {{ $star <= round($avgRating) ? '#ffc107' : '#ddd' }};"></i>
                  @endfor
                </div>
                <span class="rating-text">({{ $totalReviews }})</span>
              </div>

              <!-- Price Section -->
              <div class="price-container mb-3">
                @php
                  $oldPrice = $product->defaultVariant->old_price ?? 0;
                  $hasDiscount = $product->defaultVariant->hasDiscount();
                @endphp
                @if($hasDiscount || $oldPrice > 0)
                  <!-- Giá cũ (gạch ngang) -->
                  <div class="mb-1">
                    <span class="text-muted" style="text-decoration: line-through; font-size: 0.9rem;">
                      {{ number_format($oldPrice > 0 ? $oldPrice : 0) }}₫
                    </span>
                  </div>
                @endif
                <!-- Giá mới (đỏ) -->
                <div class="mb-1">
                  <span class="text-danger fw-bold" style="font-size: 1.2rem;">
                    {{ number_format($product->defaultVariant->price) }}₫
                  </span>
                </div>
                @if($hasDiscount)
                  <!-- Phần trăm giảm -->
                  <div class="mb-2">
                    <span class="badge bg-danger">
                      Giảm {{ $product->defaultVariant->getDiscountPercent() }}%
                    </span>
                  </div>
                @endif
              </div>

              <!-- Specs -->
              @if($product->defaultVariant->attributeValues->count() > 0)
              <div class="product-specs mb-3">
                @foreach($product->defaultVariant->attributeValues->take(3) as $attrValue)
                <div class="spec-item">
                  <i class="bi bi-check-circle"></i>
                  <span>{{ $attrValue->attribute->name ?? '' }}: {{ $attrValue->value }}</span>
                </div>
                @endforeach
              </div>
              @endif
            </div>
          </div>
        </a>
          @endif
      </div>
      @empty
      <div class="col-12">
        <p class="text-center text-muted">Chưa có sản phẩm flash sale</p>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endif
<!--end::Flash Sale-->

<!--begin::Brands Section-->
<div class="brands-section mb-5">
  <h3 class="fw-bold mb-4 text-center">
    <i class="bi bi-award-fill" style="color: {{ config('constants.theme.primary') }};"></i>
    Thương hiệu nổi bật
  </h3>

  <div class="brand-grid">
    <a href="/thuong-hieu/asus" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/ASUS_Logo.svg" alt="ASUS">
    </a>

    <a href="/thuong-hieu/msi" class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/11/MSI-Logo.png" alt="MSI">
    </a>

    <a href="/thuong-hieu/dell" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/4/48/Dell_Logo.svg" alt="Dell">
    </a>

    <a href="/thuong-hieu/hp" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/HP_logo_2012.svg" alt="HP">
    </a>

    <a href="/thuong-hieu/lenovo" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Lenovo_logo_2015.svg" alt="Lenovo">
    </a>

    <a href="/thuong-hieu/apple" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple">
    </a>

    <a href="/thuong-hieu/acer" class="brand-item">
      <img src="https://upload.wikimedia.org/wikipedia/commons/0/00/Acer_2011.svg" alt="Acer">
    </a>
  </div>
</div>


<!--end::Brands Section-->

<!--begin::Featured Products-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.sections.featured.icon') }}" style="color: {{ config('constants.sections.featured.color') }};"></i> {{ config('constants.sections.featured.title') }}</h3>
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @forelse($featuredProducts as $product)
    <div class="col-lg-3 col-md-4 col-sm-6">
      @if($product->defaultVariant)
      <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
        <div class="card product-card h-100" style="transition: all 0.3s; cursor: pointer;">
          <div class="position-relative overflow-hidden" style="height: 240px;">
            @php
              $primaryImage = $product->images->sortBy('sort_order')->first();
              $productImage = $primaryImage 
                ? asset('storage/' . $primaryImage->image_path)
                : ($product->defaultVariant && $product->defaultVariant->image 
                    ? (str_starts_with($product->defaultVariant->image, 'http') 
                        ? $product->defaultVariant->image 
                        : asset('storage/' . $product->defaultVariant->image))
                    : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop');
            @endphp
            <img src="{{ $productImage }}"
                 class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                 style="object-fit: cover; transition: transform 0.3s;"
                 onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop'">
            @if($product->defaultVariant->hasDiscount())
              <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                -{{ $product->defaultVariant->getDiscountPercent() }}%
              </span>
            @endif
          </div>
          <div class="card-body d-flex flex-column">
            <!-- Tên sản phẩm -->
            <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>

            <!-- Mô tả -->
            <p class="text-muted small mb-2" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
              {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao', 80) }}
            </p>

            <!-- Rating -->
            <div class="product-rating mb-2">
              @php
                $avgRating = $product->average_rating ?? 0;
                $totalReviews = $product->total_reviews ?? 0;
              @endphp
              <div class="stars">
                @for($star = 1; $star <= 5; $star++)
                  <i class="bi bi-star{{ $star <= round($avgRating) ? '-fill' : '' }}" style="color: {{ $star <= round($avgRating) ? '#ffc107' : '#ddd' }};"></i>
                @endfor
              </div>
              <span class="rating-text">({{ $totalReviews }})</span>
            </div>

            <!-- Price Section -->
            <div class="price-container mb-3">
              @php
                $oldPrice = $product->defaultVariant->old_price ?? 0;
                $hasDiscount = $product->defaultVariant->hasDiscount();
              @endphp
              @if($hasDiscount || $oldPrice > 0)
                <!-- Giá cũ (gạch ngang) -->
                <div class="mb-1">
                  <span class="text-muted" style="text-decoration: line-through; font-size: 0.9rem;">
                    {{ number_format($oldPrice > 0 ? $oldPrice : 0) }}₫
                  </span>
                </div>
              @endif
              <!-- Giá mới (đỏ) -->
              <div class="mb-1">
                <span class="text-danger fw-bold" style="font-size: 1.2rem;">
                  {{ number_format($product->defaultVariant->price) }}₫
                </span>
              </div>
              @if($hasDiscount)
                <!-- Phần trăm giảm -->
                <div class="mb-2">
                  <span class="badge bg-danger">
                    Giảm {{ $product->defaultVariant->getDiscountPercent() }}%
                  </span>
                </div>
              @endif
            </div>

            <!-- Specs -->
            @if($product->defaultVariant->attributeValues->count() > 0)
            <div class="product-specs mb-3">
              @foreach($product->defaultVariant->attributeValues->take(3) as $attrValue)
              <div class="spec-item">
                <i class="bi bi-check-circle"></i>
                <span>{{ $attrValue->attribute->name ?? '' }}: {{ $attrValue->value }}</span>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
      </a>
      @endif
    </div>
    @empty
    <div class="col-12">
      <p class="text-center text-muted">Chưa có sản phẩm nổi bật</p>
    </div>
    @endforelse
  </div>
</div>
<!--end::Featured Products-->

<div class="section-divider"></div>

<!--begin::Laptop Gaming-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.categories.gaming.icon') }}" style="color: {{ config('constants.categories.gaming.color') }};"></i> {{ config('constants.categories.gaming.name') }}</h3>
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @forelse($gamingProducts ?? [] as $product)
    <div class="col-lg-3 col-md-4 col-sm-6">
      @if($product->defaultVariant)
      <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
        <div class="card product-card h-100" style="transition: all 0.3s; cursor: pointer;">
          <div class="position-relative overflow-hidden" style="height: 240px;">
            @php
              $primaryImage = $product->images->sortBy('sort_order')->first();
              $productImage = $primaryImage 
                ? asset('storage/' . $primaryImage->image_path)
                : ($product->defaultVariant && $product->defaultVariant->image 
                    ? (str_starts_with($product->defaultVariant->image, 'http') 
                        ? $product->defaultVariant->image 
                        : asset('storage/' . $product->defaultVariant->image))
                    : 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop');
            @endphp
            <img src="{{ $productImage }}"
                 class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                 style="object-fit: cover; transition: transform 0.3s;"
                 onerror="this.src='https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop'">
            @if($product->defaultVariant->hasDiscount())
              <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                -{{ $product->defaultVariant->getDiscountPercent() }}%
              </span>
            @endif
            <div class="position-absolute top-0 start-0 m-3">
              <span class="badge bg-danger">Gaming</span>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <!-- Tên sản phẩm -->
            <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>

            <!-- Mô tả -->
            <p class="text-muted small mb-2" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
              {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao', 80) }}
            </p>

            <!-- Rating -->
            <div class="product-rating mb-2">
              @php
                $avgRating = $product->average_rating ?? 0;
                $totalReviews = $product->total_reviews ?? 0;
              @endphp
              <div class="stars">
                @for($star = 1; $star <= 5; $star++)
                  <i class="bi bi-star{{ $star <= round($avgRating) ? '-fill' : '' }}" style="color: {{ $star <= round($avgRating) ? '#ffc107' : '#ddd' }};"></i>
                @endfor
              </div>
              <span class="rating-text">({{ $totalReviews }})</span>
            </div>

            <!-- Price Section -->
            <div class="price-container mb-3">
              @php
                $oldPrice = $product->defaultVariant->old_price ?? 0;
                $hasDiscount = $product->defaultVariant->hasDiscount();
              @endphp
              @if($hasDiscount || $oldPrice > 0)
                <!-- Giá cũ (gạch ngang) -->
                <div class="mb-1">
                  <span class="text-muted" style="text-decoration: line-through; font-size: 0.9rem;">
                    {{ number_format($oldPrice > 0 ? $oldPrice : 0) }}₫
                  </span>
                </div>
              @endif
              <!-- Giá mới (đỏ) -->
              <div class="mb-1">
                <span class="text-danger fw-bold" style="font-size: 1.2rem;">
                  {{ number_format($product->defaultVariant->price) }}₫
                </span>
              </div>
              @if($hasDiscount)
                <!-- Phần trăm giảm -->
                <div class="mb-2">
                  <span class="badge bg-danger">
                    Giảm {{ $product->defaultVariant->getDiscountPercent() }}%
                  </span>
                </div>
              @endif
            </div>

            <!-- Specs -->
            @if($product->defaultVariant->attributeValues->count() > 0)
            <div class="product-specs mb-3">
              @foreach($product->defaultVariant->attributeValues->take(3) as $attrValue)
              <div class="spec-item">
                <i class="bi bi-check-circle"></i>
                <span>{{ $attrValue->attribute->name ?? '' }}: {{ $attrValue->value }}</span>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
      </a>
      @endif
    </div>
    @empty
    <div class="col-12">
      <p class="text-center text-muted">Chưa có sản phẩm laptop gaming</p>
    </div>
    @endforelse
  </div>
</div>
<!--end::Laptop Gaming-->

<div class="section-divider"></div>

<!--begin::Laptop Văn Phòng-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.categories.office.icon') }}" style="color: {{ config('constants.categories.office.color') }};"></i> {{ config('constants.categories.office.name') }}</h3>
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @forelse($officeProducts ?? [] as $product)
    <div class="col-lg-3 col-md-4 col-sm-6">
      @if($product->defaultVariant)
      <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
        <div class="card product-card h-100" style="transition: all 0.3s; cursor: pointer;">
          <div class="position-relative overflow-hidden" style="height: 240px;">
            @php
              $primaryImage = $product->images->sortBy('sort_order')->first();
              $productImage = $primaryImage 
                ? asset('storage/' . $primaryImage->image_path)
                : ($product->defaultVariant && $product->defaultVariant->image 
                    ? (str_starts_with($product->defaultVariant->image, 'http') 
                        ? $product->defaultVariant->image 
                        : asset('storage/' . $product->defaultVariant->image))
                    : 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop');
            @endphp
            <img src="{{ $productImage }}"
                 class="card-img-top w-100 h-100" alt="{{ $product->name }}"
                 style="object-fit: cover; transition: transform 0.3s;"
                 onerror="this.src='https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop'">
            @if($product->defaultVariant->hasDiscount())
              <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                -{{ $product->defaultVariant->getDiscountPercent() }}%
              </span>
            @endif
            <div class="position-absolute top-0 start-0 m-3">
              <span class="badge bg-info">Văn phòng</span>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <!-- Tên sản phẩm -->
            <h6 class="card-title fw-bold mb-2">{{ $product->name }}</h6>

            <!-- Mô tả -->
            <p class="text-muted small mb-2" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
              {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao', 80) }}
            </p>

            <!-- Rating -->
            <div class="product-rating mb-2">
              @php
                $avgRating = $product->average_rating ?? 0;
                $totalReviews = $product->total_reviews ?? 0;
              @endphp
              <div class="stars">
                @for($star = 1; $star <= 5; $star++)
                  <i class="bi bi-star{{ $star <= round($avgRating) ? '-fill' : '' }}" style="color: {{ $star <= round($avgRating) ? '#ffc107' : '#ddd' }};"></i>
                @endfor
              </div>
              <span class="rating-text">({{ $totalReviews }})</span>
            </div>

            <!-- Price Section -->
            <div class="price-container mb-3">
              @php
                $oldPrice = $product->defaultVariant->old_price ?? 0;
                $hasDiscount = $product->defaultVariant->hasDiscount();
              @endphp
              @if($hasDiscount || $oldPrice > 0)
                <!-- Giá cũ (gạch ngang) -->
                <div class="mb-1">
                  <span class="text-muted" style="text-decoration: line-through; font-size: 0.9rem;">
                    {{ number_format($oldPrice > 0 ? $oldPrice : 0) }}₫
                  </span>
                </div>
              @endif
              <!-- Giá mới (đỏ) -->
              <div class="mb-1">
                <span class="text-danger fw-bold" style="font-size: 1.2rem;">
                  {{ number_format($product->defaultVariant->price) }}₫
                </span>
              </div>
              @if($hasDiscount)
                <!-- Phần trăm giảm -->
                <div class="mb-2">
                  <span class="badge bg-danger">
                    Giảm {{ $product->defaultVariant->getDiscountPercent() }}%
                  </span>
                </div>
              @endif
            </div>

            <!-- Specs -->
            @if($product->defaultVariant->attributeValues->count() > 0)
            <div class="product-specs mb-3">
              @foreach($product->defaultVariant->attributeValues->take(3) as $attrValue)
              <div class="spec-item">
                <i class="bi bi-check-circle"></i>
                <span>{{ $attrValue->attribute->name ?? '' }}: {{ $attrValue->value }}</span>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
      </a>
      @endif
    </div>
    @empty
    <div class="col-12">
      <p class="text-center text-muted">Chưa có sản phẩm laptop văn phòng</p>
    </div>
    @endforelse
  </div>
</div>
<!--end::Laptop Văn Phòng-->

<div class="section-divider"></div>

<!--begin::Featured Display Products-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.sections.featured_display.icon') }}" style="color: {{ config('constants.sections.featured_display.color') }};"></i> {{ config('constants.sections.featured_display.title') }}</h3>
  </div>
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card featured-display-card border-0 position-relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=400&fit=crop" class="card-img" alt="Banner" style="height: 350px; object-fit: cover;">
        <div class="card-img-overlay d-flex align-items-end">
          <div class="text-white">
            <h4 class="fw-bold mb-2">MacBook Pro M3 Max</h4>
            <p class="mb-3">Hiệu năng vượt trội cho công việc chuyên nghiệp. Chip M3 Max mạnh mẽ nhất từ Apple.</p>
            <a href="#" class="btn btn-light btn-lg">{{ config('constants.buttons.view_detail') }}</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card featured-display-card border-0 position-relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=400&fit=crop" class="card-img" alt="Banner" style="height: 350px; object-fit: cover;">
        <div class="card-img-overlay d-flex align-items-end">
          <div class="text-white">
            <h4 class="fw-bold mb-2">ASUS ROG Strix</h4>
            <p class="mb-3">Trải nghiệm gaming đỉnh cao với RTX 40 series. Hiệu năng vượt trội cho mọi tựa game.</p>
            <a href="#" class="btn btn-light btn-lg">{{ config('constants.buttons.view_detail') }}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Featured Display Products-->

<!--begin::Testimonials Section-->
<div class="mb-5">
  <h3 class="fw-bold mb-4 text-center"><i class="bi bi-chat-quote-fill" style="color: {{ config('constants.theme.primary') }};"></i> Khách hàng nói gì về chúng tôi</h3>
  <div class="row g-4">
    @for($i = 1; $i <= 3; $i++)
    <div class="col-lg-4 col-md-6">
      <div class="card testimonial-card h-100">
        <div class="testimonial-header">
          <div class="testimonial-avatar">
            {{ chr(64 + $i) }}
          </div>
          <div>
            <h6 class="mb-0 fw-bold">Khách hàng {{ $i }}</h6>
            <small class="text-muted">Đã mua {{ 5 + $i * 2 }} sản phẩm</small>
          </div>
        </div>
        <div class="testimonial-rating">
          @for($star = 1; $star <= 5; $star++)
            <i class="bi bi-star-fill"></i>
          @endfor
        </div>
        <p class="testimonial-text">
          "Sản phẩm chất lượng tốt, giao hàng nhanh chóng. Nhân viên tư vấn nhiệt tình, chuyên nghiệp. Tôi rất hài lòng với dịch vụ của cửa hàng!"
        </p>
        <div class="testimonial-author">
          - Nguyễn Văn {{ chr(64 + $i) }}
        </div>
      </div>
    </div>
    @endfor
  </div>
</div>
<!--end::Testimonials Section-->
@endsection

@push('scripts')
<script>
  // Countdown Timer
  function updateCountdown() {
    const now = new Date().getTime();
    const endOfDay = new Date();
    endOfDay.setHours(23, 59, 59, 999);
    const distance = endOfDay - now;

    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerHTML =
      String(hours).padStart(2, '0') + ":" +
      String(minutes).padStart(2, '0') + ":" +
      String(seconds).padStart(2, '0');
  }

  setInterval(updateCountdown, 1000);
  updateCountdown();

  // Add to cart animation
  document.querySelectorAll('.btn-primary, .btn-danger').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (this.textContent.includes('Thêm') || this.textContent.includes('Mua')) {
        const ripple = document.createElement('span');
        ripple.style.cssText = `
          position: absolute;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.6);
          transform: scale(0);
          animation: ripple 0.6s linear;
          pointer-events: none;
        `;
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
        ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
      }
    });
  });

  // Add ripple animation
  const style = document.createElement('style');
  style.textContent = `
    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);

  // Add to cart functionality
  document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const variantId = this.getAttribute('data-variant-id');
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
          product_variant_id: variantId,
          quantity: 1
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          btn.innerHTML = '<i class="bi bi-check-circle"></i> Đã thêm';
          btn.classList.remove('btn-danger', 'btn-primary');
          btn.classList.add('btn-success');

          // Update cart count in header if exists
          const cartBadge = document.querySelector('.cart-count-badge');
          if (cartBadge) {
            cartBadge.textContent = data.cart_count;
          }

          setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-danger', 'btn-primary');
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

