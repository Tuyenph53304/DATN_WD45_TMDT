@extends('admin.layout')

@section('title', 'Chi tiết Sản phẩm - Admin Panel')
@section('page-title', 'Chi tiết Sản phẩm')

@push('styles')
<style>
  .product-images-section {
    margin-top: 30px;
  }

  .primary-image-container {
    margin-bottom: 20px;
  }

  .primary-image-wrapper {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    border: 3px solid #667eea;
    aspect-ratio: 1;
    max-width: 500px;
  }

  .primary-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .primary-image-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .product-images-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 20px;
  }

  .product-image-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    aspect-ratio: 1;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .product-image-item:hover {
    border-color: #667eea;
    transform: scale(1.05);
  }

  .product-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .product-image-item .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .product-image-item:hover .image-overlay {
    opacity: 1;
  }

  .variant-card {
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    background: white;
    transition: all 0.3s ease;
  }

  .variant-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
  }

  .attribute-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.85rem;
    font-weight: 600;
    margin: 3px;
  }
</style>
@endpush

@section('content')
<div class="row g-4">
  <div class="col-md-8">
    <div class="modern-card">
      <div class="modern-card-header">
        <h3 class="modern-card-title">
          <i class="bi bi-box-seam"></i>
          Thông tin Sản phẩm
        </h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-modern" style="background: #f1f5f9; color: #475569;">
          <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
      </div>
      <div class="card-body" style="padding: 30px;">
        <table class="table modern-table">
          <tr>
            <th width="200">ID</th>
            <td>{{ $product->id }}</td>
          </tr>
          <tr>
            <th>Tên sản phẩm</th>
            <td><strong>{{ $product->name }}</strong></td>
          </tr>
          <tr>
            <th>Danh mục</th>
            <td>{{ $product->category->name ?? '-' }}</td>
          </tr>
          <tr>
            <th>Mô tả</th>
            <td>{{ $product->description ?? '-' }}</td>
          </tr>
          <tr>
            <th>Trạng thái</th>
            <td>
              <span class="badge badge-modern {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $product->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Số variants</th>
            <td><span class="badge badge-modern bg-info">{{ $product->variants->count() }}</span></td>
          </tr>
          <tr>
            <th>Số ảnh</th>
            <td><span class="badge badge-modern bg-primary">{{ $product->images->count() }}</span></td>
          </tr>
        </table>

        <div class="mt-4">
          <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-modern" 
             style="background: var(--warning-gradient); color: white;">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa
          </a>
          <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-modern" 
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
              <i class="bi bi-trash me-1"></i> Xóa
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="modern-card">
      <div class="modern-card-header">
        <h3 class="modern-card-title">
          <i class="bi bi-images"></i>
          Ảnh sản phẩm
        </h3>
      </div>
      <div class="card-body" style="padding: 30px;">
        @if($product->images->count() > 0)
        <div class="product-images-section">
          @php
            $sortedImages = $product->images->sortBy('sort_order');
            $primaryImage = $sortedImages->first();
            $otherImages = $sortedImages->skip(1);
          @endphp
          
          <!-- Primary Image -->
          <div class="primary-image-container">
            <div class="primary-image-wrapper">
              <span class="primary-image-badge">Ảnh đại diện</span>
              <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                   alt="Primary product image"
                   onerror="this.src='{{ asset('assets/img/default-150x150.png') }}'">
            </div>
          </div>

          <!-- Other Images -->
          @if($otherImages->count() > 0)
          <div>
            <h6 class="mb-3" style="font-weight: 600; color: #475569;">
              <i class="bi bi-images me-2"></i> Các ảnh khác ({{ $otherImages->count() }})
            </h6>
            <div class="product-images-gallery">
              @foreach($otherImages as $image)
              <div class="product-image-item">
                <img src="{{ asset('storage/' . $image->image_path) }}" 
                     alt="Product image {{ $loop->index + 2 }}"
                     onerror="this.src='{{ asset('assets/img/default-150x150.png') }}'">
                <div class="image-overlay">
                  <form action="{{ route('admin.products.images.delete', [$product, $image]) }}" method="POST" 
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa ảnh này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i> Xóa
                    </button>
                  </form>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif
        </div>
        @else
        <div class="text-center py-4">
          <i class="bi bi-image" style="font-size: 3rem; color: #cbd5e1;"></i>
          <p class="mt-3 mb-0 text-muted">Chưa có ảnh nào</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Variants Section -->
<div class="row g-4 mt-2">
  <div class="col-12">
    <div class="modern-card">
      <div class="modern-card-header">
        <h3 class="modern-card-title">
          <i class="bi bi-box-seam"></i>
          Biến thể sản phẩm ({{ $product->variants->count() }})
        </h3>
      </div>
      <div class="card-body" style="padding: 30px;">
        @if($product->variants->count() > 0)
        <div class="row g-3">
          @foreach($product->variants as $variant)
          <div class="col-md-6">
            <div class="variant-card">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <h6 class="mb-1" style="font-weight: 700;">SKU: {{ $variant->sku }}</h6>
                  <div class="mb-2">
                    @foreach($variant->attributeValues as $attrValue)
                    <span class="attribute-badge">
                      {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                    </span>
                    @endforeach
                  </div>
                </div>
                @if($variant->image)
                <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}" 
                     alt="Variant image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                     onerror="this.style.display='none'">
                @endif
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <small class="text-muted">Giá:</small>
                  <div style="font-weight: 700; color: #ef4444; font-size: 1.1rem;">
                    {{ number_format($variant->price) }}₫
                  </div>
                  @if($variant->old_price && $variant->old_price > $variant->price)
                  <div style="text-decoration: line-through; color: #94a3b8; font-size: 0.9rem;">
                    {{ number_format($variant->old_price) }}₫
                  </div>
                  <div style="color: #10b981; font-size: 0.85rem;">
                    Giảm {{ round((($variant->old_price - $variant->price) / $variant->old_price) * 100) }}%
                  </div>
                  @endif
                </div>
                <div class="col-6">
                  <small class="text-muted">Tồn kho:</small>
                  <div style="font-weight: 700; font-size: 1.1rem; color: {{ $variant->stock > 0 ? '#10b981' : '#ef4444' }};">
                    {{ $variant->stock }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <div class="text-center py-5">
          <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
          <p class="mt-3 mb-0 text-muted">Chưa có biến thể nào</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
