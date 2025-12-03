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
      @for($i = 1; $i <= 4; $i++)
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="card product-card h-100 position-relative">
          <span class="flash-sale-badge discount-badge position-absolute top-0 end-0 m-3">-{{ 25 + $i * 5 }}%</span>
          <div class="position-relative overflow-hidden" style="height: 220px;">
            <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top w-100 h-100" alt="Product" style="object-fit: cover;">
            <div class="position-absolute top-0 start-0 m-2">
              <span class="badge bg-danger">HOT</span>
            </div>
          </div>
          <div class="card-body d-flex flex-column">
            <h6 class="card-title fw-bold">ASUS ROG Strix G15 RTX {{ 4050 + $i * 10 }}</h6>
            <p class="text-muted small mb-2">AMD Ryzen 7, 16GB RAM, 512GB SSD</p>

            <!-- Rating -->
            <div class="product-rating mb-2">
              <div class="stars">
                @for($star = 1; $star <= 5; $star++)
                  <i class="bi bi-star{{ $star <= (4 + ($i % 2)) ? '-fill' : '' }}"></i>
                @endfor
              </div>
              <span class="rating-text">({{ 120 + $i * 15 }})</span>
            </div>

            <!-- Price -->
            <div class="price-container mb-3">
              <span class="price-old">{{ number_format(25000000 - $i * 2000000) }}₫</span>
              <span class="price-new">{{ number_format(18000000 - $i * 1500000) }}₫</span>
            </div>

            <!-- Features -->
            <div class="product-features mb-3">
              <span class="feature-badge"><i class="bi bi-check-circle"></i> Bảo hành 24T</span>
              <span class="feature-badge"><i class="bi bi-truck"></i> Freeship</span>
            </div>

            <!-- Stock Status -->
            <div class="mb-3">
              <span class="stock-status in-stock">
                <i class="bi bi-check-circle-fill"></i> Còn hàng
              </span>
            </div>

            <!-- Actions -->
            <div class="product-actions">
              <button class="btn btn-danger btn-sm flex-grow-1">
                <i class="bi bi-cart-plus"></i> {{ config('constants.buttons.buy_now') }}
              </button>
              <button class="btn btn-outline-danger btn-sm">
                <i class="bi bi-heart"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      @endfor
    </div>
  </div>
</div>
@endif
<!--end::Flash Sale-->

<!--begin::Brands Section-->
<div class="brands-section mb-5">
  <h3 class="fw-bold mb-4 text-center"><i class="bi bi-award-fill" style="color: {{ config('constants.theme.primary') }};"></i> Thương hiệu nổi bật</h3>
  <div class="brand-grid">
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/11/ASUS-Logo.png" alt="ASUS" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/11/MSI-Logo.png" alt="MSI" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/Dell-Logo.png" alt="Dell" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/HP-Logo.png" alt="HP" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/Lenovo-Logo.png" alt="Lenovo" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/Apple-Logo.png" alt="Apple" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/Acer-Logo.png" alt="Acer" style="max-height: 40px;">
    </div>
    <div class="brand-item">
      <img src="https://logos-world.net/wp-content/uploads/2020/06/HP-Logo.png" alt="HP" style="max-height: 40px;">
    </div>
  </div>
</div>
<!--end::Brands Section-->

<!--begin::Featured Products-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.sections.featured.icon') }}" style="color: {{ config('constants.sections.featured.color') }};"></i> {{ config('constants.sections.featured.title') }}</h3>
    <a href="#" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @for($i = 1; $i <= 4; $i++)
    <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="card product-card h-100">
        <div class="position-relative overflow-hidden" style="height: 240px;">
          <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top w-100 h-100" alt="Product" style="object-fit: cover;">
          <div class="position-absolute top-0 start-0 m-3">
            <span class="badge bg-success">Mới</span>
          </div>
          <div class="position-absolute top-0 end-0 m-3">
            <button class="btn btn-sm btn-light rounded-circle p-2">
              <i class="bi bi-heart"></i>
            </button>
          </div>
        </div>
        <div class="card-body d-flex flex-column">
          <h6 class="card-title fw-bold">MacBook Pro 14" M3 Pro</h6>
          <p class="text-muted small mb-2">M3 Pro, 18GB RAM, 512GB SSD, 14.2" Liquid Retina XDR</p>

          <!-- Rating -->
          <div class="product-rating mb-2">
            <div class="stars">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <span class="rating-text">({{ 245 + $i * 12 }})</span>
          </div>

          <!-- Price -->
          <div class="price-container mb-3">
            <span class="price-new">{{ number_format(42990000 - $i * 2000000) }}₫</span>
          </div>

          <!-- Specs -->
          <div class="product-specs mb-3">
            <div class="spec-item">
              <i class="bi bi-cpu"></i>
              <span>M3 Pro</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-memory"></i>
              <span>18GB RAM</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-hdd"></i>
              <span>512GB SSD</span>
            </div>
          </div>

          <!-- Warranty -->
          <div class="mb-3">
            <span class="warranty-badge">
              <i class="bi bi-shield-check"></i> Bảo hành 12 tháng
            </span>
          </div>

          <!-- Actions -->
          <div class="product-actions">
            <button class="btn btn-primary btn-sm flex-grow-1">
              <i class="bi bi-cart-plus"></i> {{ config('constants.buttons.add_to_cart') }}
            </button>
            <button class="btn btn-outline-danger btn-sm">
              <i class="bi bi-heart"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endfor
  </div>
