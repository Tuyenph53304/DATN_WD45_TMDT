@extends('user.layout')

@section('title', 'Trang chủ')

@section('content')
<!--begin::Hero Section-->
<div class="row mb-4">
  <div class="col-12">
    <div class="card bg-primary text-white">
      <div class="card-body p-5">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h1 class="display-4 fw-bold">Chào mừng đến với E-Commerce</h1>
            <p class="lead mb-4">Khám phá hàng ngàn sản phẩm chất lượng với giá tốt nhất</p>
            <a href="#" class="btn btn-light btn-lg">
              <i class="bi bi-bag-fill"></i> Mua sắm ngay
            </a>
          </div>
          <div class="col-md-4 text-center">
            <i class="bi bi-cart-check" style="font-size: 8rem; opacity: 0.3;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Hero Section-->

<!--begin::Featured Products-->
<div class="row mb-4">
  <div class="col-12">
    <h2 class="mb-4"><i class="bi bi-star-fill text-warning"></i> Sản phẩm nổi bật</h2>
  </div>

  <!--begin::Product Card 1-->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm">
      <img src="{{ asset('assets/img/prod-1.jpg') }}" class="card-img-top" alt="Product 1" style="height: 200px; object-fit: cover;">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">Sản phẩm 1</h5>
        <p class="card-text text-muted">Mô tả sản phẩm ngắn gọn...</p>
        <div class="mt-auto">
          <p class="h4 text-primary mb-3">1,000,000₫</p>
          <a href="#" class="btn btn-primary w-100">
            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::Product Card 1-->

  <!--begin::Product Card 2-->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm">
      <img src="{{ asset('assets/img/prod-2.jpg') }}" class="card-img-top" alt="Product 2" style="height: 200px; object-fit: cover;">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">Sản phẩm 2</h5>
        <p class="card-text text-muted">Mô tả sản phẩm ngắn gọn...</p>
        <div class="mt-auto">
          <p class="h4 text-primary mb-3">1,500,000₫</p>
          <a href="#" class="btn btn-primary w-100">
            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::Product Card 2-->

  <!--begin::Product Card 3-->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm">
      <img src="{{ asset('assets/img/prod-3.jpg') }}" class="card-img-top" alt="Product 3" style="height: 200px; object-fit: cover;">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">Sản phẩm 3</h5>
        <p class="card-text text-muted">Mô tả sản phẩm ngắn gọn...</p>
        <div class="mt-auto">
          <p class="h4 text-primary mb-3">2,000,000₫</p>
          <a href="#" class="btn btn-primary w-100">
            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::Product Card 3-->

  <!--begin::Product Card 4-->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm">
      <img src="{{ asset('assets/img/prod-4.jpg') }}" class="card-img-top" alt="Product 4" style="height: 200px; object-fit: cover;">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title">Sản phẩm 4</h5>
        <p class="card-text text-muted">Mô tả sản phẩm ngắn gọn...</p>
        <div class="mt-auto">
          <p class="h4 text-primary mb-3">2,500,000₫</p>
          <a href="#" class="btn btn-primary w-100">
            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::Product Card 4-->
</div>
<!--end::Featured Products-->

<!--begin::Categories-->
<div class="row mb-4">
  <div class="col-12">
    <h2 class="mb-4"><i class="bi bi-grid-3x3-gap-fill text-info"></i> Danh mục sản phẩm</h2>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card text-center border-0 shadow-sm">
      <div class="card-body">
        <i class="bi bi-phone" style="font-size: 3rem; color: #0d6efd;"></i>
        <h5 class="card-title mt-3">Điện thoại</h5>
        <a href="#" class="btn btn-sm btn-outline-primary">Xem thêm</a>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card text-center border-0 shadow-sm">
      <div class="card-body">
        <i class="bi bi-laptop" style="font-size: 3rem; color: #20c997;"></i>
        <h5 class="card-title mt-3">Laptop</h5>
        <a href="#" class="btn btn-sm btn-outline-success">Xem thêm</a>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card text-center border-0 shadow-sm">
      <div class="card-body">
        <i class="bi bi-headphones" style="font-size: 3rem; color: #ffc107;"></i>
        <h5 class="card-title mt-3">Tai nghe</h5>
        <a href="#" class="btn btn-sm btn-outline-warning">Xem thêm</a>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
    <div class="card text-center border-0 shadow-sm">
      <div class="card-body">
        <i class="bi bi-watch" style="font-size: 3rem; color: #dc3545;"></i>
        <h5 class="card-title mt-3">Đồng hồ</h5>
        <a href="#" class="btn btn-sm btn-outline-danger">Xem thêm</a>
      </div>
    </div>
  </div>
</div>
<!--end::Categories-->
@endsection

