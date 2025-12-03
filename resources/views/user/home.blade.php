@extends('user.layout')

@section('title', 'Trang chủ - LaptopStore')

@push('styles')
<style>
  /* Additional page-specific styles if needed */
</style>
@endpush

@section('content')
<!--begin::Banner Slider-->
<div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="4000">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
  </div>
  <div class="carousel-inner rounded">
    <div class="carousel-item active">
      <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1200&h=500&fit=crop" class="d-block w-100" alt="Banner 1">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-4">
        <h2 class="display-4 fw-bold">Laptop Gaming Mới Nhất 2024</h2>
        <p class="lead">Hiệu năng vượt trội, trải nghiệm gaming đỉnh cao</p>
        <a href="#" class="btn btn-primary btn-lg">Xem ngay</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=1200&h=500&fit=crop" class="d-block w-100" alt="Banner 2">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-4">
        <h2 class="display-4 fw-bold">MacBook Pro M3</h2>
        <p class="lead">Sức mạnh xử lý đồ họa chuyên nghiệp</p>
        <a href="#" class="btn btn-primary btn-lg">Khám phá</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=1200&h=500&fit=crop" class="d-block w-100" alt="Banner 3">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-4">
        <h2 class="display-4 fw-bold">Laptop Văn Phòng</h2>
        <p class="lead">Mỏng nhẹ, pin lâu, hiệu năng ổn định</p>
        <a href="#" class="btn btn-primary btn-lg">Mua ngay</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
<!--end::Banner Slider-->

<!--begin::Flash Sale-->
<div class="card flash-sale-card mb-4">
  <div class="card-header flash-sale-header text-white d-flex justify-content-between align-items-center position-relative">
    <h4 class="mb-0"><i class="bi bi-lightning-fill"></i> FLASH SALE - Giảm sốc đến 50%</h4>
    <div class="countdown-timer">
      <i class="bi bi-clock"></i> Còn lại: <span id="countdown">23:59:59</span>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card product-card h-100 position-relative">
          <span class="flash-sale-badge discount-badge">-30%</span>
          <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">ASUS ROG Strix G15</h6>
            <p class="text-muted small mb-2">RTX 4060, AMD Ryzen 7</p>
            <div class="mb-2">
              <span class="price-old">25.990.000₫</span>
              <span class="price-new ms-2">18.190.000₫</span>
            </div>
            <button class="btn btn-danger btn-sm mt-auto">Mua ngay</button>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card product-card h-100 position-relative">
          <span class="flash-sale-badge discount-badge">-25%</span>
          <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">MSI Katana GF66</h6>
            <p class="text-muted small mb-2">RTX 4050, Intel i7</p>
            <div class="mb-2">
              <span class="price-old">22.990.000₫</span>
              <span class="price-new ms-2">17.240.000₫</span>
            </div>
            <button class="btn btn-danger btn-sm mt-auto">Mua ngay</button>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card product-card h-100 position-relative">
          <span class="flash-sale-badge discount-badge">-40%</span>
          <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">Acer Predator Helios</h6>
            <p class="text-muted small mb-2">RTX 4070, Intel i9</p>
            <div class="mb-2">
              <span class="price-old">35.990.000₫</span>
              <span class="price-new ms-2">21.590.000₫</span>
            </div>
            <button class="btn btn-danger btn-sm mt-auto">Mua ngay</button>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card product-card h-100 position-relative">
          <span class="flash-sale-badge discount-badge">-35%</span>
          <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">Lenovo Legion 5</h6>
            <p class="text-muted small mb-2">RTX 4060, AMD Ryzen 5</p>
            <div class="mb-2">
              <span class="price-old">24.990.000₫</span>
              <span class="price-new ms-2">16.240.000₫</span>
            </div>
            <button class="btn btn-danger btn-sm mt-auto">Mua ngay</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Flash Sale-->