</div>
<!--end::Featured Products-->

<div class="section-divider"></div>

<!--begin::Laptop Gaming-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.categories.gaming.icon') }}" style="color: {{ config('constants.categories.gaming.color') }};"></i> {{ config('constants.categories.gaming.name') }}</h3>
    <a href="#" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @for($i = 1; $i <= 4; $i++)
    <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="card product-card h-100">
        <div class="position-relative overflow-hidden" style="height: 240px;">
          <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop" class="card-img-top w-100 h-100" alt="Product" style="object-fit: cover;">
          <div class="position-absolute top-0 start-0 m-3">
            <span class="badge bg-danger">Gaming</span>
          </div>
        </div>
        <div class="card-body d-flex flex-column">
          <h6 class="card-title fw-bold">ASUS ROG Zephyrus G14 RTX {{ 4060 + $i * 10 }}</h6>
          <p class="text-muted small mb-2">AMD Ryzen 9, 32GB RAM, 1TB SSD, 14" QHD 165Hz</p>

          <div class="product-rating mb-2">
            <div class="stars">
              @for($star = 1; $star <= 5; $star++)
                <i class="bi bi-star{{ $star <= 4 ? '-fill' : '' }}"></i>
              @endfor
            </div>
            <span class="rating-text">({{ 89 + $i * 8 }})</span>
          </div>

          <div class="price-container mb-3">
            <span class="price-new">{{ number_format(45990000 - $i * 2000000) }}₫</span>
          </div>

          <div class="product-specs mb-3">
            <div class="spec-item">
              <i class="bi bi-gpu-card"></i>
              <span>RTX {{ 4060 + $i * 10 }}</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-cpu"></i>
              <span>Ryzen 9</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-display"></i>
              <span>165Hz</span>
            </div>
          </div>

          <div class="product-actions">
            <button class="btn btn-primary btn-sm flex-grow-1">
              <i class="bi bi-cart-plus"></i> {{ config('constants.buttons.add_to_cart') }}
            </button>
            <button class="btn btn-outline-danger btn-sm">
              <i class="bi bi-heart"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endfor
  </div>
</div>
<!--end::Laptop Gaming-->

<div class="section-divider"></div>

<!--begin::Laptop Văn Phòng-->
<div class="mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi {{ config('constants.categories.office.icon') }}" style="color: {{ config('constants.categories.office.color') }};"></i> {{ config('constants.categories.office.name') }}</h3>
    <a href="#" class="btn btn-outline-primary">{{ config('constants.buttons.view_all') }} <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4">
    @for($i = 1; $i <= 4; $i++)
    <div class="col-lg-3 col-md-4 col-sm-6">
      <div class="card product-card h-100">
        <div class="position-relative overflow-hidden" style="height: 240px;">
          <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop" class="card-img-top w-100 h-100" alt="Product" style="object-fit: cover;">
          <div class="position-absolute top-0 start-0 m-3">
            <span class="badge bg-info">Văn phòng</span>
          </div>
        </div>
        <div class="card-body d-flex flex-column">
          <h6 class="card-title fw-bold">Dell Latitude 5540</h6>
          <p class="text-muted small mb-2">Intel Core i7, 16GB RAM, 512GB SSD, 15.6" FHD</p>

          <div class="product-rating mb-2">
            <div class="stars">
              @for($star = 1; $star <= 5; $star++)
                <i class="bi bi-star{{ $star <= (4 + ($i % 2)) ? '-fill' : '' }}"></i>
              @endfor
            </div>
            <span class="rating-text">({{ 156 + $i * 10 }})</span>
          </div>

          <div class="price-container mb-3">
            <span class="price-new">{{ number_format(18990000 - $i * 1000000) }}₫</span>
          </div>

          <div class="product-specs mb-3">
            <div class="spec-item">
              <i class="bi bi-cpu"></i>
              <span>Intel i7</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-battery-full"></i>
              <span>Pin 8h</span>
            </div>
            <div class="spec-item">
              <i class="bi bi-lightning"></i>
              <span>Nhẹ 1.6kg</span>
            </div>
          </div>

          <div class="product-actions">
            <button class="btn btn-primary btn-sm flex-grow-1">
              <i class="bi bi-cart-plus"></i> {{ config('constants.buttons.add_to_cart') }}
            </button>
            <button class="btn btn-outline-danger btn-sm">
              <i class="bi bi-heart"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endfor
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
</script>
@endpush
