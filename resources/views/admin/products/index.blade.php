@extends('admin.layout')

@section('title', 'Quản lý Sản phẩm - Admin Panel')
@section('page-title', 'Quản lý Sản phẩm')

@push('styles')
<style>
  .product-table {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: var(--card-shadow);
  }

  .product-image-preview {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
  }

  .pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
    padding: 20px 0;
  }

  .pagination-wrapper .pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
  }

  .pagination-wrapper .pagination li {
    display: inline-block;
  }

  .pagination-wrapper .pagination .page-link,
  .pagination-wrapper .pagination .page-item span {
    display: block;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    color: #475569;
    background: white;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
    font-weight: 600;
    min-width: 44px;
    text-align: center;
  }

  .pagination-wrapper .pagination .page-item.active .page-link,
  .pagination-wrapper .pagination .page-item.active span {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
  }

  .pagination-wrapper .pagination .page-link:hover:not(.disabled) {
    background: #f1f5f9;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-2px);
    text-decoration: none;
  }

  .pagination-wrapper .pagination .page-item.disabled .page-link,
  .pagination-wrapper .pagination .page-item.disabled span {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
  }
</style>
@endpush

@section('content')
<div class="modern-card">
  <div class="modern-card-header">
    <h3 class="modern-card-title">
      <i class="bi bi-box-seam"></i>
      Danh sách Sản phẩm
    </h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-gradient btn-modern">
      <i class="bi bi-plus-circle me-1"></i> Tạo Sản phẩm mới
    </a>
  </div>

  <div class="product-table">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text" style="background: #f1f5f9; border: 2px solid #e2e8f0; border-right: none;">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Tìm kiếm theo tên, mô tả..."
                   style="border: 2px solid #e2e8f0; border-left: none;">
          </div>
        </div>
        <div class="col-md-3">
          <select name="category_id" class="form-control" style="border: 2px solid #e2e8f0;">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select name="status" class="form-control" style="border: 2px solid #e2e8f0;">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-gradient btn-modern w-100">
            <i class="bi bi-search me-1"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Products Table -->
    @if($products->count() > 0)
    <div class="table-responsive">
      <table class="table modern-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Số variants</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>
              @if($product->images->count() > 0)
                @php
                  $firstImage = $product->images->sortBy('sort_order')->first();
                  $imageUrl = asset('storage/' . $firstImage->image_path);
                @endphp
                <img src="{{ $imageUrl }}" 
                     alt="{{ $product->name }}" 
                     class="product-image-preview"
                     onerror="this.src='{{ asset('assets/img/default-150x150.png') }}'">
              @elseif($product->variants->count() > 0 && $product->variants->first()->image)
                @php
                  $variantImage = $product->variants->first()->image;
                  $variantImageUrl = str_starts_with($variantImage, 'http') 
                    ? $variantImage 
                    : asset('storage/' . $variantImage);
                @endphp
                <img src="{{ $variantImageUrl }}" 
                     alt="{{ $product->name }}" 
                     class="product-image-preview"
                     onerror="this.src='{{ asset('assets/img/default-150x150.png') }}'">
              @else
                <img src="{{ asset('assets/img/default-150x150.png') }}" 
                     alt="No image" 
                     class="product-image-preview">
              @endif
            </td>
            <td><strong>{{ $product->name }}</strong></td>
            <td>{{ $product->category->name ?? '-' }}</td>
            <td><span class="badge badge-modern bg-info">{{ $product->variants->count() }}</span></td>
            <td>
              <span class="badge badge-modern {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $product->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-modern" 
                   style="background: var(--info-gradient); color: white;" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-modern" 
                   style="background: var(--warning-gradient); color: white;" title="Sửa">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-modern" 
                          style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-wrapper">
      {{ $products->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-5">
      <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e1;"></i>
      <p class="mt-3 mb-0" style="color: #64748b; font-size: 1.1rem;">
        <i class="bi bi-info-circle me-2"></i> Không tìm thấy sản phẩm nào.
      </p>
    </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Custom pagination styling
  document.addEventListener('DOMContentLoaded', function() {
    const pagination = document.querySelector('.pagination');
    if (pagination) {
      // Wrap pagination in custom structure if needed
      const paginationItems = pagination.querySelectorAll('li');
      paginationItems.forEach(item => {
        const link = item.querySelector('a, span');
        if (link && link.textContent.trim() === '') {
          // Handle prev/next arrows
          if (item.classList.contains('disabled')) {
            link.style.opacity = '0.5';
            link.style.cursor = 'not-allowed';
          }
        }
      });
    }
  });
</script>
@endpush