<!--begin::Featured Products-->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-star-fill text-warning"></i> Sản phẩm nổi bật</h3>
    <a href="#" class="btn btn-outline-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">MacBook Pro 14" M3</h6>
          <p class="text-muted small mb-2">M3 Pro, 18GB RAM, 512GB SSD</p>
          <div class="mb-2">
            <span class="price-new">42.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Dell XPS 15</h6>
          <p class="text-muted small mb-2">Intel i7, 16GB RAM, RTX 4050</p>
          <div class="mb-2">
            <span class="price-new">38.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">HP Spectre x360</h6>
          <p class="text-muted small mb-2">Intel i7, 16GB RAM, 1TB SSD</p>
          <div class="mb-2">
            <span class="price-new">35.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Lenovo ThinkPad X1</h6>
          <p class="text-muted small mb-2">Intel i7, 16GB RAM, 512GB SSD</p>
          <div class="mb-2">
            <span class="price-new">32.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Featured Products-->

<!--begin::Laptop Gaming-->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-controller text-danger"></i> Laptop Gaming</h3>
    <a href="#" class="btn btn-outline-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">ASUS ROG Zephyrus G14</h6>
          <p class="text-muted small mb-2">RTX 4070, AMD Ryzen 9</p>
          <div class="mb-2">
            <span class="price-new">45.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">MSI Raider GE78</h6>
          <p class="text-muted small mb-2">RTX 4080, Intel i9</p>
          <div class="mb-2">
            <span class="price-new">62.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Acer Predator Triton</h6>
          <p class="text-muted small mb-2">RTX 4060, Intel i7</p>
          <div class="mb-2">
            <span class="price-new">28.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Lenovo Legion Pro 7</h6>
          <p class="text-muted small mb-2">RTX 4090, AMD Ryzen 9</p>
          <div class="mb-2">
            <span class="price-new">68.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Laptop Gaming-->

<!--begin::Laptop Văn Phòng-->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-briefcase text-info"></i> Laptop Văn Phòng</h3>
    <a href="#" class="btn btn-outline-primary">Xem tất cả <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Dell Latitude 5540</h6>
          <p class="text-muted small mb-2">Intel i5, 8GB RAM, 256GB SSD</p>
          <div class="mb-2">
            <span class="price-new">18.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">HP EliteBook 840</h6>
          <p class="text-muted small mb-2">Intel i7, 16GB RAM, 512GB SSD</p>
          <div class="mb-2">
            <span class="price-new">24.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">Lenovo ThinkPad E14</h6>
          <p class="text-muted small mb-2">Intel i5, 8GB RAM, 512GB SSD</p>
          <div class="mb-2">
            <span class="price-new">16.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card product-card h-100 shadow-sm">
        <img src="https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=400&h=300&fit=crop" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
        <div class="card-body d-flex flex-column">
          <h6 class="card-title">ASUS VivoBook S15</h6>
          <p class="text-muted small mb-2">Intel i5, 8GB RAM, 256GB SSD</p>
          <div class="mb-2">
            <span class="price-new">15.990.000₫</span>
          </div>
          <div class="d-flex gap-2 mt-auto">
            <button class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-cart-plus"></i> Thêm giỏ</button>
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Laptop Văn Phòng-->

<!--begin::Featured Display Products-->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold"><i class="bi bi-trophy-fill text-warning"></i> Sản phẩm trưng bày</h3>
  </div>
  <div class="row">
    <div class="col-lg-6 mb-3">
      <div class="card featured-display-card border-0 position-relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&h=400&fit=crop" class="card-img" alt="Banner" style="height: 300px; object-fit: cover;">
        <div class="card-img-overlay d-flex align-items-end">
          <div class="text-white p-4">
            <h4 class="fw-bold">MacBook Pro M3 Max</h4>
            <p class="mb-2">Hiệu năng vượt trội cho công việc chuyên nghiệp</p>
            <a href="#" class="btn btn-light">Khám phá ngay</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 mb-3">
      <div class="card featured-display-card border-0 position-relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&h=400&fit=crop" class="card-img" alt="Banner" style="height: 300px; object-fit: cover;">
        <div class="card-img-overlay d-flex align-items-end">
          <div class="text-white p-4">
            <h4 class="fw-bold">ASUS ROG Strix</h4>
            <p class="mb-2">Trải nghiệm gaming đỉnh cao với RTX 40 series</p>
            <a href="#" class="btn btn-light">Xem chi tiết</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Featured Display Products-->
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
</script>
@endpush
