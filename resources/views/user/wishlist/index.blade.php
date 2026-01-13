@extends('user.layout')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 fw-bold text-primary"><i class="bi bi-heart-fill me-2 text-danger"></i> Danh sách Yêu thích</h1>
            @if ($wishlistItems->isEmpty())
                <div class="card p-5 text-center shadow-sm">
                    <i class="bi bi-heartbreak-fill display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Danh sách yêu thích của bạn đang trống!</h5>
                    <p class="text-muted">Hãy thêm các sản phẩm bạn quan tâm để dễ dàng theo dõi nhé.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3 w-50 mx-auto">Mua sắm ngay</a>
                </div>
            @else
                <div class="list-group">
                    @foreach ($wishlistItems as $item)
                        @php
                            $product = $item->product;
                            $defaultVariant = $product->variants->first();
                        @endphp
                        <div class="list-group-item list-group-item-action wishlist-item-row p-3 mb-3 border rounded-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-4">
                                    @php
                                      $primaryImage = $product->images->sortBy('sort_order')->first();
                                      $productImage = $primaryImage 
                                        ? asset('storage/' . $primaryImage->image_path)
                                        : ($defaultVariant && $defaultVariant->image 
                                            ? (str_starts_with($defaultVariant->image, 'http') 
                                                ? $defaultVariant->image 
                                                : asset('storage/' . $defaultVariant->image))
                                            : 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop');
                                    @endphp
                                    <img src="{{ $productImage }}" class="img-fluid rounded" alt="{{ $product->name }}" style="max-height: 100px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=400&h=300&fit=crop'">
                                </div>
                                <div class="col-md-6 col-8">
                                    <h5 class="mb-1 fw-bold">
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                                    </h5>
                                    <p class="mb-1 text-muted">{{ $product->category->name ?? 'Không rõ' }}</p>
                                    <p class="mb-0 text-danger fw-bold fs-5">{{ number_format($defaultVariant->price_sale ?? $defaultVariant->price, 0, ',', '.') }}₫</p>
                                </div>
                                <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                    <button class="btn btn-sm btn-outline-danger toggle-wishlist-btn" data-product-id="{{ $product->id }}">
                                        <i class="bi bi-heart-fill me-2"></i> Xóa khỏi yêu thích
                                    </button>
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm ms-2">
                                        <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection